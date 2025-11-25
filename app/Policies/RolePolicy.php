<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use App\Traits\HasPermissionChecks;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class RolePolicy
{
    use HasPermissionChecks;
    public function viewAny(User $user): Response
    {
        return $this->allowIfHasRoleOrCan($user, 'role.view');
    }

    public function fetch(User $user): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'role.view',
            'You do not have permission to fetch roles.'
        );
    }

    public function rolePermissionsSelected(User $user): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            ['role.view', 'permission.view'],
            'You do not have permission to fetch roles.'
        );
    }

    public function rolePermissionsUpdate(User $user, Role $role): Response
    {
        $hasPermission = $this->allowIfHasRoleOrCan(
            $user,
            ['role.edit', 'permission.edit'],
            'You do not have permission to update roles.'
        );

        if (!$hasPermission->allowed())
        {
            return $hasPermission;
        }

        if ($role->name === 'super-admin')
        {
            return Response::deny('You can not edit permissions of the super administrator.');
        }

        return Response::allow();
    }

    public function create(User $user): Response
    {
        return $this->allowIfHasRoleOrCan(
            $user,
            'role.create',
            'You do not have permission to create roles.'
        );
    }

    public function update(User $user, Role $role): Response
    {
        $hasPermission = $this->allowIfHasRoleOrCan(
            $user,
            'role.edit',
            'You do not have permission to edit roles.'
        );

        if (!$hasPermission->allowed())
        {
            return $hasPermission;
        }

        if ($role->name === 'super-admin')
        {
            return Response::deny('You can not edit the super administrator.');
        }

        return Response::allow();
    }

    public function delete(User $user, Role $role): Response
    {
        $hasPermission = $this->allowIfHasRoleOrCan(
            $user,
            'role.delete',
            'You do not have permission to delete roles.'
        );

        if (!$hasPermission->allowed())
        {
            return $hasPermission;
        }

        if ($role->name === 'super-admin')
        {
            return Response::deny('You can not delete the super administrator.');
        }

        return Response::allow();
    }
}
