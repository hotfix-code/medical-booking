<?php

namespace App\Services;

use App\Models\User;
use App\Support\AppResponse;
use Illuminate\Http\JsonResponse;

class UserService
{
    public function create(array $data): JsonResponse
    {
        $user = User::create($data);
        $user->assignRole($data['role_id']);
        $user->load('roles');
        return AppResponse::success($user, 'User created successfully.');
    }

    public function update(User $user, array $data): JsonResponse
    {
        $user->update($data);
        $user->syncRoles($data['role_id']);
        $user->load('roles');
        return AppResponse::success($user, 'User updated successfully.');
    }

    public function delete(User $user): JsonResponse
    {
        if ($user->doctor()->exists())
        {
            return AppResponse::error([
                'doctor' => 'Cannot delete user because it has a doctor profile associated with it.'
            ], status: 422);
        }

        if ($user->patient()->exists())
        {
            return AppResponse::error([
                'patient' => 'Cannot delete user because it has a patient profile associated with it.'
            ], status: 422);
        }

        $user->delete();
        return AppResponse::success($user, 'User deleted successfully.');
    }

    public function fetch(User $user): JsonResponse
    {
        $user->load('roles');
        return AppResponse::success($user);
    }
}
