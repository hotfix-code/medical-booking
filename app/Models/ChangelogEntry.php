<?php

namespace App\Models;

use App\Enums\ChangelogCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChangelogEntry extends Model
{
    protected $fillable = [
        'version',
        'released_at',
        'category',
        'sort_order',
    ];

    protected $casts = [
        'released_at' => 'date',
        'category' => ChangelogCategory::class,
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(ChangelogEntryTranslation::class);
    }

    public function translatedDescription(): ?string
    {
        $locales = array_unique([
            app()->getLocale(),
            config('app.fallback_locale'),
        ]);

        foreach ($locales as $locale) {
            $translation = $this->translations->firstWhere('locale', $locale);

            if ($translation) {
                return $translation->description;
            }
        }

        return null;
    }
}
