<?php

namespace App\Repositories;

use App\Models\Company;

class CompanyRepository
{
    public function __construct(
        protected Company $model
    ) {}

    /**
     * Find a company by ID
     *
     * @param int $id
     * @return Company|null
     */
    public function findById(int $id): ?Company
    {
        return $this->model->find($id);
    }

    /**
     * Deactivate a company
     *
     * @param Company $company
     * @return void
     */
    public function deactivate(Company $company): void
    {
        $company->update(['active' => false]);
    }
}
