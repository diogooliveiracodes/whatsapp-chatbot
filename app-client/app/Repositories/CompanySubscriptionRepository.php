<?php

namespace App\Repositories;

use App\Models\CompanySubscription;
use Illuminate\Database\Eloquent\Collection;

class CompanySubscriptionRepository
{
    public function __construct(
        protected CompanySubscription $model
    ) {}

    /**
     * Deactivate company subscriptions by company ID
     *
     * @param int $companyId
     * @return void
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        $this->model->where('company_id', $companyId)->update(['status' => 'inactive']);
    }
}
