<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
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
        $doctor = $this->route('doctor');

        return [
            'firstname' => 'required|string|min:2|max:50',
            'lastname' => 'required|string|min:2|max:50',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($doctor->user_id),
            ],
            'document_type_id' => 'required|uuid|exists:document_types,id',
            'document_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('doctors')->ignore($doctor),
            ],
            'license_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('doctors')->ignore($doctor),
            ],
            'phone' => 'nullable|string|max:20',
            'specialties' => 'required|array',
            'specialties.*' => 'uuid|exists:specialties,id',
            'password' => 'nullable|string|min:6|confirmed',
        ];
    }
}
