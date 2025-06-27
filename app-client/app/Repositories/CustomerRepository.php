<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerRepository
{
    protected $model;

    public function __construct(Customer $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Customer
    {
        return $this->model->create($data);
    }

    public function findById(int $id): ?Customer
    {
        return $this
            ->model
            ->where('company_id', Auth::user()->company_id)
            ->where('id', $id)
            ->first();
    }

    public function update(Customer $customer, array $data): bool
    {
        return $customer->update($data);
    }

    public function delete(Customer $customer): bool
    {
        return $customer->delete();
    }

    public function searchByQuery(string $query, $unit)
    {
        return $this
            ->model
            ->where('unit_id', $unit->id)
            ->where(function ($q) use ($query) {
                $q
                    ->where('name', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%");
            })
            ->select('id', 'name', 'phone', 'active', 'created_at')
            ->get();
    }

    public function getCustomersByUnit($unit)
    {
        return $this
            ->model
            ->where('unit_id', $unit->id)
            ->select('id', 'name', 'phone', 'active')
            ->get();
    }

    /**
     * Deactivate customers by company ID
     *
     * @param int $companyId
     * @return void
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        $this->model->where('company_id', $companyId)->update(['active' => false]);
    }
}
