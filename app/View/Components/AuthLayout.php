<?php

namespace App\View\Components;

use App\Services\SettingService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AuthLayout extends Component
{
    public function __construct(private readonly SettingService $settings) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('layouts.auth', [
            'accentRgb' => $this->settings->accentRgb(),
        ]);
    }
}
