<?php

namespace App\Services;

use App\Models\Patient;
use App\Support\AppResponse;
use App\Traits\UpdatesOwnUserAndSelf;
use Illuminate\Http\JsonResponse;

class PatientService
{
    use UpdatesOwnUserAndSelf;

    public function create(array $data): JsonResponse
    {
        try {
            $patient = $this->createWithUser(
                $data,
                'patient',
                ['firstname', 'lastname', 'email', 'password'],
                ['user', 'documentType']
            );
            return AppResponse::success($patient, __('patients.flash.created'));
        } catch (\Throwable $th) {
            report($th);
            return AppResponse::error(
                ['patient' => __('patients.errors.unable_create')],
                __('patients.errors.unexpected_create'),
                500
            );
        }
    }

    public function update(Patient $patient, array $data): JsonResponse
    {
        try {
            $userFields = ['firstname', 'lastname', 'email', 'password'];
            $patient = $this->updateWithUser($patient, $data, $userFields, ['user', 'documentType']);
            return AppResponse::success($patient, __('patients.flash.updated'));
        } catch (\Throwable $th) {
            report($th);
            return AppResponse::error(
                ['patient' => __('patients.errors.unable_update')],
                __('patients.errors.unexpected_update'),
                500
            );
        }
    }

    public function delete(Patient $patient): JsonResponse
    {
        try {
            $this->deleteWithUser($patient);
            return AppResponse::success($patient, __('patients.flash.deleted'));
        } catch (\Throwable $th) {
            report($th);
            return AppResponse::error(
                ['patient' => __('patients.errors.unable_delete')],
                __('patients.errors.unexpected_delete'),
                500
            );
        }
    }

    public function fetch(Patient $patient): JsonResponse
    {
        $patient->load(['user', 'documentType']);
        return AppResponse::success($patient);
    }
}
