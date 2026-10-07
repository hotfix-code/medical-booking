<?php

namespace App\Http\Requests;

use App\Models\Setting;
use App\Support\AppearancePalette;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateAppearanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $response = Gate::inspect('update', Setting::class);

        abort_if($response->denied(), 404);

        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sidebar_style' => strtolower(trim((string) $this->input('sidebar_style'))),
            'sidebar_color' => strtolower(trim((string) $this->input('sidebar_color'))),
            'accent_color' => strtolower(trim((string) $this->input('accent_color'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'sidebar_style' => ['required', Rule::in(['light', 'dark'])],
            'sidebar_color' => ['present', Rule::in(['', ...AppearancePalette::sidebarColors()])],
            'accent_color' => ['present', Rule::in(['', ...AppearancePalette::accentColors()])],
        ];
    }

    public function attributes(): array
    {
        return [
            'sidebar_style' => __('settings.style.title'),
            'sidebar_color' => __('settings.sidebar_color.title'),
            'accent_color' => __('settings.accent_color.title'),
        ];
    }
}
