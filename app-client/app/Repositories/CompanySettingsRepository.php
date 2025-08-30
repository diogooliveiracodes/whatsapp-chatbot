<?php

namespace App\Repositories;

use App\Models\CompanySettings;

class CompanySettingsRepository
{
    public function __construct(
        protected CompanySettings $model
    ) {}

    public function create(array $data): CompanySettings
    {
        return $this->model->create($data);
    }

    public function findByCompanyId(int $companyId): ?CompanySettings
    {
        return $this->model->where('company_id', $companyId)->first();
    }

    public function updateByCompanyId(int $companyId, array $data): bool
    {
        return $this->model->where('company_id', $companyId)->update($data);
    }
}
