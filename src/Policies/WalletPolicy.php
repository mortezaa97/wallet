<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Policies;

use App\Models\User;
use Mortezaa97\Wallet\Models\Wallet;
use Illuminate\Auth\Access\Response;

class WalletPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Wallet $wallet): bool
    {
        return $user->id === $wallet->created_by || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Wallet $wallet): bool
    {
        return $user->id === $wallet->created_by || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Wallet $wallet): bool
    {
        return $user->id === $wallet->created_by || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Wallet $wallet): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Wallet $wallet): bool
    {
        return $user->hasRole('admin');
    }
}

