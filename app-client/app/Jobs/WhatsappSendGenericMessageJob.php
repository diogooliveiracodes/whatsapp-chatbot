<?php

namespace App\Jobs;

use App\Services\ErrorLog\ErrorLogService;
use App\Services\WhatsApp\WhatsAppService;
use App\Services\WhatsApp\AutomatedMessageService;
use App\Enum\AutomatedMessageTypeEnum;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WhatsappSendGenericMessageJob implements ShouldQueue
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
        private string $phone,
        private string $message,
        private int $companyId,
        private int $unitId,
        private ?AutomatedMessageTypeEnum $messageType = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        ErrorLogService $errorLogService,
        WhatsAppService $whatsAppService,
        AutomatedMessageService $automatedMessageService
    ): void {
        try {
            $this->logError($errorLogService, ['message' => 'enviando mensagem genérica do WhatsApp phone: ' . $this->phone . ' company_id: ' . $this->companyId . ' unit_id: ' . $this->unitId . ' message: ' . $this->message]);

            // Check if WhatsApp is configured for this company
            if (!$whatsAppService->isConfigured($this->companyId)) {
                throw new Exception('WhatsApp não está configurado para esta empresa');
            }

            // If message type is provided, try to get automated message
            if ($this->messageType) {
                $automatedMessage = $automatedMessageService->getMessageByType($this->messageType, $this->unitId);

                if ($automatedMessage) {
                    // Use the automated message content
                    $messageToSend = $automatedMessage->content;

                    Log::info('Using automated message for generic job', [
                        'message_type' => $this->messageType->value,
                        'automated_message_id' => $automatedMessage->id,
                        'unit_id' => $this->unitId
                    ]);
                } else {
                    // Fallback to provided message
                    $messageToSend = $this->message;

                    Log::warning('Automated message not found, using fallback message', [
                        'message_type' => $this->messageType->value,
                        'unit_id' => $this->unitId
                    ]);
                }
            } else {
                // Use provided message directly
                $messageToSend = $this->message;
            }

            // Send message via WhatsApp
            $result = $whatsAppService->sendTextMessage(
                $this->phone,
                $messageToSend,
                $this->companyId,
                $this->unitId
            );

            if (!$result['success']) {
                throw new Exception('Erro ao enviar mensagem via WhatsApp: ' . ($result['error'] ?? 'Erro desconhecido'));
            }

            $this->logError($errorLogService, ['message' => 'mensagem genérica enviada com sucesso phone: ' . $this->phone . ' message_id: ' . ($result['message_id'] ?? 'N/A')]);

        } catch (\Exception $e) {
            $this->logError($errorLogService, ['message' => 'erro ao enviar mensagem genérica do WhatsApp phone: ' . $this->phone . ' company_id: ' . $this->companyId . ' unit_id: ' . $this->unitId . ' error: ' . $e->getMessage()]);

            throw $e;
        }
    }

    /**
     * Log de erro seguindo o padrão do controller
     */
    private function logError(ErrorLogService $errorLogService, array $data): void
    {
        $errorLogService->logError(new Exception($data['message']), [
            'action' => 'whatsapp_send_generic_message',
            'resolved' => 0,
            'company_id' => $this->companyId,
        ], 'whatsapp_webhook');
    }
}
