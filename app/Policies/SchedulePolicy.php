<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;
use App\Traits\HasPermissionChecks;
use Illuminate\Auth\Access\Response;

class SchedulePolicy
{
    use HasPermissionChecks;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'schedule.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Schedule $schedule): bool
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
            'schedule.create',
            'You do not have permission to create schedules.'
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schedule $schedule): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'schedule.edit',
            'You do not have permission to edit schedules.'
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Schedule $schedule): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'schedule.delete',
            'You do not have permission to delete schedules.'
        );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Schedule $schedule): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Schedule $schedule): bool
    {
        return false;
    }

    public function fetch(User $user): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'schedule.view',
            'You do not have permission to fetch schedules.'
        );
    }
}
