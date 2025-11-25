<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConsultingRoomRequest extends FormRequest
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
        $id = $this->route('consultingRoom');

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                Rule::unique('consulting_rooms')->ignore($id),
            ],
            'location' => 'nullable|string|max:255',
        ];
    }
}
