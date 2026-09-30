<?php

namespace App\View\Components;

use App\Services\SettingService;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public function __construct(private readonly SettingService $settings) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app', [
            'appearance' => $this->settings->appearance(),
        ]);
    }
}
