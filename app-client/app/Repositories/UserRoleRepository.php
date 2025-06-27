<?php

namespace App\Repositories;

use App\Models\UserRole;
use Illuminate\Database\Eloquent\Collection;

class UserRoleRepository
{
    public function __construct(
        protected UserRole $model
    ) {}

    /**
     * Deactivate user roles by company ID
     *
     * @param int $companyId
     * @return void
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        $this->model->where('company_id', $companyId)->delete();
    }
}
