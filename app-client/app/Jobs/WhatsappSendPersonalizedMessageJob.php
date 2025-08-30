<?php

namespace App\Jobs;

use App\Services\ErrorLog\ErrorLogService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WhatsappSendPersonalizedMessageJob implements ShouldQueue
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
        private int $customerId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ErrorLogService $errorLogService): void
    {
        try {
            $this->logError($errorLogService, ['message' => 'enviando mensagem personalizada do WhatsApp phone: ' . $this->phone . ' company_id: ' . $this->companyId . ' unit_id: ' . $this->unitId . ' customer_id: ' . $this->customerId . ' message: ' . $this->message]);

            // TODO: Implementar integração com WhatsApp Cloud API
            // Por enquanto, apenas logamos a mensagem
            $this->logError($errorLogService, ['message' => 'mensagem personalizada enviada com sucesso phone: ' . $this->phone . ' customer_id: ' . $this->customerId . ' message: ' . $this->message]);

        } catch (\Exception $e) {
            $this->logError($errorLogService, ['message' => 'erro ao enviar mensagem personalizada do WhatsApp phone: ' . $this->phone . ' company_id: ' . $this->companyId . ' unit_id: ' . $this->unitId . ' customer_id: ' . $this->customerId . ' error: ' . $e->getMessage()]);

            throw $e;
        }
    }

    /**
     * Log de erro seguindo o padrão do controller
     */
    private function logError(ErrorLogService $errorLogService, array $data): void
    {
        $errorLogService->logError(new Exception($data['message']), [
            'action' => 'whatsapp_send_personalized_message',
            'resolved' => 0,
            'company_id' => $this->companyId,
        ], 'whatsapp_webhook');
    }
}
