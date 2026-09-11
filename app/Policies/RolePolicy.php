<?php

namespace App\Policies;

use App\Enums\Role as RoleEnum;
use App\Models\Role;
use App\Models\User;
use App\Traits\HasPermissionChecks;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    use HasPermissionChecks;
    public function viewAny(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'role.view');
    }

    public function fetch(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'role.view');
    }

    public function rolePermissionsSelected(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, ['role.view', 'permission.view']);
    }

    public function rolePermissionsUpdate(User $user, Role $role): Response
    {
        $hasPermission = $this->allowIfHasRoleOrCan($user, ['role.edit', 'permission.edit']);

        if (!$hasPermission->allowed())
        {
            return $hasPermission;
        }

        if ($role->name === 'super-admin')
        {
            return Response::deny(__('roles.errors.cannot_edit_super_admin_permissions'));
        }

        return Response::allow();
    }

    public function create(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'role.create');
    }

    public function update(User $user, Role $role): Response
    {
        $hasPermission = $this->allowIfHasRoleOrCan($user, 'role.edit');

        if (!$hasPermission->allowed())
        {
            return $hasPermission;
        }

        if (RoleEnum::isLocked($role->name))
        {
            return Response::deny(__('roles.errors.cannot_edit_locked'));
        }

        return Response::allow();
    }

    public function delete(User $user, Role $role): Response
    {
        $hasPermission = $this->allowIfHasRoleOrCan($user, 'role.delete');

        if (!$hasPermission->allowed())
        {
            return $hasPermission;
        }

        if (RoleEnum::isLocked($role->name))
        {
            return Response::deny(__('roles.errors.cannot_delete_locked'));
        }

        return Response::allow();
    }
}
