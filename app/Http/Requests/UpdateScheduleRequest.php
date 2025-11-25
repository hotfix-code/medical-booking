<?php

namespace App\Http\Requests;

use App\Enums\Weekday;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateScheduleRequest extends FormRequest
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
            'doctor_id' => 'required|uuid|exists:doctors,id',
            'specialty_id' => 'required|uuid|exists:specialties,id',
            'consulting_room_id' => 'required|uuid|exists:consulting_rooms,id',
            'weekday' => ['required', new Enum(Weekday::class)],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ];
    }
}
