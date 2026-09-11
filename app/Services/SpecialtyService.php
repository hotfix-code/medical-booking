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
        return AppResponse::success($specialty, __('specialties.flash.created'));
    }

    public function update(Specialty $specialty, array $data): JsonResponse
    {
        $specialty->update($data);
        return AppResponse::success($specialty, __('specialties.flash.updated'));
    }

    public function delete(Specialty $specialty): JsonResponse
    {
         if ($specialty->doctors()->exists())
         {
             return AppResponse::error([
                 'doctors' => __('specialties.errors.cannot_delete_doctors'),
             ], status: 422);
         }

        $specialty->delete();
        return AppResponse::success($specialty, __('specialties.flash.deleted'));
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
