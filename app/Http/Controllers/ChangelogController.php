<?php

namespace App\Http\Controllers;

use App\Services\ChangelogService;

class ChangelogController extends Controller
{
    public function index(ChangelogService $changelogService)
    {
        return view('pages.changelogs.index', [
            'releases' => $changelogService->getReleases(),
        ]);
    }
}
