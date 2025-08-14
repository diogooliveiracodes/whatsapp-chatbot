<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Enum\PaymentStatusEnum;
use App\Jobs\CheckPaymentStatusJob;
use App\Services\ErrorLog\ErrorLogService;
use Illuminate\Support\Facades\Log;

class PaymentStatusCheckerService
{
    /**
     * PaymentStatusCheckerService constructor.
     */
    public function __construct(
        private ErrorLogService $errorLogService
    ) {}

    /**
     * Processa pagamentos pendentes e cria jobs para verificação
     *
     * @param int $limit
     * @return array
     */
    public function processPendingPayments(int $limit = 50): array
    {
        try {
            $pendingPayments = $this->getPendingPayments($limit);

            if ($pendingPayments->isEmpty()) {
                return [
                    'success' => true,
                    'message' => 'Nenhum pagamento pendente encontrado',
                    'processed' => 0,
                    'errors' => 0
                ];
            }

            $processed = 0;
            $errors = 0;

            foreach ($pendingPayments as $payment) {
                try {
                    $this->dispatchPaymentCheckJob($payment);
                    $processed++;

                    Log::info('Job de verificação de pagamento criado', [
                        'payment_id' => $payment->id,
                        'asaas_payment_id' => $payment->gateway_payment_id
                    ]);

                } catch (\Exception $e) {
                    $errors++;
                    $this->errorLogService->logError($e, [
                        'action' => 'processPendingPayments',
                        'payment_id' => $payment->id,
                        'asaas_payment_id' => $payment->gateway_payment_id
                    ]);

                    Log::error('Erro ao criar job de verificação de pagamento', [
                        'payment_id' => $payment->id,
                        'asaas_payment_id' => $payment->gateway_payment_id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            return [
                'success' => true,
                'message' => "Processamento concluído. {$processed} jobs criados, {$errors} erros.",
                'processed' => $processed,
                'errors' => $errors,
                'total_found' => $pendingPayments->count()
            ];

        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'processPendingPayments',
                'limit' => $limit
            ]);

            return [
                'success' => false,
                'message' => 'Erro ao processar pagamentos pendentes: ' . $e->getMessage(),
                'processed' => 0,
                'errors' => 1
            ];
        }
    }

    /**
     * Busca pagamentos pendentes
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getPendingPayments(int $limit): \Illuminate\Database\Eloquent\Collection
    {
        return Payment::where('status', PaymentStatusEnum::PENDING->value)
            ->whereNotNull('gateway_payment_id')
            ->where('expires_at', '>', now()) // Não processar pagamentos expirados
            ->limit($limit)
            ->get();
    }

    /**
     * Dispara job para verificar status do pagamento
     *
     * @param Payment $payment
     * @return void
     */
    private function dispatchPaymentCheckJob(Payment $payment): void
    {
        CheckPaymentStatusJob::dispatch($payment->gateway_payment_id)
            ->onQueue('payment-checks')
            ->delay(now()->addSeconds(rand(1, 30))); // Delay aleatório para evitar sobrecarga
    }

    /**
     * Obtém estatísticas dos pagamentos pendentes
     *
     * @return array
     */
    public function getPendingPaymentsStats(): array
    {
        try {
            $totalPending = Payment::where('status', PaymentStatusEnum::PENDING->value)
                ->whereNotNull('gateway_payment_id')
                ->count();

            $expiredPending = Payment::where('status', PaymentStatusEnum::PENDING->value)
                ->whereNotNull('gateway_payment_id')
                ->where('expires_at', '<=', now())
                ->count();

            $validPending = $totalPending - $expiredPending;

            return [
                'total_pending' => $totalPending,
                'expired_pending' => $expiredPending,
                'valid_pending' => $validPending,
                'last_check' => now()->toDateTimeString()
            ];

        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'getPendingPaymentsStats'
            ]);

            return [
                'total_pending' => 0,
                'expired_pending' => 0,
                'valid_pending' => 0,
                'error' => $e->getMessage()
            ];
        }
    }
}
