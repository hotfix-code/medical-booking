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
        return AppResponse::success($role, __('roles.flash.created'));
    }

    public function update(Role $role, array $data): JsonResponse
    {
        $role->update(['name' => $data['role']]);
        return AppResponse::success($role, __('roles.flash.updated'));
    }

    public function delete(Role $role): JsonResponse
    {
        if ($role->users()->exists())
        {
            return AppResponse::error([
                'users' => __('roles.errors.cannot_delete_users'),
            ], status: 422);
        }

        $role->delete();
        return AppResponse::success($role, __('roles.flash.deleted'));
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
            $prefix = Str::before($permission->name, '.');
            $key = 'permissions.categories.'.$prefix;
            $permission->formatName = trans()->has($key)
                ? __($key)
                : Str::of($prefix)->replace('_', ' ')->plural()->title()->toString();
        });
        $role->permissionsSelected = $permissions;
        return AppResponse::success($role);
    }

    public function updatePermissions(Role $role, array $permissions): JsonResponse
    {
        $role->syncPermissions($permissions);
        return AppResponse::success($role, __('roles.flash.permissions_updated'));
    }
}
