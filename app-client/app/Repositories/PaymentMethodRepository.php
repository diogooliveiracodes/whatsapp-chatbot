<?php

namespace App\Repositories;

use App\Models\PaymentMethod;
use App\Services\ErrorLog\ErrorLogService;

/**
 * Repository class for managing PaymentMethod model operations
 */
class PaymentMethodRepository
{
    /**
     * Create a new PaymentMethodRepository instance
     *
     * @param PaymentMethod $model The PaymentMethod model instance
     * @param ErrorLogService $errorLogService Service for logging errors
     */
    public function __construct(
        protected PaymentMethod $model,
        protected ErrorLogService $errorLogService
    ) {}

    /**
     * Deactivate payment methods associated with a specific company
     *
     * This method updates the active status of all payment method records for the given company ID
     * to false. If an error occurs during the update process, it will be logged using
     * the error log service.
     *
     * @param int $companyId The ID of the company whose payment methods should be deactivated
     * @return void
     * @throws \Exception When database operation fails (caught and logged internally)
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        try {
            $this->model->where('company_id', $companyId)->update(['active' => false]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'deactivate_payment_method',
                'company_id' => $companyId,
            ]);
        }
    }
}
