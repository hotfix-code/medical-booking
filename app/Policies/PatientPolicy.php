<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;
use App\Traits\HasPermissionChecks;
use Illuminate\Auth\Access\Response;

class PatientPolicy
{
    use HasPermissionChecks;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'patient.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Patient $patient): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'patient.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Patient $patient): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'patient.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Patient $patient): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'patient.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Patient $patient): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Patient $patient): bool
    {
        return false;
    }

    public function fetch(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'patient.view');
    }
}
