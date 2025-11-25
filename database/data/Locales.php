<?php

namespace Database\Data;

class Locales
{
    public static function list(): array
    {
       return [
           [
               'code' => 'en',
               'name' => 'English',
               'is_active' => true,
               'image_path' => 'assets/images/flags/us_flag.jpg',
               'created_at' => now(),
               'updated_at' => now(),
           ],
           [
               'code' => 'es',
               'name' => 'Español',
               'is_active' => true,
               'image_path' => 'assets/images/flags/spain_flag.jpg',
               'created_at' => now(),
               'updated_at' => now(),
           ],
       ];
    }
}
