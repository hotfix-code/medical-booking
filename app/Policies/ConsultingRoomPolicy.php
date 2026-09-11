<?php

namespace App\Policies;

use App\Models\ConsultingRoom;
use App\Models\User;
use App\Traits\HasPermissionChecks;
use Illuminate\Auth\Access\Response;

class ConsultingRoomPolicy
{
    use HasPermissionChecks;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'consulting_room.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ConsultingRoom $consultingRoom): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'consulting_room.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ConsultingRoom $consultingRoom): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'consulting_room.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ConsultingRoom $consultingRoom): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'consulting_room.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ConsultingRoom $consultingRoom): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ConsultingRoom $consultingRoom): bool
    {
        return false;
    }

    public function fetch(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'consulting_room.view');
    }
}
