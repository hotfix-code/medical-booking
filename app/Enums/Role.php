<?php

namespace App\Enums;

enum Role: string
{
    case SuperAdmin = 'super-admin';
    case Doctor = 'doctor';
    case Patient = 'patient';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function isLocked(?string $name): bool
    {
        return in_array($name, self::values(), true);
    }

    public static function label(?string $name): string
    {
        if (!$name) {
            return __('common.states.na');
        }

        $key = 'enums.role.' . $name;

        return trans()->has($key) ? __($key) : $name;
    }
}
