<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class DetectLocaleFromIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected $supportedLocales = ['en', 'it', 'fr', 'de'];

    public function handle(Request $request, Closure $next)
    {
        $urlLocale = $request->segment(1);
        if ($urlLocale && in_array($urlLocale, $this->supportedLocales)) {
            LaravelLocalization::setLocale($urlLocale);
            App::setLocale($urlLocale);
            return $next($request);
        }

        $ip = $request->ip();
        // if ($ip === '127.0.0.1' || $ip === '::1') {
        //     $ip = '151.36.0.1';
        // }
        $countryCode = $this->getCountryCodeFromIP($ip);

        $locale = match ($countryCode) {
            'IT' => 'it',
            'FR' => 'fr',
            'DE' => 'de',
            default => 'en',
        };

        if (auth()->check() && in_array(auth()->user()->language, $this->supportedLocales)) {
            $locale = auth()->user()->language;
        }

        App::setLocale($locale);
        LaravelLocalization::setLocale($locale);

        if ($urlLocale !== $locale) {
            $segments = $request->segments();
            array_unshift($segments, $locale);
            return redirect()->to(implode('/', $segments));
        }

        return $next($request);
    }

    protected function getCountryCodeFromIP($ip)
    {
        // if ($ip === '127.0.0.1' || $ip === '::1') {
        //     $ip = '151.36.0.1';
        // }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://ipapi.co/{$ip}/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$response || $httpCode !== 200) {
            $response = @file_get_contents("https://ipinfo.io/{$ip}/json");
            if (!$response) return null;
        }

        $data = json_decode($response, true);
        return $data['country'] ?? null;
    }
}
