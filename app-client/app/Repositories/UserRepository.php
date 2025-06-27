<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function __construct(
        protected User $model
    ) {}

    /**
     * Deactivate users by company ID
     *
     * @param int $companyId
     * @return void
     */
    public function deactivateByCompanyId(int $companyId): void
    {
        $this->model->where('company_id', $companyId)->update(['active' => false]);
    }
}
