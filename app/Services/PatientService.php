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
            return AppResponse::success($patient, 'Patient created successfully.');
        } catch (\Throwable $th) {
            report($th);
            return AppResponse::error(
                ['patient' => 'Unable to create patient at this time. Please try again later.'],
                'An unexpected error occurred while creating the patient.',
                500
            );
        }
    }

    public function update(Patient $patient, array $data): JsonResponse
    {
        try {
            $userFields = ['firstname', 'lastname', 'email', 'password'];
            $patient = $this->updateWithUser($patient, $data, $userFields, ['user', 'documentType']);
            return AppResponse::success($patient, 'Patient updated successfully.');
        } catch (\Throwable $th) {
            report($th);
            return AppResponse::error(
                ['patient' => 'Unable to update patient at this time.'],
                'An unexpected error occurred while updating the patient.',
                500
            );
        }
    }

    public function delete(Patient $patient): JsonResponse
    {
//        if ($patient->appointments()->exists())
//        {
//            return AppResponse::error([
//                'appointments' => 'Cannot delete patient because it has appointments associated with it.'
//            ], status: 422);
//        }

        try {
            $this->deleteWithUser($patient);
            return AppResponse::success($patient, 'Patient deleted successfully.');
        } catch (\Throwable $th) {
            report($th);
            return AppResponse::error(
                ['patient' => 'Unable to delete patient at this time.'],
                'An unexpected error occurred while deleting the patient.',
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
