<?php

namespace App\Services;

use App\Models\Specialty;
use App\Support\AppResponse;
use Illuminate\Http\JsonResponse;

class SpecialtyService
{
    public function create(array $data): JsonResponse
    {
        $specialty = Specialty::create($data);
        return AppResponse::success($specialty, 'Specialty created successfully.');
    }

    public function update(Specialty $specialty, array $data): JsonResponse
    {
        $specialty->update($data);
        return AppResponse::success($specialty, 'Specialty updated successfully.');
    }

    public function delete(Specialty $specialty): JsonResponse
    {
         if ($specialty->doctors()->exists())
         {
             return AppResponse::error([
                 'doctors' => 'Cannot delete specialty because it has doctors associated with it.'
             ], status: 422);
         }

        $specialty->delete();
        return AppResponse::success($specialty, 'Specialty deleted successfully.');
    }

    public function fetch(Specialty $specialty): JsonResponse
    {
        return AppResponse::success($specialty);
    }

    public function fetchWithDoctors(Specialty $specialty): JsonResponse
    {
        $specialty->load('doctors.user');
        return AppResponse::success($specialty);
    }
}
