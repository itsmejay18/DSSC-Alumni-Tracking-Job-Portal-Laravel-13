<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackLastActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            Cache::put(
                sprintf('user-online-%d', $request->user()->id),
                now()->toDateTimeString(),
                now()->addMinutes(10)
            );
        }

        return $next($request);
    }
}
