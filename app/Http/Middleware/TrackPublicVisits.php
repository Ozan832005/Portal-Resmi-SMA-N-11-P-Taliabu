<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackPublicVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET')) {
            return $next($request);
        }

        $today = now()->toDateString();

        if ($request->session()->get('public_visit_tracked_on') === $today) {
            return $next($request);
        }

        $userAgent = (string) $request->userAgent();

        if (preg_match('/bot|crawler|spider|slurp|headless|preview/i', $userAgent) === 1) {
            return $next($request);
        }

        $browser = $this->browserName($userAgent);

        DB::table('site_visit_statistics')->insertOrIgnore([
            'visit_date' => $today,
            'browser' => $browser,
            'visits' => 0,
        ]);

        DB::table('site_visit_statistics')
            ->where('visit_date', $today)
            ->where('browser', $browser)
            ->increment('visits');

        $request->session()->put('public_visit_tracked_on', $today);

        return $next($request);
    }

    private function browserName(string $userAgent): string
    {
        $userAgent = strtolower($userAgent);

        return match (true) {
            str_contains($userAgent, 'edg/') => 'Edge',
            str_contains($userAgent, 'opr/') || str_contains($userAgent, 'opera') => 'Opera',
            str_contains($userAgent, 'firefox/') => 'Firefox',
            str_contains($userAgent, 'chrome/') || str_contains($userAgent, 'chromium/') => 'Chrome',
            str_contains($userAgent, 'safari/') => 'Safari',
            default => 'Lainnya',
        };
    }
}
