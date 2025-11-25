<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Specialty;
use App\Support\AppResponse;
use App\Traits\UpdatesOwnUserAndSelf;
use Illuminate\Http\JsonResponse;

class DoctorService
{
    use UpdatesOwnUserAndSelf;

    public function create(array $data): JsonResponse
    {
        try {
            $doctor = $this->createWithUser(
                $data,
                'doctor',
                ['firstname', 'lastname', 'email', 'password'],
                ['user', 'documentType', 'specialties']
            );
            return AppResponse::success($doctor, 'Doctor created successfully.');
        } catch (\Throwable $th) {
            report($th);
            return AppResponse::error(
                ['doctor' => 'Unable to create doctor at this time. Please try again later.'],
                'An unexpected error occurred while creating the doctor.',
                500
            );
        }
    }

    public function update(Doctor $doctor, array $data): JsonResponse
    {
        try {
            $userFields = ['firstname', 'lastname', 'email', 'password'];
            $doctor = $this->updateWithUser(
                $doctor,
                $data,
                $userFields,
                ['user', 'documentType', 'specialties']
            );
            return AppResponse::success($doctor, 'Doctor updated successfully.');
        } catch (\Throwable $th) {
            report($th);
            return AppResponse::error(
                ['doctor' => 'Unable to update doctor at this time.'],
                'An unexpected error occurred while updating the doctor.',
                500
            );
        }
    }

    public function delete(Doctor $doctor): JsonResponse
    {
        if ($doctor->appointments()->exists())
        {
            return AppResponse::error([
                'appointments' => 'Cannot delete doctor because it has appointments associated with it.'
            ], status: 422);
        }

        if ($doctor->schedules()->exists())
        {
            return AppResponse::error([
                'schedules' => 'Cannot delete doctor because it has schedules associated with it.'
            ], status: 422);
        }

        try {
            $this->deleteWithUser($doctor);
            return AppResponse::success($doctor, 'Doctor deleted successfully.');
        } catch (\Throwable $th) {
            report($th);
            return AppResponse::error(
                ['doctor' => 'Unable to delete doctor at this time.'],
                'An unexpected error occurred while deleting the doctor.',
                500
            );
        }
    }

    public function fetch(Doctor $doctor): JsonResponse
    {
        $doctor->load(['user', 'documentType', 'specialties', 'schedules', 'schedules.consultingRoom']);
        return AppResponse::success($doctor);
    }


    public function fetchBySpecialty(Doctor $doctor, Specialty $specialty): JsonResponse
    {
        $doctor->load([
            'user',
            'documentType',
            'specialties' => function($query) use ($specialty) {
                $query->where('specialties.id', $specialty->id);
            },
            'schedules' => function ($query) use ($specialty) {
                $query->where('specialty_id', $specialty->id);
            },
            'schedules.consultingRoom'
        ]);
        return AppResponse::success($doctor);
    }
}
