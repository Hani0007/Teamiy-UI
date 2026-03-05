<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SetLocaleFromHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['en', 'it', 'fr', 'de'];

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ Respect LaravelLocalization URL locale
        |--------------------------------------------------------------------------
        | If URL already contains /en /fr /it /de
        | DO NOT override it.
        */
        $currentLocale = LaravelLocalization::getCurrentLocale();

        if ($currentLocale && in_array($currentLocale, $supportedLocales)) {
            App::setLocale($currentLocale);
            session(['locale' => $currentLocale]);
            return $next($request);
        }

        $locale = null;

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ IP Detection (FIRST PRIORITY)
        |--------------------------------------------------------------------------
        */

        $ip = app()->environment('local')
            ? '151.36.0.1' // Change IP here to test locally
            : $request->ip();

        try {
            $location = geoip($ip);
            $countryCode = $location->iso_code ?? null;

            $locale = match ($countryCode) {
                'IT' => 'it',
                'FR' => 'fr',
                'DE' => 'de',
                default => 'en',
            };
        } catch (\Exception $e) {
            $locale = null;
        }

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ Accept-Language Header
        |--------------------------------------------------------------------------
        */
        if (!$locale) {
            $locale = $request->getPreferredLanguage($supportedLocales);
        }

        /*
        |--------------------------------------------------------------------------
        | 4️⃣ Authenticated User Preference
        |--------------------------------------------------------------------------
        */
        if (!$locale && Auth::check()) {
            $userLang = Auth::user()->language ?? null;

            if (in_array($userLang, $supportedLocales)) {
                $locale = $userLang;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 5️⃣ Session Fallback
        |--------------------------------------------------------------------------
        */
        if (!$locale && session()->has('locale')) {
            $sessionLang = session('locale');

            if (in_array($sessionLang, $supportedLocales)) {
                $locale = $sessionLang;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 6️⃣ Final Fallback
        |--------------------------------------------------------------------------
        */
        if (!$locale || !in_array($locale, $supportedLocales)) {
            $locale = config('app.locale', 'en');
        }

        App::setLocale($locale);
        session(['locale' => $locale]);

        return $next($request);
    }
}

