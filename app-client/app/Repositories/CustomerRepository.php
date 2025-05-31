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
        return $this->model->find($id);
    }

    public function update(Customer $customer, array $data): bool
    {
        return $customer->update($data);
    }

    public function delete(Customer $customer): bool
    {
        return $customer->delete();
    }

    public function findByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)
            ->select('id', 'name', 'phone', 'active', 'prospect_origin', 'created_at')
            ->get();
    }

    public function searchByQuery(string $query, int $userId)
    {
        return $this->model->where('user_id', $userId)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })
            ->select('id', 'name', 'phone')
            ->limit(10)
            ->get();
    }
}
