<?php

namespace App\Http\Middleware;

use App\Models\AnalyticsLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackAnalytics
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && $request->header('X-Inertia') === 'true') {
            AnalyticsLog::create([
                'path' => $request->path(),
                'session_hash' => hash('sha256', $request->session()->getId()),
                'referrer' => $request->header('referer'),
                'visited_at' => now(),
            ]);
        }

        return $response;
    }
}