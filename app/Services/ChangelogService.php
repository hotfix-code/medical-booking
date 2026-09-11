<?php

namespace App\Services;

use App\Models\ChangelogEntry;
use Illuminate\Support\Collection;

class ChangelogService
{
    public function getReleases(): Collection
    {
        return ChangelogEntry::query()
            ->with('translations')
            ->orderByDesc('released_at')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('version');
    }
}
