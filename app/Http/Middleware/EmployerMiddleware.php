<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->role === 'employer', 403);

        return $next($request);
    }
}
