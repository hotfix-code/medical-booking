<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locale extends Model
{
    protected $primaryKey = 'code';

    protected $fillable = [
        'code',
        'name',
        'image_path',
        'is_active',
    ];
}
