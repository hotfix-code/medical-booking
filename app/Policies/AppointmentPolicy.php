<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use App\Traits\HasPermissionChecks;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    use HasPermissionChecks;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'appointment.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'appointment.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'appointment.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'appointment.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return false;
    }

    public function fetch(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'appointment.view');
    }
}