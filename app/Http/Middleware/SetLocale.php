<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $availableLocales = DB::table('locales')
            ->where('is_active', true)
            ->pluck('code');

        $defaultLocale = DB::table('settings')
            ->where('key', 'default_locale')
            ->value('value') ?? config('app.locale');

        $userLocale = Auth::check()
            ? Auth::user()->locale
            : null;

        $locale = $userLocale ?? $defaultLocale;

        if (!$availableLocales->contains($locale))
        {
            $locale = config('app.fallback_locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
