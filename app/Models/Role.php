<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasUuids, HasFactory;

    protected $primaryKey = 'uuid';

    protected $hidden = [
      'guard_name',
    ];

    public function createAtDateFormat(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->format('Y/m/d')
        );
    }
}
