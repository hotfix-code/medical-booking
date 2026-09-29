<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Traits\RespondsToAuthorization;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    use RespondsToAuthorization;

    public function index(Request $request): View
    {
        $this->authorizeView('viewAny', Setting::class);

        $tab = $request->query('tab') === 'general' ? 'general' : 'appearance';

        return view('pages.settings.index', [
            'tab' => $tab,
        ]);
    }
}
