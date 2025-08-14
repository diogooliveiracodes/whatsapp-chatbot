<?php

namespace App\Jobs;

use App\Services\Payment\AsaasPaymentService;
use App\Services\Payment\PaymentService;
use App\Services\ErrorLog\ErrorLogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckPaymentStatusJob implements ShouldQueue
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
        private string $asaasPaymentId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        AsaasPaymentService $asaasPaymentService,
        PaymentService $paymentService,
        ErrorLogService $errorLogService
    ): void {
        try {
            Log::info('Iniciando verificação de status do pagamento', [
                'asaas_payment_id' => $this->asaasPaymentId
            ]);

            // Buscar o pagamento pelo ID do Asaas
            $payment = $paymentService->findByAsaasPaymentId($this->asaasPaymentId);

            if (!$payment) {
                Log::warning('Pagamento não encontrado no sistema', [
                    'asaas_payment_id' => $this->asaasPaymentId
                ]);
                return;
            }

            // Verificar se o pagamento ainda está pendente
            if ($payment->status->value !== 1) { // PENDING
                Log::info('Pagamento já não está mais pendente', [
                    'payment_id' => $payment->id,
                    'asaas_payment_id' => $this->asaasPaymentId,
                    'current_status' => $payment->status->value
                ]);
                return;
            }

            // Verificar status no Asaas
            $asaasResponse = $asaasPaymentService->checkPaymentStatus($this->asaasPaymentId);

            if (!isset($asaasResponse['status'])) {
                Log::error('Resposta inválida do Asaas', [
                    'payment_id' => $payment->id,
                    'asaas_payment_id' => $this->asaasPaymentId,
                    'response' => $asaasResponse
                ]);
                return;
            }

            $asaasStatus = $asaasResponse['status'];
            $internalStatus = $paymentService->mapAsaasStatusToInternal($asaasStatus);

            // Atualizar status do pagamento apenas se houve mudança
            if ($payment->status->value !== $internalStatus) {
                $paidAt = in_array(strtolower($asaasStatus), ['confirmed', 'paid', 'received'])
                    ? now()->toDateTimeString()
                    : null;

                $updated = $paymentService->updatePaymentStatus(
                    $payment->id,
                    $internalStatus,
                    $paidAt
                );

                if ($updated) {
                    Log::info('Status do pagamento atualizado com sucesso', [
                        'payment_id' => $payment->id,
                        'asaas_payment_id' => $this->asaasPaymentId,
                        'old_status' => $payment->status->value,
                        'new_status' => $internalStatus,
                        'asaas_status' => $asaasStatus
                    ]);
                } else {
                    Log::error('Falha ao atualizar status do pagamento', [
                        'payment_id' => $payment->id,
                        'asaas_payment_id' => $this->asaasPaymentId,
                        'new_status' => $internalStatus
                    ]);
                }
            } else {
                Log::info('Status do pagamento permanece o mesmo', [
                    'payment_id' => $payment->id,
                    'asaas_payment_id' => $this->asaasPaymentId,
                    'status' => $internalStatus
                ]);
            }

        } catch (\Exception $e) {
            $errorLogService->logError($e, [
                'action' => 'CheckPaymentStatusJob',
                'asaas_payment_id' => $this->asaasPaymentId
            ]);

            Log::error('Erro ao verificar status do pagamento', [
                'asaas_payment_id' => $this->asaasPaymentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw para que o job seja marcado como falhado
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Job de verificação de pagamento falhou', [
            'asaas_payment_id' => $this->asaasPaymentId,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);
    }
}
