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
        return AppResponse::success($consultingRoom, __('consulting_rooms.flash.created'));
    }

    public function update(ConsultingRoom $consultingRoom, array $data): JsonResponse
    {
        $consultingRoom->update($data);
        return AppResponse::success($consultingRoom, __('consulting_rooms.flash.updated'));
    }

    public function delete(ConsultingRoom $consultingRoom): JsonResponse
    {
        $consultingRoom->delete();
        return AppResponse::success($consultingRoom, __('consulting_rooms.flash.deleted'));
    }

    public function fetch(ConsultingRoom $consultingRoom): JsonResponse
    {
        return AppResponse::success($consultingRoom);
    }
}
