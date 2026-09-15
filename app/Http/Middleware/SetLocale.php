<?php

namespace App\Http\Middleware;

use App\Support\Locales;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Apply the visitor's preferred locale (session choice, falling back
     * to the application default) to the translator and date formatter.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Locales::current();

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
