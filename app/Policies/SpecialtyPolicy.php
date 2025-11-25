<?php

namespace App\Policies;

use App\Models\Specialty;
use App\Models\User;
use App\Traits\HasPermissionChecks;
use Illuminate\Auth\Access\Response;

class SpecialtyPolicy
{
    use HasPermissionChecks;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'specialty.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Specialty $specialty): bool
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
            'specialty.create',
            'You do not have permission to create specialties.'
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Specialty $specialty): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'specialty.edit',
            'You do not have permission to edit specialties.'
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Specialty $specialty): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'specialty.delete',
            'You do not have permission to delete specialties.'
        );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Specialty $specialty): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Specialty $specialty): bool
    {
        return false;
    }

    public function fetch(User $user): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'specialty.view',
            'You do not have permission to fetch specialties.'
        );
    }
}
