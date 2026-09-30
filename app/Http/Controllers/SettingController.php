<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAppearanceRequest;
use App\Models\Setting;
use App\Services\SettingService;
use App\Support\AppearancePalette;
use App\Traits\RespondsToAuthorization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    use RespondsToAuthorization;

    public function index(Request $request, SettingService $settings): View
    {
        $this->authorizeView('viewAny', Setting::class);

        $tab = $request->query('tab') === 'general' ? 'general' : 'appearance';
        $sidebarStyle = old('sidebar_style', $settings->get('appearance.sidebar_style', 'light'));

        if (! in_array($sidebarStyle, ['light', 'dark'], true)) {
            $sidebarStyle = 'light';
        }

        $sidebarColor = AppearancePalette::sidebarColor(old('sidebar_color', $settings->get('appearance.sidebar_color', '')));
        $accentColor = AppearancePalette::accentColor(old('accent_color', $settings->get('appearance.accent_color', '')));

        $sidebarInk = $sidebarColor === '' ? '' : AppearancePalette::ink($sidebarColor);
        $previewStyle = implode('; ', array_filter([
            $sidebarColor !== '' ? '--settings-preview-sidebar: '.$sidebarColor : null,
            $sidebarInk !== '' ? '--settings-preview-ink: '.$sidebarInk : null,
            $accentColor !== '' ? '--settings-preview-accent: '.$accentColor : null,
        ]));

        return view('pages.settings.index', [
            'tab' => $tab,
            'sidebarStyle' => $sidebarStyle,
            'sidebarColors' => AppearancePalette::sidebarColors(),
            'sidebarColor' => $sidebarColor,
            'accentColors' => AppearancePalette::accentColors(),
            'accentColor' => $accentColor,
            'previewStyle' => $previewStyle,
        ]);
    }

    public function updateAppearance(UpdateAppearanceRequest $request, SettingService $settings): RedirectResponse
    {
        $data = $request->validated();

        $settings->saveAppearance(
            $data['sidebar_style'],
            $data['sidebar_color'],
            $data['accent_color'],
        );

        return redirect()
            ->route('settings.index', ['tab' => 'appearance'])
            ->with('success', __('settings.flash.saved'));
    }
}
