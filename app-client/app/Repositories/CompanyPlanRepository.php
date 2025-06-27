<?php

namespace App\Repositories;

use App\Models\CompanyPlan;
use Illuminate\Database\Eloquent\Collection;

class CompanyPlanRepository
{
    public function __construct(
        protected CompanyPlan $model
    ) {}

    /**
     * Deactivate company plans by company ID
     *
     * @param int $companyId
     * @return void
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        $this->model->where('company_id', $companyId)->delete();
    }
}
