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
        private int $unitId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ErrorLogService $errorLogService): void
    {
        try {
            $this->logError($errorLogService, ['message' => 'enviando mensagem genérica do WhatsApp phone: ' . $this->phone . ' company_id: ' . $this->companyId . ' unit_id: ' . $this->unitId . ' message: ' . $this->message]);

            // TODO: Implementar integração com WhatsApp Cloud API
            // Por enquanto, apenas logamos a mensagem
            $this->logError($errorLogService, ['message' => 'mensagem genérica enviada com sucesso phone: ' . $this->phone . ' message: ' . $this->message]);

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
