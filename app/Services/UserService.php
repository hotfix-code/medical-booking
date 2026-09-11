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
        return AppResponse::success($user, __('users.flash.created'));
    }

    public function update(User $user, array $data): JsonResponse
    {
        $user->update($data);
        $user->syncRoles($data['role_id']);
        $user->load('roles');
        return AppResponse::success($user, __('users.flash.updated'));
    }

    public function delete(User $user): JsonResponse
    {
        if ($user->doctor()->exists())
        {
            return AppResponse::error([
                'doctor' => __('users.errors.cannot_delete_doctor'),
            ], status: 422);
        }

        if ($user->patient()->exists())
        {
            return AppResponse::error([
                'patient' => __('users.errors.cannot_delete_patient'),
            ], status: 422);
        }

        $user->delete();
        return AppResponse::success($user, __('users.flash.deleted'));
    }

    public function fetch(User $user): JsonResponse
    {
        $user->load('roles');
        return AppResponse::success($user);
    }
}
