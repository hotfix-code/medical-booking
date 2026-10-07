<?php

namespace App\Services;

use App\Models\ChangelogEntry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ChangelogService
{
    public function isInitialized(): bool
    {
        if (! Schema::hasTable('changelog_entries') || ! Schema::hasTable('changelog_entry_translations')) {
            return false;
        }

        return ChangelogEntry::query()->exists();
    }

    public function getReleases(): Collection
    {
        return ChangelogEntry::query()
            ->with('translations')
            ->orderByDesc('released_at')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('version')
            ->sortKeysUsing(fn (string $a, string $b) => version_compare($b, $a));
    }
}
