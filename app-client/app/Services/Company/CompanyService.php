<?php

namespace App\Services\Company;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

class CompanyService
{
    /**
     * Get all companies
     *
     * @return Collection
     */
    public function getCompanies(): Collection
    {
        return Company::where('active', true)->get();
    }

    /**
     * Create a new company
     *
     * @param array $data
     * @return Company
     */
    public function create(array $data): Company
    {
        $data['active'] = $data['active'] ?? true;

        return Company::create($data);
    }

    /**
     * Update an existing company
     *
     * @param Company $company
     * @param array $data
     * @return Company
     */
    public function update(Company $company, array $data): Company
    {
        $data['active'] = $data['active'] ?? false;

        $company->update($data);
        return $company;
    }

    /**
     * Find a company by ID
     *
     * @param int $id
     * @return Company|null
     */
    public function findById(int $id): ?Company
    {
        return Company::find($id);
    }

    /**
     * Get company by name
     *
     * @param string $name
     * @return Company|null
     */
    public function findByName(string $name): ?Company
    {
        return Company::where('name', $name)->first();
    }
}
