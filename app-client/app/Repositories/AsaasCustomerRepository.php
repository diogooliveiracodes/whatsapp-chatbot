<?php

namespace App\Repositories;

use App\Enum\AsaasCustomerTypeEnum;
use App\Models\AsaasCustomer;

class AsaasCustomerRepository
{
    protected $model;

    public function __construct(AsaasCustomer $model)
    {
        $this->model = $model;
    }

    public function create(array $data): AsaasCustomer
    {
        return $this->model->create($data);
    }

    public function customerExists(array $data): bool
    {
        if ($data['type'] == AsaasCustomerTypeEnum::COMPANY->value) {
            return $this->model->where('company_id', $data['company_id'])->exists();
        }

        if ($data['type'] == AsaasCustomerTypeEnum::CUSTOMER->value) {
            return $this->model->where('customer_id', $data['customer_id'])->exists();
        }

        return false;
    }
}
