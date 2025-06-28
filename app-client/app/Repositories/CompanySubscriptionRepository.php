<?php

namespace App\Repositories;

use App\Models\CompanySubscription;
use App\Services\ErrorLog\ErrorLogService;

/**
 * Repository class for managing CompanySubscription model operations
 */
class CompanySubscriptionRepository
{
    /**
     * Create a new CompanySubscriptionRepository instance
     *
     * @param CompanySubscription $model The CompanySubscription model instance
     * @param ErrorLogService $errorLogService Service for logging errors
     */
    public function __construct(
        protected CompanySubscription $model,
        protected ErrorLogService $errorLogService
    ) {}

    /**
     * Deactivate company subscriptions associated with a specific company
     *
     * This method updates the status of all company subscription records for the given company ID
     * to 'inactive'. If an error occurs during the update process, it will be logged using
     * the error log service.
     *
     * @param int $companyId The ID of the company whose subscriptions should be deactivated
     * @return void
     * @throws \Exception When database operation fails (caught and logged internally)
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        try {
            $this->model->where('company_id', $companyId)->update(['status' => 'inactive']);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'deactivate_company_subscription',
                'company_id' => $companyId,
            ]);
        }
    }
}
