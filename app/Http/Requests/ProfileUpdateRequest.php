<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $userRole = $this->user()->role;
        $roleRules = $this->getRoleValidationRules();
        return $roleRules[$userRole] ?? $roleRules['admin'];
    }

    private function getRoleValidationRules(): array
    {
        return [
            'admin' => [
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'max:255',
                    Rule::unique(User::class)->ignore($this->user()->id)
                ],
            ],
            'doctor' => [
                'firstname' => 'required|string|min:2|max:50',
                'lastname' => 'required|string|min:2|max:50',
                'email' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($this->user()->id)
                ],
                'document_type_id' => 'required|uuid|exists:document_types,id',
                'document_number' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('doctors')->ignore($this->user()->doctor?->id),
                ],
                'license_number' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('doctors')->ignore($this->user()->doctor?->id),
                ],
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:6|confirmed',
            ],
            'patient' => [
                'firstname' => 'required|string|min:2|max:50',
                'lastname' => 'required|string|min:2|max:50',
                'email' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'max:255',
                    Rule::unique(User::class)->ignore($this->user()->id)
                ],
                'document_type_id' => 'required|uuid|exists:document_types,id',
                'document_number' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('patients')->ignore($this->user()->patient?->id),
                ],
                'gender' => 'nullable|in:male,female,other',
                'birthdate' => 'nullable|date|before:today',
                'phone' => 'nullable|string|max:20',
            ],
        ];
    }
}
