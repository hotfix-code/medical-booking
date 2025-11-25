<?php

namespace App\Http\Controllers;

use App\DataTables\ConsultingRoomsDataTable;
use App\Http\Requests\StoreConsultingRoomRequest;
use App\Http\Requests\UpdateConsultingRoomRequest;
use App\Models\ConsultingRoom;
use App\Services\ConsultingRoomService;
use App\Traits\RespondsToAuthorization;
use Illuminate\Http\JsonResponse;

class ConsultingRoomController extends Controller
{
    use RespondsToAuthorization;

    /**
     * Display a listing of the resource.
     */
    public function index(ConsultingRoomsDataTable $dataTable)
    {
        $this->authorizeView('viewAny', ConsultingRoom::class);
        return $dataTable->render('pages.consulting-rooms.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConsultingRoomRequest $request, ConsultingRoomService $service): JsonResponse
    {
        $response = $this->authorizeJson('create', ConsultingRoom::class);
        return $response ?? $service->create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(ConsultingRoom $consultingRoom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConsultingRoom $consultingRoom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConsultingRoomRequest $request, ConsultingRoom $consultingRoom, ConsultingRoomService $service): JsonResponse
    {
        $response = $this->authorizeJson('update', $consultingRoom);
        return $response ?? $service->update($consultingRoom, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConsultingRoom $consultingRoom, ConsultingRoomService $service): JsonResponse
    {
        $response = $this->authorizeJson('delete', $consultingRoom);
        return $response ?? $service->delete($consultingRoom);
    }

    public function fetch(ConsultingRoom $consultingRoom, ConsultingRoomService $service): JsonResponse
    {
        $response = $this->authorizeJson('fetch', $consultingRoom);
        return $response ?? $service->fetch($consultingRoom);
    }
}
