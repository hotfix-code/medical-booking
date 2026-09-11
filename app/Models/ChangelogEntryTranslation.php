<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChangelogEntryTranslation extends Model
{
    protected $fillable = [
        'changelog_entry_id',
        'locale',
        'description',
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(ChangelogEntry::class, 'changelog_entry_id');
    }
}
