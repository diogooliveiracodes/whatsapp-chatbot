<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function __construct(
        protected User $model
    ) {}

    /**
     * Get all users
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->with(['company', 'user_role', 'unit'])->get();
    }

    /**
     * Get all users for the current company
     *
     * @return Collection
     */
    public function getByCompany(): Collection
    {
        return $this->model->with(['company', 'user_role', 'unit'])
            ->where('company_id', Auth::user()->company_id)
            ->where('active', true)
            ->where('id', '!=', Auth::id()) // Exclude current user
            ->get();
    }

    /**
     * Get all deactivated users for the current company
     *
     * @return Collection
     */
    public function getDeactivatedByCompany(): Collection
    {
        return $this->model->with(['company', 'user_role', 'unit'])
            ->where('company_id', Auth::user()->company_id)
            ->where('active', false)
            ->where('id', '!=', Auth::id()) // Exclude current user
            ->get();
    }

    /**
     * Find a user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return $this->model->with(['company', 'user_role', 'unit'])->find($id);
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing user
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    /**
     * Deactivate a user
     *
     * @param User $user
     * @return void
     */
    public function deactivate(User $user): void
    {
        $user->update(['active' => false]);
    }

    /**
     * Activate a user
     *
     * @param User $user
     * @return void
     */
    public function activate(User $user): void
    {
        $user->update(['active' => true]);
    }

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
