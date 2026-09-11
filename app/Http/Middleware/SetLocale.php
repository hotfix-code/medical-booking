<?php

namespace App\Http\Middleware;

use App\Models\Locale;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $this->apply($request);
        return $next($request);
    }

    public function apply(Request $request): void
    {
        $availableLocales = Locale::query()
            ->where('is_active', true)
            ->get();

        $defaultLocale = DB::table('settings')
            ->where('key', 'default_locale')
            ->value('value') ?? config('app.locale');

        $locale = $request->user()?->locale ?? $defaultLocale;

        if (! $availableLocales->contains('code', $locale)) {
            $locale = config('app.fallback_locale');
        }

        App::setLocale($locale);

        View::share('availableLocales', $availableLocales);
    }
}
