<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends AppFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('role')) {
            $this->merge(['role' => Str::lower($this->role)]);
        }
    }

    public function rules(): array
    {
        return [
            'role' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'unique:roles,name',
                Rule::notIn(Role::values()),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'role.not_in' => __('roles.errors.cannot_use_locked_name'),
        ];
    }
}
