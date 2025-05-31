<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Auth;

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
        $data['active'] = true;
        $data['prospect_origin'] = 'manual';

        return $this->repository->create($data);
    }

    public function getCustomersByUser()
    {
        return $this->repository->findByUserId(Auth::id());
    }

    public function searchCustomers(string $query)
    {
        return $this->repository->searchByQuery($query, Auth::id());
    }

    public function updateCustomer($customer, array $data)
    {
        return $this->repository->update($customer, $data);
    }

    public function deleteCustomer($customer)
    {
        return $this->repository->delete($customer);
    }
}
