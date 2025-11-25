<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $id = $this->route('user');;
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
            'role_id.not_in' => 'You do not have permission to assign the super-admin role.',
        ];
    }
}
