<?php

namespace App\Repositories;

use App\Models\CompanySettings;

class CompanySettingsRepository
{
    public function __construct(
        protected CompanySettings $model
    ) {}

    public function deactivateByCompanyId(int $companyId): void
    {
        $this->model->where('company_id', $companyId)->update(['active' => false]);
    }
}
