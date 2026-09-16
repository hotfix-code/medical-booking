<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Schedule;
use App\Support\AppResponse;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    public function __construct(
        private SettingService $settings
    ) { }

    public function create(array $data): JsonResponse
    {
        if (!$this->validateScheduleBelongsToDoctor($data['schedule_id'], $data['doctor_id']))
        {
            return AppResponse::error([
                'schedule' => __('appointments.errors.schedule_mismatch'),
            ]);
        }

        if ($this->slotTaken($data))
        {
            return AppResponse::error([
                'slot' => __('appointments.errors.slot_unavailable'),
            ]);
        }

        $data = $this->withSlotKeys($data);

        try
        {
            $appointment = DB::transaction(function () use ($data)
            {
                Patient::whereKey($data['patient_id'])->lockForUpdate()->first();

                if ($this->validatePatientBookingPolicy($data['patient_id'], $data['appointment_date']))
                {
                    return null;
                }

                return Appointment::create($data);
            });
        }
        catch (UniqueConstraintViolationException)
        {
            return AppResponse::error([
                'slot' => __('appointments.errors.slot_unavailable'),
            ]);
        }

        if ($appointment === null)
        {
            return AppResponse::error([
                'conflict' => __('appointments.errors.patient_conflict'),
            ]);
        }

        $appointment->refresh();
        $appointment->load(['patient.user', 'doctor.user', 'consultingRoom', 'schedule.specialty']);
        return AppResponse::success($appointment, __('appointments.flash.created'));
    }

    public function update(Appointment $appointment, array $data): JsonResponse
    {
        $effective = $this->mergeAppointmentData($appointment, $data);

        if ($this->slotTaken($effective, $appointment->id))
        {
            return AppResponse::error([
                'slot' => __('appointments.errors.slot_unavailable'),
            ]);
        }

        $slotKeys = $this->slotKeys($effective);
        $data['doctor_slot_key'] = $slotKeys->doctor;
        $data['room_slot_key'] = $slotKeys->room;

        try
        {
            DB::transaction(fn () => $appointment->update($data));
        }
        catch (UniqueConstraintViolationException)
        {
            return AppResponse::error([
                'slot' => __('appointments.errors.slot_unavailable'),
            ]);
        }

        $appointment->refresh();
        $appointment->load(['patient.user', 'doctor.user', 'consultingRoom', 'schedule.specialty']);
        return AppResponse::success($appointment, __('appointments.flash.updated'));
    }

    public function delete(Appointment $appointment): JsonResponse
    {
        $appointment->delete();
        return AppResponse::success($appointment, __('appointments.flash.deleted'));
    }

    public function fetch(Appointment $appointment): JsonResponse
    {
        $appointment->load(['patient.user', 'doctor.user', 'consultingRoom', 'schedule.specialty']);
        return AppResponse::success($appointment);
    }

    private function validatePatientBookingPolicy(string $patientId, string $appointmentDate, ?string $excludeAppointmentId = null)
    {
        if ($this->settings->getBool('appointments.allow_multiple_per_day', false))
        {
            return null;
        }

        $minDaysBetween = max($this->settings->getInt('appointments.min_days_between', 1), 1);

        $date = Carbon::parse($appointmentDate);

        $query = Appointment::where('patient_id', $patientId)
            ->whereBetween('appointment_date', [
                $date->copy()->subDays($minDaysBetween - 1)->toDateString(),
                $date->copy()->addDays($minDaysBetween - 1)->toDateString(),
            ])
            ->isActive();

        if ($excludeAppointmentId)
        {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return $query->first();
    }

    private function validateScheduleBelongsToDoctor(string $scheduleId, string $doctorId): bool
    {
        return Schedule::where('id', $scheduleId)
            ->where('doctor_id', $doctorId)
            ->exists();
    }

    private function slotTaken(array $data, ?string $excludeAppointmentId = null): bool
    {
        if ($this->isCancelled($data['status']))
        {
            return false;
        }

        return $this->doctorSlotTaken($data, $excludeAppointmentId)
            || $this->roomSlotTaken($data, $excludeAppointmentId);
    }

    private function doctorSlotTaken(array $data, ?string $excludeAppointmentId = null): bool
    {
        $query = Appointment::where('doctor_id', $data['doctor_id'])
            ->where('appointment_date', $data['appointment_date'])
            ->where('appointment_time', $data['appointment_time'])
            ->whereNotNull('doctor_slot_key');

        if ($excludeAppointmentId)
        {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return $query->exists();
    }

    private function roomSlotTaken(array $data, ?string $excludeAppointmentId = null): bool
    {
        $query = Appointment::where('consulting_room_id', $data['consulting_room_id'])
            ->where('appointment_date', $data['appointment_date'])
            ->where('appointment_time', $data['appointment_time'])
            ->whereNotNull('room_slot_key');

        if ($excludeAppointmentId)
        {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return $query->exists();
    }

    private function isCancelled(AppointmentStatus|string $status): bool
    {
        $value = $status instanceof AppointmentStatus ? $status->value : $status;

        return $value === AppointmentStatus::Cancelled->value;
    }

    private function mergeAppointmentData(Appointment $appointment, array $data): array
    {
        return [
            'doctor_id' => $data['doctor_id'] ?? $appointment->doctor_id,
            'consulting_room_id' => $data['consulting_room_id'] ?? $appointment->consulting_room_id,
            'appointment_date' => $data['appointment_date'] ?? $appointment->appointment_date,
            'appointment_time' => $data['appointment_time'] ?? $appointment->appointment_time,
            'status' => $data['status'] ?? $appointment->status,
        ];
    }

    private function withSlotKeys(array $data): array
    {
        $slotKeys = $this->slotKeys($data);
        $data['doctor_slot_key'] = $slotKeys->doctor;
        $data['room_slot_key'] = $slotKeys->room;

        return $data;
    }

    /**
     * Generates unique slot keys for doctor and room based on the provided appointment data.
     *
     * The doctor key is based on doctor, date and time so a doctor cannot be double-booked
     * across specialties. The room key is based on room, date and time. Only a cancelled
     * appointment releases the slot; completed and no-show keep it because it was consumed.
     *
     * @param array $data Contains the appointment details such as doctor ID, consulting room ID,
     *                    appointment date, appointment time, and status.
     *
     * @return object Returns an object with 'doctor' and 'room' properties. Both properties contain hashed keys
     *                unless the status is 'cancelled', in which case both are null.
     */
    private function slotKeys(array $data): object
    {
        if ($this->isCancelled($data['status']))
        {
            return (object) [
                'doctor' => null,
                'room' => null,
            ];
        }

        $doctorSlotKey = sprintf(
            '%s-%s-%s',
            $data['doctor_id'],
            $data['appointment_date'],
            $data['appointment_time']
        );

        $roomSlotKey = sprintf(
            '%s-%s-%s',
            $data['consulting_room_id'],
            $data['appointment_date'],
            $data['appointment_time']
        );

        return (object) [
            'doctor' => hash('sha256', $doctorSlotKey),
            'room' => hash('sha256', $roomSlotKey),
        ];
    }
}
