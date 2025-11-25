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

            return AppResponse::success($user, 'Profile updated successfully.');

        } catch (\Throwable $th) {
            return AppResponse::error(
                ['profile' => 'Unable to update profile at this time. Please try again later.'],
                'An unexpected error occurred while updating the profile.',
                500
            );
        }
    }
}
