<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Support\AppResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AppointmentAvailabilityService
{
    public function getAvailableSlotsForDoctor(string $doctorId, string $date): JsonResponse
    {
        try {
            $carbon = Carbon::parse($date);
            $weekday = $carbon->dayOfWeek;

            $schedules = Schedule::where('doctor_id', $doctorId)
                ->where('weekday', $weekday)
                ->with(['consultingRoom', 'specialty'])
                ->orderBy('start_time')
                ->get();

            if ($schedules->isEmpty())
            {
                return AppResponse::success([], 'No schedules found for this day.');
            }

            $bookedSlots = $this->getBookedSlots($doctorId, $date);
            $schedulesByRoom = $schedules->groupBy('consulting_room_id');
            $availableSlotsByRoom = [];

            foreach ($schedulesByRoom as $roomId => $roomSchedules)
            {
                $consultingRoom = $roomSchedules->first()->consultingRoom;

                $availableSlotsByRoom[$roomId] = [
                    'consulting_room' => [
                        'id' => $consultingRoom->id,
                        'name' => $consultingRoom->name,
                        'location' => $consultingRoom->location ?? null,
                    ],
                    'schedules' => [],
                    'slots' => []
                ];

                foreach ($roomSchedules as $schedule)
                {
                    $scheduleSlots = [];
                    foreach ($schedule->time_slots as $slot)
                    {
                        $slotTime = $slot['start'];
                        $datetime = Carbon::parse($date . ' ' . $slotTime);

                        if ($datetime->isPast())
                        {
                            continue;
                        }

                        $isBooked = $bookedSlots->contains(function ($booked) use ($slotTime, $roomId)
                        {
                            return $booked->appointment_time === $slotTime &&
                                $booked->consulting_room_id === $roomId;
                        });

                        if (!$isBooked)
                        {
                            $slotData = [
                                'schedule_id' => $schedule->id,
                                'specialty_id' => $schedule->specialty_id,
                                'specialty_name' => $schedule->specialty->name,
                                'start_time' => $slot['start'],
                                'end_time' => $slot['end'],
                                'datetime' => $datetime->toISOString(),
                            ];

                            $scheduleSlots[] = $slotData;
                            $availableSlotsByRoom[$roomId]['slots'][] = $slotData;
                        }
                    }

                    $availableSlotsByRoom[$roomId]['schedules'][] = [
                        'schedule_id' => $schedule->id,
                        'specialty_id' => $schedule->specialty_id,
                        'specialty_name' => $schedule->specialty->name,
                        'start_time' => $schedule->start_time,
                        'end_time' => $schedule->end_time,
                        'available_slots_count' => count($scheduleSlots),
                        'slots' => $scheduleSlots
                    ];
                }

                usort($availableSlotsByRoom[$roomId]['slots'], function ($a, $b)
                {
                    return strcmp($a['start_time'], $b['start_time']);
                });
            }

            $availableSlotsByRoom = array_filter($availableSlotsByRoom, function ($room)
            {
                return count($room['slots']) > 0;
            });

            return AppResponse::success(array_values($availableSlotsByRoom), 'Available slots retrieved successfully.');

        } catch (\Exception $e) {
            report($e);
            return AppResponse::error(
                ['error' => 'Unable to fetch available slots.'],
                'An error occurred while retrieving available slots.',
                500
            );
        }
    }

    public function getAvailableSlotsForDoctorBySpecialty(string $doctorId, string $specialtyId, string $date): JsonResponse
    {
        try {
            $carbon = Carbon::parse($date);
            $weekday = $carbon->dayOfWeek;

            $schedules = Schedule::where('doctor_id', $doctorId)
                ->where('specialty_id', $specialtyId)
                ->where('weekday', $weekday)
                ->with(['consultingRoom', 'specialty'])
                ->orderBy('start_time')
                ->get();

            if ($schedules->isEmpty())
            {
                return AppResponse::success([], 'No schedules found for this doctor, specialty and day.');
            }

            $bookedSlots = $this->getBookedSlots($doctorId, $date);
            $schedulesByRoom = $schedules->groupBy('consulting_room_id');
            $availableSlotsByRoom = [];

            foreach ($schedulesByRoom as $roomId => $roomSchedules)
            {
                $consultingRoom = $roomSchedules->first()->consultingRoom;
                $specialty = $roomSchedules->first()->specialty;

                $availableSlotsByRoom[$roomId] = [
                    'consulting_room' => [
                        'id' => $consultingRoom->id,
                        'name' => $consultingRoom->name,
                        'location' => $consultingRoom->location ?? null,
                    ],
                    'specialty' => [
                        'id' => $specialty->id,
                        'name' => $specialty->name,
                    ],
                    'schedules' => [],
                    'slots' => []
                ];

                foreach ($roomSchedules as $schedule)
                {
                    $scheduleSlots = [];
                    foreach ($schedule->time_slots as $slot)
                    {
                        $slotTime = $slot['start'];
                        $datetime = Carbon::parse($date . ' ' . $slotTime);

                        if ($datetime->isPast())
                        {
                            continue;
                        }

                        $isBooked = $bookedSlots->contains(function ($booked) use ($slotTime, $roomId)
                        {
                            return $booked->appointment_time === $slotTime &&
                                $booked->consulting_room_id === $roomId;
                        });

                        if (!$isBooked)
                        {
                            $slotData = [
                                'schedule_id' => $schedule->id,
                                'specialty_id' => $schedule->specialty_id,
                                'start_time' => $slot['start'],
                                'end_time' => $slot['end'],
                                'datetime' => $datetime->toISOString(),
                            ];

                            $scheduleSlots[] = $slotData;
                            $availableSlotsByRoom[$roomId]['slots'][] = $slotData;
                        }
                    }

                    $availableSlotsByRoom[$roomId]['schedules'][] = [
                        'schedule_id' => $schedule->id,
                        'start_time' => $schedule->start_time,
                        'end_time' => $schedule->end_time,
                        'available_slots_count' => count($scheduleSlots),
                        'slots' => $scheduleSlots
                    ];
                }

                usort($availableSlotsByRoom[$roomId]['slots'], function ($a, $b)
                {
                    return strcmp($a['start_time'], $b['start_time']);
                });
            }

            $availableSlotsByRoom = array_filter($availableSlotsByRoom, function ($room)
            {
                return count($room['slots']) > 0;
            });

            return AppResponse::success(array_values($availableSlotsByRoom), 'Available slots for specialty retrieved successfully.');

        } catch (\Exception $e) {
            report($e);
            return AppResponse::error(
                ['error' => 'Unable to fetch available slots.'],
                'An error occurred while retrieving available slots for specialty.',
                500
            );
        }
    }

    public function getDoctorCompleteAvailability(string $doctorId, string $date): JsonResponse
    {
        try {
            $carbon = Carbon::parse($date);
            if ($date <= Carbon::now()->toDateString())
            {
                return AppResponse::error(
                    ['error' => 'The date must be in the future.'],
                    'The date must be in the future.'
                );
            }

            $weekday = $carbon->dayOfWeek;
            $doctor = Doctor::with(['user', 'documentType', 'specialties', 'schedules', 'schedules.consultingRoom', 'schedules.specialty'])
                ->findOrFail($doctorId);

            $bookedSlots = $this->getBookedSlots($doctorId, $date);
            $doctorData = $doctor->toArray();

            $doctorData['schedules'] = collect($doctorData['schedules'])
                ->filter(function ($schedule) use ($weekday) {
                    return $schedule['weekday'] === $weekday;
                })
                ->map(function ($schedule) use ($bookedSlots, $date)
                {
                    $schedule['time_slots'] = collect($schedule['time_slots'])
                        ->map(function ($slot) use ($bookedSlots, $schedule, $date)
                        {
                            $slotTime = $slot['start'];
                            $datetime = Carbon::parse($date . ' ' . $slotTime);

                            $isBooked = $bookedSlots->contains(function ($booked) use ($slotTime, $schedule)
                            {
                                return $booked->appointment_time === $slotTime &&
                                    $booked->consulting_room_id === $schedule['consulting_room_id'];
                            });

                            $slot['is_available'] = !$isBooked && !$datetime->isPast();
                            $slot['datetime'] = $datetime->toISOString();

                            return $slot;
                        })
                        ->toArray();

                    $availableCount = collect($schedule['time_slots'])
                        ->where('is_available', true)
                        ->count();

                    $schedule['available_slots_count'] = $availableCount;
                    $schedule['total_slots_count'] = count($schedule['time_slots']);

                    return $schedule;
                })
                ->values()
                ->toArray();

            $totalAvailable = collect($doctorData['schedules'])->sum('available_slots_count');
            $totalSlots = collect($doctorData['schedules'])->sum('total_slots_count');

            $doctorData['availability_summary'] = [
                'date' => $date,
                'weekday' => $weekday,
                'total_available_slots' => $totalAvailable,
                'total_slots' => $totalSlots,
                'schedules_count' => count($doctorData['schedules']),
                'consulting_rooms_count' => collect($doctorData['schedules'])->pluck('consulting_room_id')->unique()->count(),
                'specialties_count' => collect($doctorData['schedules'])->pluck('specialty_id')->unique()->count(),
            ];

            return AppResponse::success($doctorData, 'Doctor complete availability retrieved successfully.');

        } catch (\Exception $e) {
            report($e);
            return AppResponse::error(
                ['error' => 'Unable to fetch doctor availability.'],
                'An error occurred while retrieving doctor availability.',
                500
            );
        }
    }

    private function getBookedSlots(string $doctorId, string $date): Collection
    {
        return Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->isActive()
            ->get(['appointment_time', 'consulting_room_id']);
    }

    public function isSlotAvailable(array $appointmentData): bool
    {
        return !Appointment::where('doctor_id', $appointmentData['doctor_id'])
            ->where('appointment_date', $appointmentData['appointment_date'])
            ->where('appointment_time', $appointmentData['appointment_time'])
            ->where('consulting_room_id', $appointmentData['consulting_room_id'])
            ->isActive()
            ->exists();
    }
}
