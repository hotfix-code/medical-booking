<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Services\RoleService;
use App\Traits\RespondsToAuthorization;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    use RespondsToAuthorization;

    public function index()
    {
        $this->authorizeView('viewAny', Role::class);
        return view('pages.roles.index', ['roles' => Role::all()]);
    }

    public function show()
    {

    }

    public function fetch(Role $role, RoleService $service): JsonResponse
    {
        $response = $this->authorizeJson('fetch', $role);
        return $response ?? $service->fetch($role);
    }

    public function create()
    {

    }

    public function store(StoreRoleRequest $request, RoleService $service): JsonResponse
    {
        $response = $this->authorizeJson('create', Role::class);
        return $response ?? $service->create($request->role);
    }

    public function edit()
    {

    }

    public function update(UpdateRoleRequest $request, Role $role, RoleService $service): JsonResponse
    {
        $response = $this->authorizeJson('update', $role);
        return $response ?? $service->update($role, $request->validated());
    }

    public function destroy(Role $role, RoleService $service): JsonResponse
    {
        $response = $this->authorizeJson('delete', $role);
        return $response ?? $service->delete($role);
    }

    public function rolePermissionsSelected(Role $role, RoleService $service): JsonResponse
    {
        $response = $this->authorizeJson('rolePermissionsSelected', $role);
        return $response ?? $service->fetchPermissionsSelected($role);
    }

    public function rolePermissionsUpdate(UpdatePermissionRequest $request, Role $role, RoleService $service): JsonResponse
    {
        $response = $this->authorizeJson('rolePermissionsUpdate', $role);
        return $response ?? $service->updatePermissions($role, $request->validated());
    }
}
