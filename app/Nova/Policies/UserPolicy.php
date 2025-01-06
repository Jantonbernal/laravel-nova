<?php

namespace App\Nova\Policies;

use App\Models\User;
use App\Nova\User as UserResource;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('viewAnyUser');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserResource $resource): bool
    {
        return $user->can('viewAnyUser', $resource);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('viewAnyUser');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserResource $resource): bool
    {
        return $user->can('viewAnyUser');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserResource $resource): bool
    {
        return $user->can('viewAnyUser');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserResource $resource): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserResource $resource): bool
    {
        return false;
    }
}
