<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
            'firstname' => 'required|string|min:2|max:50',
            'lastname' => 'required|string|min:2|max:50',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'document_type_id' => 'required|uuid|exists:document_types,id',
            'document_number' => 'required|string|max:20|unique:patients,document_number',
            'gender' => 'nullable|in:male,female,other',
            'phone' => 'nullable|string|max:20',
            'birthdate' => [
                'nullable',
                'date',
                'before:today',
                'after_or_equal:' . now()->subYears(120)->toDateString(),
            ]
        ];
    }
}
