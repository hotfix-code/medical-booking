<?php

namespace App\Support;

class AppearancePalette
{
    public static function sidebarColors(): array
    {
        return [
            '#3585fc',
            '#273249',
            '#8447b0',
            '#d04b50',
            '#ce4878',
            '#e97f35',
            '#e6ad28',
            '#3da065',
            '#1f7d7f',
            '#717d89',
        ];
    }

    public static function accentColors(): array
    {
        return [
            '#3886fd',
            '#6c4db5',
            '#d14b7b',
            '#ce4b51',
            '#e87f37',
            '#e8b028',
            '#3faf6b',
            '#159e95',
            '#727e8a',
        ];
    }

    public static function sidebarColor(mixed $value): string
    {
        return self::match($value, self::sidebarColors());
    }

    public static function accentColor(mixed $value): string
    {
        return self::match($value, self::accentColors());
    }

    private static function match(mixed $value, array $palette): string
    {
        $value = strtolower(trim((string) $value));

        return in_array($value, $palette, true) ? $value : '';
    }

    public static function ink(string $hex): string
    {
        $luminance = self::luminance($hex);
        $dark = '#111827';
        $light = '#f8fafc';

        return self::contrast(self::luminance($dark), $luminance) >= self::contrast(self::luminance($light), $luminance)
            ? $dark
            : $light;
    }

    public static function rgb(string $hex): string
    {
        $hex = ltrim($hex, '#');

        return implode(', ', [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ]);
    }

    private static function luminance(string $hex): float
    {
        $hex = ltrim($hex, '#');
        $red = hexdec(substr($hex, 0, 2)) / 255;
        $green = hexdec(substr($hex, 2, 2)) / 255;
        $blue = hexdec(substr($hex, 4, 2)) / 255;

        return (0.2126 * self::linear($red))
            + (0.7152 * self::linear($green))
            + (0.0722 * self::linear($blue));
    }

    private static function linear(float $channel): float
    {
        return $channel <= 0.04045
            ? $channel / 12.92
            : (($channel + 0.055) / 1.055) ** 2.4;
    }

    private static function contrast(float $first, float $second): float
    {
        $lighter = max($first, $second);
        $darker = min($first, $second);

        return ($lighter + 0.05) / ($darker + 0.05);
    }
}
