<?php

namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository
{
    public function __construct(
        protected Payment $model
    ) {}

    /**
     * Deactivate payments by company ID
     *
     * @param int $companyId
     * @return void
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        $this->model->where('company_id', $companyId)->update(['active' => false]);
    }
}
