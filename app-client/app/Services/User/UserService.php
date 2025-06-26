<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    /**
     * Get all users
     *
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return User::with(['company', 'user_role', 'unit'])->get();
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['active'] = $data['active'] ?? true;

        return User::create($data);
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
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $data['active'] = $data['active'] ?? false;

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
     * Find a user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return User::with(['company', 'user_role', 'unit'])->find($id);
    }
}
