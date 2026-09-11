<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user');;
        $roleValidation = ['required', 'uuid', 'exists:roles,uuid'];

        if (!$this->user()->hasRole('super-admin'))
        {
            $superAdminRole = Role::where(['name' => 'super-admin'])->first();
            $roleValidation[] = Rule::notIn([$superAdminRole->uuid]);
        }

        return [
            'firstname' => 'required|string|min:2|max:50',
            'lastname' => 'required|string|min:2|max:50',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id),
            ],
            'locale' => 'nullable|string|exists:locales,code',
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => $roleValidation,
        ];
    }

    public function messages(): array
    {
        return [
            'role_id.not_in' => __('users.cannot_assign_super_admin'),
        ];
    }
}
