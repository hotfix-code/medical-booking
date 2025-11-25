<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocumentTypeRequest extends FormRequest
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
        $id = $this->route('documentType');

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('document_types')->ignore($id),
            ],
            'code' => [
                'required',
                'string',
                'min:2',
                'max:10',
                Rule::unique('document_types')->ignore($id),
            ],
        ];
    }
}
