<?php

namespace App\Enums;

enum Role: string
{
    case SuperAdmin = 'super-admin';
    case Admin = 'admin';
    case Doctor = 'doctor';
    case Patient = 'patient';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
