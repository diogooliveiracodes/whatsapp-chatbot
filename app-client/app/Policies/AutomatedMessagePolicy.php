<?php

namespace App\Policies;

use App\Models\AutomatedMessage;
use App\Models\User;
use App\Enum\UserRoleEnum;

class AutomatedMessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AutomatedMessage $automatedMessage): bool
    {
        // Admin pode ver todas as mensagens
        if ($user->isAdmin()) {
            return true;
        }

        // Owner pode ver mensagens da sua empresa
        if ($user->isOwner()) {
            return $automatedMessage->company_id === $user->company_id;
        }

        // Outros usuários só podem ver mensagens da sua unidade
        return $automatedMessage->unit_id === $user->unit_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AutomatedMessage $automatedMessage): bool
    {
        // Admin pode atualizar todas as mensagens
        if ($user->isAdmin()) {
            return true;
        }

        // Owner pode atualizar mensagens da sua empresa
        if ($user->isOwner()) {
            return $automatedMessage->company_id === $user->company_id;
        }

        // Outros usuários só podem atualizar mensagens da sua unidade
        return $automatedMessage->unit_id === $user->unit_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AutomatedMessage $automatedMessage): bool
    {
        // Admin pode deletar todas as mensagens
        if ($user->isAdmin()) {
            return true;
        }

        // Owner pode deletar mensagens da sua empresa
        if ($user->isOwner()) {
            return $automatedMessage->company_id === $user->company_id;
        }

        // Outros usuários só podem deletar mensagens da sua unidade
        return $automatedMessage->unit_id === $user->unit_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AutomatedMessage $automatedMessage): bool
    {
        // Admin pode restaurar todas as mensagens
        if ($user->isAdmin()) {
            return true;
        }

        // Owner pode restaurar mensagens da sua empresa
        if ($user->isOwner()) {
            return $automatedMessage->company_id === $user->company_id;
        }

        // Outros usuários só podem restaurar mensagens da sua unidade
        return $automatedMessage->unit_id === $user->unit_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AutomatedMessage $automatedMessage): bool
    {
        // Admin pode deletar permanentemente todas as mensagens
        if ($user->isAdmin()) {
            return true;
        }

        // Owner pode deletar permanentemente mensagens da sua empresa
        if ($user->isOwner()) {
            return $automatedMessage->company_id === $user->company_id;
        }

        // Outros usuários só podem deletar permanentemente mensagens da sua unidade
        return $automatedMessage->unit_id === $user->unit_id;
    }
}
