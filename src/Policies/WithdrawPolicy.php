<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Policies;

use App\Models\User;
use Mortezaa97\Wallet\Models\Withdraw;
use Illuminate\Auth\Access\Response;

class WithdrawPolicy
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
    public function view(User $user, Withdraw $withdraw): bool
    {
        return $user->id === $withdraw->created_by || $user->hasRole('admin');
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
    public function update(User $user, Withdraw $withdraw): bool
    {
        return $user->id === $withdraw->created_by || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Withdraw $withdraw): bool
    {
        return $user->id === $withdraw->created_by || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Withdraw $withdraw): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Withdraw $withdraw): bool
    {
        return $user->hasRole('admin');
    }
}

