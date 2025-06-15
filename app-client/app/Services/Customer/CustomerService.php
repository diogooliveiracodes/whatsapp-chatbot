<?php

namespace App\Services\Customer;

use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Unit;

class CustomerService
{
    protected $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createCustomer(array $data)
    {
        $data['user_id'] = Auth::id();
        $data['unit_id'] = Auth::user()->unit_id;
        $data['company_id'] = Auth::user()->company_id;
        $data['active'] = $data['active'] ?? false;

        return $this->repository->create($data);
    }

    public function searchCustomers(string $query)
    {
        return $this->repository->searchByQuery($query, Auth::id());
    }

    public function updateCustomer($customer, array $data)
    {
        $data['active'] = $data['active'] ?? false;
        return $this->repository->update($customer, $data);
    }

    public function deleteCustomer($customer)
    {
        return $this->repository->delete($customer);
    }

    public function getCustomersByUnit()
    {
        $unit = Auth::user()->unit;
        return $this->repository->getCustomersByUnit($unit);
    }
}
