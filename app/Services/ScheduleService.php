<?php

namespace App\Services;

use App\Models\ConsultingRoom;
use App\Models\Schedule;
use App\Support\AppResponse;
use Illuminate\Http\JsonResponse;

class ScheduleService
{
    public function create(array $data): JsonResponse
    {
        if ($this->hasConflict($data))
        {
            return AppResponse::error([
                'conflict' => __('schedules.errors.conflict'),
            ], status: 422);
        }

        $schedule = Schedule::create($data);
        $schedule->load(['doctor.user', 'consultingRoom', 'specialty']);
        $schedule->schedulesByConsultingRoom = $this->fetchAllByConsultingRoom($schedule->consultingRoom)->getData()->data;
        return AppResponse::success($schedule, __('schedules.flash.created'));
    }

    public function update(Schedule $schedule, array $data): JsonResponse
    {
        if ($this->hasConflict($data, $schedule))
        {
            return AppResponse::error([
                'conflict' => __('schedules.errors.conflict'),
            ], status: 422);
        }

        $schedule->update($data);
        $schedule->load(['doctor.user', 'consultingRoom', 'specialty']);
        $schedule->schedulesByConsultingRoom = $this->fetchAllByConsultingRoom($schedule->consultingRoom)->getData()->data;
        return AppResponse::success($schedule, __('schedules.flash.updated'));
    }

    public function delete(Schedule $schedule): JsonResponse
    {
         if ($schedule->appointments()->exists())
         {
             return AppResponse::error([
                 'appointments' => __('schedules.errors.cannot_delete_appointments'),
             ], status: 422);
         }

        $schedule->delete();
        $schedule->schedulesByConsultingRoom = $this->fetchAllByConsultingRoom($schedule->consultingRoom)->getData()->data;
        return AppResponse::success($schedule, __('schedules.flash.deleted'));
    }

    public function fetch(Schedule $schedule): JsonResponse
    {
        $schedule->load(['doctor.user', 'consultingRoom', 'specialty']);
        return AppResponse::success($schedule);
    }

    public function fetchAllByConsultingRoom(ConsultingRoom $consultingRoom): JsonResponse
    {
        $data = Schedule::query()
            ->where('consulting_room_id', $consultingRoom->id)
            ->with(['doctor.user', 'specialty'])
            ->get()
            ->map(fn($schedule) => [
                'id' => $schedule->id,
                'title' => $schedule->doctor->user->full_name . ' - ' . $schedule->specialty->name . ' - ' . $schedule->consultingRoom->name,
                'description' => $schedule->doctor->user->full_name . ' - ' . $schedule->specialty->name . ' - ' . $schedule->consultingRoom->name,
                'location' => $schedule->consultingRoom->name,
                'specialty' => $schedule->specialty->name,
                'allDay' => false,
                'color' => $schedule->doctor->user->color,
                'textColor' => $schedule->doctor->user->textColor,
                'daysOfWeek' => [$schedule->weekday],
                'startTime' => $schedule->start_time,
                'endTime' => $schedule->end_time,
                'groupIdByDoctor' => 'sch_'.$schedule->doctor->id,
                'groupIdBySpecialty' => 'spec_'.$schedule->specialty_id,
            ]);

        return AppResponse::success($data, __('schedules.flash.fetched'));
    }

    protected function hasConflict(array $data, ?Schedule $except = null): bool
    {
        return Schedule::where('weekday', $data['weekday'])
            ->when($except, fn ($q) => $q->where('id', '!=', $except->id))
            ->whereTime('start_time', '<', $data['end_time'])
            ->whereTime('end_time',   '>', $data['start_time'])
            ->where(function ($q) use ($data) {
                $q->where('doctor_id', $data['doctor_id'])
                    ->orWhere('consulting_room_id', $data['consulting_room_id']);
            })
            ->exists();
    }
}
