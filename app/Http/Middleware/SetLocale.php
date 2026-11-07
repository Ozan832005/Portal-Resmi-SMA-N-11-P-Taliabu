<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Apply the language selected for the current visitor.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale', 'id');
        $locale = in_array($locale, ['id', 'en'], true) ? $locale : 'id';

        App::setLocale($locale);
        Carbon::setLocale($locale);
        config(['app.fallback_locale' => 'id']);

        return $next($request);
    }
}
