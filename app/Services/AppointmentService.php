<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Schedule;
use App\Support\AppResponse;
use Illuminate\Http\JsonResponse;

class AppointmentService
{
    public function create(array $data): JsonResponse
    {
        if ($this->validateOneAppointmentPerDay($data['patient_id'], $data['appointment_date']))
        {
            return AppResponse::error([
                'conflict' => __('appointments.errors.patient_conflict'),
            ]);
        }

        if (!$this->validateScheduleBelongsToDoctor($data['schedule_id'], $data['doctor_id']))
        {
            return AppResponse::error([
                'schedule' => __('appointments.errors.schedule_mismatch'),
            ]);
        }

        if (!$this->validateSlotAvailability($data)) {
            return AppResponse::error([
                'slot' => __('appointments.errors.slot_unavailable'),
            ]);
        }

        $slotKeys = $this->slotKeys($data);
        $data['doctor_slot_key'] = $slotKeys->doctor;
        $data['room_slot_key'] = $slotKeys->room;

        $appointment = Appointment::create($data);
        $appointment->refresh();
        $appointment->load(['patient.user', 'doctor.user', 'consultingRoom', 'schedule.specialty']);
        return AppResponse::success($appointment, __('appointments.flash.created'));
    }

    public function update(Appointment $appointment, array $data): JsonResponse
    {
        $slotKeys = $this->slotKeys($data);
        $data['doctor_slot_key'] = $slotKeys->doctor;
        $data['room_slot_key'] = $slotKeys->room;

        $appointment->update($data);
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

    private function validateOneAppointmentPerDay(string $patientId, string $appointmentDate, ?string $excludeAppointmentId = null)
    {
        $query = Appointment::where('patient_id', $patientId)
            ->where('appointment_date', $appointmentDate)
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

    private function validateSlotAvailability(array $data, ?string $excludeAppointmentId = null): bool
    {
        $query = Appointment::where('doctor_id', $data['doctor_id'])
            ->where('appointment_date', $data['appointment_date'])
            ->where('appointment_time', $data['appointment_time'])
            ->where('consulting_room_id', $data['consulting_room_id'])
            ->isActive();

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return !$query->exists();
    }

    /**
     * Generates unique slot keys for doctor and room based on the provided appointment data.
     *
     * Depending on the appointment status, it either generates hashed keys or returns null values.
     *
     * @param array $data Contains the appointment details such as doctor ID, specialty ID, consulting room ID,
     *                    appointment date, appointment time, and status.
     *
     * @return object Returns an object with 'doctor' and 'room' properties. Both properties will contain hashed keys
     *                if the status is 'pending' or 'confirmed', and null if the status is 'completed', 'cancelled', or 'no_show'.
     */
    private function slotKeys(array $data): object
    {
        if (in_array($data['status'], ['pending', 'confirmed']))
        {
            $doctorSlotKey = sprintf(
                '%s-%s-%s-%s',
                $data['doctor_id'],
                $data['specialty_id'],
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

        if (in_array($data['status'], ['completed', 'cancelled', 'no_show']))
        {
            return (object) [
                'doctor' => null,
                'room' => null,
            ];
        }
    }
}
