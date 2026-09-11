<?php

namespace App\Services;

use App\Support\AppResponse;
use App\Traits\UpdatesOwnUserAndSelf;
use Illuminate\Http\JsonResponse;

class ProfileService
{
    use UpdatesOwnUserAndSelf;

    public function update(array $data): JsonResponse
    {
        $user = auth()->user();

        $model = match ($user->role)
        {
            'patient' => $user->patient,
            'doctor' => $user->doctor,
            default => $user,
        };

        try {
            if (collect(['patient', 'doctor'])->contains($user->role))
            {
                $user = $this->updateWithUser(
                    $model,
                    $data,
                    ['firstname', 'lastname', 'email', 'password'],
                    ['user']
                );
            }
            else
            {
                $user->update($data);
                $user->refresh();
            }

            return AppResponse::success($user, __('profile.flash.updated'));

        } catch (\Throwable $th) {
            return AppResponse::error(
                ['profile' => __('profile.errors.unable_update')],
                __('profile.errors.unexpected_update'),
                500
            );
        }
    }
}
