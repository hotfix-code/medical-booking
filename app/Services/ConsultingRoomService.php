<?php

namespace App\Services;

use App\Models\ConsultingRoom;
use App\Support\AppResponse;
use Illuminate\Http\JsonResponse;

class ConsultingRoomService
{
    public function create(array $data): JsonResponse
    {
        $consultingRoom = ConsultingRoom::create($data);
        return AppResponse::success($consultingRoom, 'Consulting Room created successfully.');
    }

    public function update(ConsultingRoom $consultingRoom, array $data): JsonResponse
    {
        $consultingRoom->update($data);
        return AppResponse::success($consultingRoom, 'Consulting Room updated successfully.');
    }

    public function delete(ConsultingRoom $consultingRoom): JsonResponse
    {
        // Add any relationship checks here if needed in the future
        // if ($consultingRoom->appointments()->exists())
        // {
        //     return AppResponse::error([
        //         'appointments' => 'Cannot delete consulting room because it has appointments associated with it.'
        //     ], status: 422);
        // }

        $consultingRoom->delete();
        return AppResponse::success($consultingRoom, 'Consulting Room deleted successfully.');
    }

    public function fetch(ConsultingRoom $consultingRoom): JsonResponse
    {
        return AppResponse::success($consultingRoom);
    }
}
