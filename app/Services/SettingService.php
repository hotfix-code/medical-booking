<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SettingService
{
    public function get(string $key, mixed $default = null): mixed
    {
        return DB::table('settings')->where('key', $key)->value('value') ?? $default;
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
