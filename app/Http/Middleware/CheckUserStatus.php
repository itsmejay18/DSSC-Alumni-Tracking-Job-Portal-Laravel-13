<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if (! $user->is_active) {
            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Your account has been deactivated. Please contact the administrator.']);
        }

        if (
            $user->role === 'employer'
            && ! $user->is_approved
            && ! $request->routeIs('employer.profile.verification', 'logout')
        ) {
            return redirect()
                ->route('employer.profile.verification')
                ->with('warning', 'Your employer account is pending admin approval.');
        }

        return $next($request);
    }
}
