<?php

namespace App\Repositories;

use App\Models\CompanyPlan;
use App\Services\ErrorLog\ErrorLogService;

/**
 * Repository class for managing CompanyPlan model operations
 */
class CompanyPlanRepository
{
    /**
     * Create a new CompanyPlanRepository instance
     *
     * @param CompanyPlan $model The CompanyPlan model instance
     * @param ErrorLogService $errorLogService Service for logging errors
     */
    public function __construct(
        protected CompanyPlan $model,
        protected ErrorLogService $errorLogService
    ) {}

    /**
     * Deactivate all company plans associated with a specific company
     *
     * This method permanently removes all company plan records for the given company ID.
     * If an error occurs during the deletion process, it will be logged using the error log service.
     *
     * @param int $companyId The ID of the company whose plans should be deactivated
     * @return void
     * @throws \Exception When database operation fails (caught and logged internally)
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        try {
            $this->model->where('company_id', $companyId)->delete();
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'deactivate_company_plan',
                'company_id' => $companyId,
            ]);
        }
    }
}
