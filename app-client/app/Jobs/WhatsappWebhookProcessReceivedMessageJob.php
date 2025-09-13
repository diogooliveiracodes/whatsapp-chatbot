<?php

namespace App\Jobs;

use App\Helpers\PhoneHelper;
use App\Jobs\WhatsappSendGenericMessageJob;
use App\Jobs\WhatsappSendPersonalizedMessageJob;
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
use App\Enum\AutomatedMessageTypeEnum;
use App\Models\AutomatedMessage;

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
        private int $companyId
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
            $this->logError($errorLogService, ['message' => 'DEBUG: Extraindo dados da mensagem.' . json_encode($this->webhookData)]);

            // Extrair dados da mensagem do webhook
            $messageData = $this->extractMessageData($errorLogService);

            $this->logError($errorLogService, ['message' => 'DEBUG: Mensagem Extraída.' . json_encode($messageData)]);

            if (!$messageData) {
                $this->logError($errorLogService, ['message' => 'dados da mensagem não encontrados no webhook']);
                return;
            }

            $phone = $messageData['phone'];
            $messageId = $messageData['message_id'];
            $messageContent = $messageData['content'];

            // 1. Verificar se já recebeu esta mensagem anteriormente
            if ($this->isMessageAlreadyProcessed($messageId)) {
                $this->logError($errorLogService, ['message' => 'mensagem já processada anteriormente message_id: ' . $messageId]);
                return;
            }

            if (!$this->isCustomerAlreadyExists($phone)) {
                // Customer NÃO existe - Disparar Job para responder com link genérico de cadastro
                $this->logError($errorLogService, ['message' => 'customer não encontrado, enviando mensagem genérica phone: ' . $phone . ' company_id: ' . $this->companyId]);

                $genericMessage = $this->getGenericMessage($this->companyId);

                WhatsappSendGenericMessageJob::dispatch(
                    $phone,
                    $genericMessage,
                    $this->companyId
                );

                return;
            }

            $customer = $this->getCustomer($phone);
            $chatSession = $this->getChatSession($customer->id, $chatSessionRepository);

            if (!$chatSession) {
                // NÃO existe ChatSession - Criar ChatSession
                $this->logError($errorLogService, ['message' => 'criando nova ChatSession para customer customer_id: ' . $customer->id . ' company_id: ' . $this->companyId]);

                $chatSession = $this->storeChatSession($customer->id, $chatSessionRepository);
                $this->logError($errorLogService, ['message' => 'ChatSession criada com sucesso, chat_session_id: ' . $chatSession->id]);
            }

            // Salvar Message
            $this->storeMessage($customer->id, $chatSession->id, $messageContent, $messageId, $messageRepository);
            $this->logError($errorLogService, ['message' => 'mensagem recebida via webhook salva com sucesso, message_id: ' . $messageId]);

            // Disparar Job para responder com link personalizado do Customer
            $this->logError($errorLogService, ['message' => 'enviando mensagem personalizada para customer customer_id: ' . $customer->id . ' phone: ' . $phone]);

            // TO DO: pegar a mensagem personalizada do Customer
            $personalizedMessage = "Olá {$customer->name}! Obrigado por entrar em contato. " .
                "Para agendar um horário, acesse: " .
                route('schedule-link.show', ['company' => $this->companyId]);

            WhatsappSendPersonalizedMessageJob::dispatch(
                $phone,
                $personalizedMessage,
                $this->companyId,
                $customer->id
            );

            $this->logError($errorLogService, ['message' => 'processamento da mensagem do WhatsApp concluído com sucesso']);

        } catch (\Exception $e) {
            $this->logError($errorLogService, ['message' => 'erro ao processar mensagem do WhatsApp company_id: ' . $this->companyId . ' error: ' . $e->getMessage()]);

            throw $e;
        }
    }

    /**
     * Extrair dados da mensagem do webhook
     */
    private function extractMessageData(ErrorLogService $errorLogService): ?array
    {
        try {
            $this->logError($errorLogService, ['message' => 'DEBUG: Iniciando extração de dados do webhook']);

            // Verificar se é o formato direto (value no nível raiz)
            $value = $this->webhookData['value'] ?? null;

            $this->logError($errorLogService, ['message' => 'DEBUG: Value encontrado, verificando messages e contacts']);

            $messages = $value['messages'][0] ?? null;
            if (!$messages) {
                $this->logError($errorLogService, ['message' => 'DEBUG: Messages não encontrado']);
                return null;
            }

            $contacts = $value['contacts'][0] ?? null;
            if (!$contacts) {
                $this->logError($errorLogService, ['message' => 'DEBUG: Contacts não encontrado']);
                return null;
            }

            $metadata = $value['metadata'] ?? null;
            if (!$metadata) {
                $this->logError($errorLogService, ['message' => 'DEBUG: Metadata não encontrado']);
                return null;
            }

            $this->logError($errorLogService, ['message' => 'DEBUG: Extraindo dados - phone: ' . ($metadata['phone_number_id'] ?? 'N/A') . ', message_id: ' . ($messages['id'] ?? 'N/A')]);

            $result = [
                'whatsapp_phone_number_id' => $metadata['phone_number_id'],
                'phone' => $metadata['display_phone_number'],
                'message_id' => $messages['id'],
                'content' => $messages['text']['body'] ?? '',
                'customer_name' => $contacts['profile']['name'] ?? 'Cliente'
            ];

            $this->logError($errorLogService, ['message' => 'DEBUG: Dados extraídos com sucesso: ' . json_encode($result)]);

            return $result;

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
     * Verificar se o Customer já existe na base para esta company
     */
    private function isCustomerAlreadyExists(string $phone): bool
    {
        return Customer::where('phone', PhoneHelper::unformat($phone))
            ->where('company_id', $this->companyId)
            ->exists();
    }

    private function getGenericMessage($companyId): string
    {
        $automatedMessage = AutomatedMessage::where('company_id', $companyId)
            ->where('type', AutomatedMessageTypeEnum::WELCOME_MESSAGE->value)
            ->first();

        if (!$automatedMessage) {
            return "Olá! Bem-vindo ao nosso sistema. Para continuar, acesse nosso link de cadastro: " .
                route('schedule-link.index', ['company' => $this->companyId]);
        }

        return $automatedMessage->content;
    }

    private function getCustomer(string $phone): Customer
    {
        return Customer::where('phone', PhoneHelper::unformat($phone))
            ->where('company_id', $this->companyId)
            ->first();
    }

    private function getChatSession(int $customerId, ChatSessionRepository $chatSessionRepository): ChatSession
    {
        return $chatSessionRepository->findActiveChatSession([
            'customer_id' => $customerId,
            'company_id' => $this->companyId
        ]);
    }

    private function storeChatSession(int $customerId, ChatSessionRepository $chatSessionRepository): ChatSession
    {
        return $chatSessionRepository->store([
            'customer_id' => $customerId,
            'company_id' => $this->companyId
        ]);
    }

    private function storeMessage(int $customerId, int $chatSessionId, string $messageContent, string $messageId, MessageRepository $messageRepository): Message
    {
        return $messageRepository->store([
            'company_id' => $this->companyId,
            'customer_id' => $customerId,
            'user_id' => null,
            'chat_session_id' => $chatSessionId,
            'content' => $messageContent,
            'type' => 'text',
            'whatsapp_message_id' => $messageId
        ]);
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
