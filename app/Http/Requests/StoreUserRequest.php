<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleValidation = ['required', 'uuid', 'exists:roles,uuid'];

        // Prevent non-super-admin users from assigning super-admin role
        if (!$this->user()->hasRole('super-admin'))
        {
            $superAdminRole = Role::where(['name' => 'super-admin'])->first();
            $roleValidation[] = Rule::notIn([$superAdminRole->uuid]);
        }

        return [
            'firstname' => 'required|string|min:2|max:50',
            'lastname' => 'required|string|min:2|max:50',
            'email' => 'required|email|unique:users,email|max:255',
            'locale' => 'nullable|string|exists:locales,code',
            'password' => 'required|string|min:6|confirmed',
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
