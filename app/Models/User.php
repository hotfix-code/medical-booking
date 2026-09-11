<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property string|null $locale
 */
class User extends Authenticatable
{
    use HasFactory, HasRoles, HasUuids, Notifiable;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'locale',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['full_name'];

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => sprintf('%s %s', $this->firstname, $this->lastname)
        );
    }

    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getRoleNames()->first()
        );
    }

    public function localeRelation(): BelongsTo
    {
        return $this->belongsTo(Locale::class, 'locale', 'code');
    }

    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }

    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class);
    }
}
