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
}
