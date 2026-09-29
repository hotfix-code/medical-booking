<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\HasPermissionChecks;
use Illuminate\Auth\Access\Response;

class SettingPolicy
{
    use HasPermissionChecks;

    public function viewAny(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'setting.edit');
    }
}
