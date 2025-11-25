<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
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
            'document_type_id' => 'required|uuid|exists:document_types,id',
            'document_number' => 'required|string|max:20|unique:doctors,document_number',
            'license_number' => 'required|string|max:50|unique:doctors,license_number',
            'phone' => 'nullable|string|max:20',
            'specialties' => 'required|array',
            'specialties.*' => 'uuid|exists:specialties,id',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
