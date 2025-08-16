<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Services\ErrorLog\ErrorLogService;

/**
 * Repository class for managing Payment model operations
 */
class PaymentRepository
{
    /**
     * Create a new PaymentRepository instance
     *
     * @param Payment $model The Payment model instance
     * @param ErrorLogService $errorLogService Service for logging errors
     */
    public function __construct(
        protected Payment $model,
        protected ErrorLogService $errorLogService
    ) {}

    /**
     * Deactivate payments associated with a specific company
     *
     * This method updates the active status of all payment records for the given company ID
     * to false. If an error occurs during the update process, it will be logged using
     * the error log service.
     *
     * @param int $companyId The ID of the company whose payments should be deactivated
     * @return void
     * @throws \Exception When database operation fails (caught and logged internally)
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        try {
            $this->model->where('company_id', $companyId)->update(['active' => false]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'deactivate_payment',
                'company_id' => $companyId,
            ]);
        }
    }

    /**
     * Create a new payment.
     *
     * @param array $data
     * @return Payment
     */
    public function createPayment(array $data): Payment
    {
        return $this->model->create($data);
    }

    /**
     * Find payment by Asaas payment ID
     *
     * @param string $asaasPaymentId
     * @return Payment|null
     */
    public function findByAsaasPaymentId(string $asaasPaymentId): ?Payment
    {
        return $this->model->where('gateway_payment_id', $asaasPaymentId)->first();
    }

    /**
     * Update payment status
     *
     * @param int $paymentId
     * @param int $status
     * @param string|null $paidAt
     * @return bool
     */
    public function updatePaymentStatus(int $paymentId, int $status, ?string $paidAt = null): bool
    {
        try {
            $updateData = ['status' => $status];

            if ($paidAt) {
                $updateData['paid_at'] = $paidAt;
            }

            return $this->model->where('id', $paymentId)->update($updateData) > 0;
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'update_payment_status',
                'payment_id' => $paymentId,
                'status' => $status,
            ]);
            return false;
        }
    }
}
