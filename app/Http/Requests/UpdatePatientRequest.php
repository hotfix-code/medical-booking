<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientRequest extends FormRequest
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
        $patient = $this->route('patient');

        return [
            'firstname' => 'required|string|min:2|max:50',
            'lastname' => 'required|string|min:2|max:50',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($patient->user_id),
            ],
            'document_type_id' => 'required|uuid|exists:document_types,id',
            'document_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('patients')->ignore($patient),
            ],
            'gender' => 'nullable|in:male,female,other',
            'birthdate' => [
                'nullable',
                'date',
                'before:today',
                'after_or_equal:' . now()->subYears(120)->toDateString(),
            ],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ];
    }
}
