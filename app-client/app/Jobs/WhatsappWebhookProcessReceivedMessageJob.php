<?php

namespace App\Jobs;

use App\Helpers\PhoneHelper;
use App\Models\Customer;
use App\Models\ChatSession;
use App\Models\Message;
use App\Repositories\ChatSessionRepository;
use App\Repositories\MessageRepository;
use App\Services\ErrorLog\ErrorLogService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WhatsappWebhookProcessReceivedMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private array $webhookData,
        private int $companyId,
        private int $unitId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        ChatSessionRepository $chatSessionRepository,
        MessageRepository $messageRepository,
        ErrorLogService $errorLogService
    ): void {
        try {
            $this->logError($errorLogService, ['message' => 'processando mensagem recebida do WhatsApp company: ' . $this->companyId . ' unit: ' . $this->unitId . ' ' . json_encode($this->webhookData)]);

            // Extrair dados da mensagem do webhook
            $messageData = $this->extractMessageData($errorLogService);

            if (!$messageData) {
                $this->logError($errorLogService, ['message' => 'dados da mensagem não encontrados no webhook']);
                return;
            }

            $phone = $messageData['phone'];
            $messageId = $messageData['message_id'];
            $messageContent = $messageData['content'];
            $customerName = $messageData['customer_name'];

            // 1. Verificar se já recebeu esta mensagem anteriormente
            if ($this->isMessageAlreadyProcessed($messageId)) {
                $this->logError($errorLogService, ['message' => 'mensagem já processada anteriormente message_id: ' . $messageId]);
                return;
            }

            // 2. Verificar se o Customer já existe na base para esta company
            $customer = Customer::where('phone', PhoneHelper::unformat($phone))
                ->where('company_id', $this->companyId)
                ->first();

            if (!$customer) {
                // Customer NÃO existe - Disparar Job para responder com link genérico de cadastro
                $this->logError($errorLogService, ['message' => 'customer não encontrado, enviando mensagem genérica phone: ' . $phone . ' company_id: ' . $this->companyId]);

                $genericMessage = "Olá! Bem-vindo ao nosso sistema. Para continuar, acesse nosso link de cadastro: " .
                    route('schedule-link.index', ['company' => $this->companyId]);

                WhatsappSendGenericMessageJob::dispatch(
                    $phone,
                    $genericMessage,
                    $this->companyId,
                    $this->unitId
                );

                return;
            }

            // Customer JÁ existe - Verificar se já existe um ChatSession para este Customer
            $chatSession = $chatSessionRepository->findActiveChatSession([
                'customer_id' => $customer->id,
                'user_id' => null // ChatSession do WhatsApp não tem user_id
            ]);

            if (!$chatSession) {
                // NÃO existe ChatSession - Criar ChatSession
                $this->logError($errorLogService, ['message' => 'criando nova ChatSession para customer customer_id: ' . $customer->id . ' company_id: ' . $this->companyId]);

                $chatSession = $chatSessionRepository->store([
                    'company_id' => $this->companyId,
                    'unit_id' => $this->unitId,
                    'customer_id' => $customer->id,
                    'user_id' => null
                ]);
            }

            // Salvar Message vinculando ao ChatSession
            $this->logError($errorLogService, ['message' => 'salvando mensagem do WhatsApp chat_session_id: ' . $chatSession->id . ' customer_id: ' . $customer->id . ' content: ' . $messageContent]);

            $messageRepository->store([
                'company_id' => $this->companyId,
                'unit_id' => $this->unitId,
                'customer_id' => $customer->id,
                'user_id' => null,
                'chat_session_id' => $chatSession->id,
                'content' => $messageContent,
                'type' => 'text',
                'whatsapp_message_id' => $messageId
            ]);

            // Disparar Job para responder com link personalizado do Customer
            $this->logError($errorLogService, ['message' => 'enviando mensagem personalizada para customer customer_id: ' . $customer->id . ' phone: ' . $phone]);

            $personalizedMessage = "Olá {$customer->name}! Obrigado por entrar em contato. " .
                "Para agendar um horário, acesse: " .
                route('schedule-link.show', ['company' => $this->companyId, 'unit' => $this->unitId]);

            WhatsappSendPersonalizedMessageJob::dispatch(
                $phone,
                $personalizedMessage,
                $this->companyId,
                $this->unitId,
                $customer->id
            );

            $this->logError($errorLogService, ['message' => 'processamento da mensagem do WhatsApp concluído com sucesso']);

        } catch (\Exception $e) {
            $this->logError($errorLogService, ['message' => 'erro ao processar mensagem do WhatsApp company_id: ' . $this->companyId . ' unit_id: ' . $this->unitId . ' error: ' . $e->getMessage()]);

            throw $e;
        }
    }

    /**
     * Extrair dados da mensagem do webhook
     */
    private function extractMessageData(ErrorLogService $errorLogService): ?array
    {
        try {
            $entry = $this->webhookData['entry'][0] ?? null;
            if (!$entry) {
                return null;
            }

            $changes = $entry['changes'][0] ?? null;
            if (!$changes) {
                return null;
            }

            $value = $changes['value'] ?? null;
            if (!$value) {
                return null;
            }

            $messages = $value['messages'][0] ?? null;
            if (!$messages) {
                return null;
            }

            $contacts = $value['contacts'][0] ?? null;
            if (!$contacts) {
                return null;
            }

            return [
                'phone' => $messages['from'],
                'message_id' => $messages['id'],
                'content' => $messages['text']['body'] ?? '',
                'customer_name' => $contacts['profile']['name'] ?? 'Cliente'
            ];

        } catch (\Exception $e) {
            $this->logError($errorLogService, ['message' => 'erro ao extrair dados da mensagem error: ' . $e->getMessage() . ' webhook_data: ' . json_encode($this->webhookData)]);
            return null;
        }
    }

    /**
     * Verificar se a mensagem já foi processada
     */
    private function isMessageAlreadyProcessed(string $messageId): bool
    {
        // Verificar se já existe uma mensagem com este ID do WhatsApp
        return Message::where('whatsapp_message_id', $messageId)
            ->where('company_id', $this->companyId)
            ->exists();
    }

    /**
     * Log de erro seguindo o padrão do controller
     */
    private function logError(ErrorLogService $errorLogService, array $data): void
    {
        $errorLogService->logError(new Exception($data['message']), [
            'action' => 'whatsapp_webhook_process',
            'resolved' => 0,
            'company_id' => $this->companyId,
        ], 'whatsapp_webhook');
    }
}
