<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\Weekday;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\ConsultingRoom;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Specialty;
use App\Services\ScheduleService;
use App\Traits\RespondsToAuthorization;
use Illuminate\Http\JsonResponse;

class ScheduleController extends Controller
{
    use RespondsToAuthorization;

    public function index()
    {
        $this->authorizeView('viewAny', Schedule::class);

        $user = auth()->user();
        $consultingRooms = $user->role == Role::Doctor->value
            ? ConsultingRoom::getForDoctor($user->doctor->id)
            : ConsultingRoom::all();

        return view('pages.schedules.index', [
            'consultingRooms' => $consultingRooms,
            'specialties' => Specialty::whereHas('doctors')->get(),
            'doctors' => Doctor::all(),
            'weekdays' => Weekday::cases(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScheduleRequest $request, ScheduleService $service): JsonResponse
    {
        $response = $this->authorizeJson('create', Schedule::class);
        return $response ?? $service->create($request->validated());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule, ScheduleService $service): JsonResponse
    {
        $response = $this->authorizeJson('update', $schedule);
        return $response ?? $service->update($schedule, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule, ScheduleService $service): JsonResponse
    {
        $response = $this->authorizeJson('delete', $schedule);
        return $response ?? $service->delete($schedule);
    }

    public function fetch(Schedule $schedule, ScheduleService $service): JsonResponse
    {
        $response = $this->authorizeJson('fetch', $schedule);
        return $response ?? $service->fetch($schedule);
    }

    public function fetchAllByConsultingRoom(ConsultingRoom $consultingRoom, ScheduleService $service): JsonResponse
    {
        $response = $this->authorizeJson('fetch', Schedule::class);
        return $response ?? $service->fetchAllByConsultingRoom($consultingRoom);
    }
}
