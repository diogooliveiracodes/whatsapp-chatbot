<?php

namespace App\Services\User;

use App\Models\User;
use App\Enum\UserRoleEnum;
use App\Repositories\UserRepository;
use App\Exceptions\User\UnauthorizedUserAccessException;
use App\Exceptions\User\SelfUpdateException;
use App\Exceptions\User\SelfDeactivationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    /**
     * Get all users
     *
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->userRepository->getAll();
    }

    /**
     * Get all users for the current company
     *
     * @return Collection
     */
    public function getUsersByCompany(): Collection
    {
        return $this->userRepository->getByCompany();
    }

    /**
     * Get all deactivated users for the current company
     *
     * @return Collection
     */
    public function getDeactivatedUsersByCompany(): Collection
    {
        return $this->userRepository->getDeactivatedByCompany();
    }

    /**
     * Get available user roles for owners (owner and employee)
     *
     * @return array
     */
    public function getAvailableUserRoles(): array
    {
        return [
            UserRoleEnum::OWNER => 'Proprietário',
            UserRoleEnum::EMPLOYEE => 'Funcionário',
        ];
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
        $data['company_id'] = Auth::user()->company_id;
        $data['active'] = $data['active'] ?? true;

        return $this->userRepository->create($data);
    }

    /**
     * Update an existing user
     *
     * @param User $user
     * @param array $data
     * @return User
     * @throws UnauthorizedUserAccessException
     * @throws SelfUpdateException
     */
    public function update(User $user, array $data): User
    {
        // Ensure user belongs to the same company
        if ($user->company_id !== Auth::user()->company_id) {
            throw new UnauthorizedUserAccessException();
        }

        // Prevent user from updating themselves
        if ($user->id === Auth::id()) {
            throw new SelfUpdateException();
        }

        $data['active'] = $data['active'] ?? false;

        return $this->userRepository->update($user, $data);
    }

    /**
     * Deactivate a user
     *
     * @param User $user
     * @return void
     * @throws UnauthorizedUserAccessException
     * @throws SelfDeactivationException
     */
    public function deactivate(User $user): void
    {
        // Ensure user belongs to the same company
        if ($user->company_id !== Auth::user()->company_id) {
            throw new UnauthorizedUserAccessException();
        }

        // Prevent user from deactivating themselves
        if ($user->id === Auth::id()) {
            throw new SelfDeactivationException();
        }

        $this->userRepository->deactivate($user);
    }

    /**
     * Activate a user
     *
     * @param User $user
     * @return void
     * @throws UnauthorizedUserAccessException
     */
    public function activate(User $user): void
    {
        // Ensure user belongs to the same company
        if ($user->company_id !== Auth::user()->company_id) {
            throw new UnauthorizedUserAccessException();
        }

        $this->userRepository->activate($user);
    }

    /**
     * Find a user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Update user password
     *
     * @param User $user
     * @param string $password
     * @return User
     * @throws UnauthorizedUserAccessException
     */
    public function updatePassword(User $user, string $password): User
    {
        // Ensure user belongs to the same company
        if ($user->company_id !== Auth::user()->company_id) {
            throw new UnauthorizedUserAccessException();
        }

        $data = ['password' => Hash::make($password)];

        return $this->userRepository->update($user, $data);
    }
}
