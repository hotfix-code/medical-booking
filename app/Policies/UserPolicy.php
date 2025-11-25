<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\HasPermissionChecks;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HasPermissionChecks;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'user.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'user.create',
            'You do not have permission to create users.'
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): Response
    {
        $hasPermission = $this->allowIfHasRoleOrCan(
            $user,
            'user.edit',
            'You do not have permission to edit users.'
        );

        if (!$hasPermission->allowed())
        {
            return $hasPermission;
        }

        if ($model->roles[0]->name === 'super-admin')
        {
            return Response::deny('You can not edit the super administrator.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): Response
    {
        $hasPermission = $this->allowIfHasRoleOrCan(
            $user,
            'user.delete',
            'You do not have permission to delete users.'
        );

        if (!$hasPermission->allowed()) {
            return $hasPermission;
        }

        if ($user->id === $model->id)
        {
            return Response::deny('You can not delete yourself.');
        }

        if ($model->hasRole('super-admin'))
        {
            return Response::deny('You can not delete the super administrator.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }

    public function fetch(User $user): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'user.view',
            'You do not have permission to fetch users.'
        );
    }
}
