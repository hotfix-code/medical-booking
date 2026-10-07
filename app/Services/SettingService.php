<?php

namespace App\Services;

use App\Models\Setting;
use App\Support\AppearancePalette;
use Illuminate\Support\Facades\DB;

class SettingService
{
    public function get(string $key, mixed $default = null): mixed
    {
        return DB::table('settings')->where('key', $key)->value('value') ?? $default;
    }

    public function saveAppearance(string $sidebarStyle, string $sidebarColor, string $accentColor): void
    {
        DB::transaction(function () use ($sidebarStyle, $sidebarColor, $accentColor) {
            $this->put('appearance.sidebar_style', $sidebarStyle);
            $this->put('appearance.sidebar_color', $sidebarColor);
            $this->put('appearance.accent_color', $accentColor);
        });
    }

    public function appearance(): array
    {
        $stored = DB::table('settings')
            ->whereIn('key', [
                'appearance.sidebar_style',
                'appearance.sidebar_color',
                'appearance.accent_color',
            ])
            ->pluck('value', 'key');

        $sidebarStyle = $stored['appearance.sidebar_style'] ?? 'light';

        if (! in_array($sidebarStyle, ['light', 'dark'], true)) {
            $sidebarStyle = 'light';
        }

        $sidebarColor = AppearancePalette::sidebarColor($stored['appearance.sidebar_color'] ?? '');
        $accentColor = AppearancePalette::accentColor($stored['appearance.accent_color'] ?? '');

        $sidebarInk = $sidebarColor === '' ? '' : AppearancePalette::ink($sidebarColor);
        $accentRgb = $accentColor === '' ? '' : AppearancePalette::rgb($accentColor);
        $style = implode('; ', array_filter([
            $sidebarColor !== '' ? '--menu-bg: '.$sidebarColor : null,
            $sidebarInk !== '' ? '--menu-prime-color: '.$sidebarInk : null,
            $sidebarColor !== '' ? '--menu-border-color: transparent' : null,
            $accentRgb !== '' ? '--primary-rgb: '.$accentRgb : null,
        ]));

        return [
            'sidebarStyle' => $sidebarStyle,
            'sidebarColor' => $sidebarColor,
            'sidebarInk' => $sidebarInk,
            'sidebarInkTone' => $sidebarColor === '' ? '' : ($sidebarInk === '#f8fafc' ? 'light' : 'dark'),
            'accentRgb' => $accentRgb,
            'style' => $style,
        ];
    }

    public function accentRgb(): string
    {
        $accentColor = AppearancePalette::accentColor($this->get('appearance.accent_color', ''));

        return $accentColor === '' ? '' : AppearancePalette::rgb($accentColor);
    }

    private function put(string $key, string $value): void
    {
        Setting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );
    }

    public function getBool(string $key, bool $default = false): bool
    {
        return filter_var($this->get($key, $default), FILTER_VALIDATE_BOOLEAN);
    }

    public function getInt(string $key, int $default = 0): int
    {
        return (int) $this->get($key, $default);
    }
}
