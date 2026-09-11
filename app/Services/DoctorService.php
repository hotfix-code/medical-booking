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
            return AppResponse::success($doctor, __('doctors.flash.created'));
        } catch (\Throwable $th) {
            report($th);
            return AppResponse::error(
                ['doctor' => __('doctors.errors.unable_create')],
                __('doctors.errors.unexpected_create'),
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
            return AppResponse::success($doctor, __('doctors.flash.updated'));
        } catch (\Throwable $th) {
            report($th);
            return AppResponse::error(
                ['doctor' => __('doctors.errors.unable_update')],
                __('doctors.errors.unexpected_update'),
                500
            );
        }
    }

    public function delete(Doctor $doctor): JsonResponse
    {
        if ($doctor->appointments()->exists())
        {
            return AppResponse::error([
                'appointments' => __('doctors.errors.cannot_delete_appointments'),
            ], status: 422);
        }

        if ($doctor->schedules()->exists())
        {
            return AppResponse::error([
                'schedules' => __('doctors.errors.cannot_delete_schedules'),
            ], status: 422);
        }

        try {
            $this->deleteWithUser($doctor);
            return AppResponse::success($doctor, __('doctors.flash.deleted'));
        } catch (\Throwable $th) {
            report($th);
            return AppResponse::error(
                ['doctor' => __('doctors.errors.unable_delete')],
                __('doctors.errors.unexpected_delete'),
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
