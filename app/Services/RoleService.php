<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Support\AppResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class RoleService
{
    public function create(string $role): JsonResponse
    {
        $role = Role::create(['name' => Str::lower($role)]);
        return AppResponse::success($role, 'Role created successfully.');
    }

    public function update(Role $role, array $data): JsonResponse
    {
        $role->update(['name' => $data['role']]);
        return AppResponse::success($role, 'Role updated successfully.');
    }

    public function delete(Role $role): JsonResponse
    {
        if ($role->users()->exists())
        {
            return AppResponse::error([
                'users' => 'Cannot delete role because it has users associated with it.'
            ], status: 422);
        }

        $role->delete();
        return AppResponse::success($role, 'Role deleted successfully.');
    }

    public function fetch(Role $role): JsonResponse
    {
        $role->load('permissions');
        return AppResponse::success($role);
    }

    public function fetchPermissionsSelected(Role $role): JsonResponse
    {
        $role->load('permissions');
        $permissions = Permission::all()->each(function($permission) use ($role)
        {
            $permission->selected = $role->permissions->contains($permission);
            $permission->formatName = Str::of($permission->name)
                ->before('.')
                ->replace('_', ' ')
                ->plural()
                ->title();
        });
        $role->permissionsSelected = $permissions;
        return AppResponse::success($role);
    }

    public function updatePermissions(Role $role, array $permissions): JsonResponse
    {
        $role->syncPermissions($permissions);
        return AppResponse::success($role, 'Permissions updated successfully.');
    }
}
