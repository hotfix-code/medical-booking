<?php

namespace App\Http\Requests;

use App\Enums\AppointmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'required|uuid|exists:patients,id',
            'doctor_id' => 'required|uuid|exists:doctors,id',
            'specialty_id' => 'required|uuid|exists:specialties,id',
            'schedule_id' => 'required|uuid|exists:schedules,id',
            'consulting_room_id' => 'required|uuid|exists:consulting_rooms,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i:s',
            'status' => ['required', Rule::enum(AppointmentStatus::class)],
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
