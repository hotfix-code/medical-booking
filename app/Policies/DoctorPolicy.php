<?php

namespace App\Policies;

use App\Models\Doctor;
use App\Models\User;
use App\Traits\HasPermissionChecks;
use Illuminate\Auth\Access\Response;

class DoctorPolicy
{
    use HasPermissionChecks;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'doctor.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Doctor $doctor): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'doctor.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'doctor.create',
            'You do not have permission to create doctors.'
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Doctor $doctor): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'doctor.edit',
            'You do not have permission to edit doctors.'
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Doctor $doctor): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'doctor.delete',
            'You do not have permission to delete doctors.'
        );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Doctor $doctor): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Doctor $doctor): bool
    {
        return false;
    }

    public function fetch(User $user): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'doctor.view',
            'You do not have permission to fetch doctors.'
        );
    }
}
