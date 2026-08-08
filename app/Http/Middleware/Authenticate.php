<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Authenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $next($request);
            }
        }

        return $this->redirectTo($request, $guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request, array $guards)
    {
        if ($request->expectsJson()) {
            Log::info('Authenticate redirectTo: expectsJson', ['guards' => $guards, 'route' => optional(request()->route())->getName()]);
            return null;
        }

        // Log for debugging which guards caused unauthenticated redirect
        Log::info('Authenticate redirectTo: unauthenticated', ['guards' => $guards, 'route' => optional(request()->route())->getName()]);

        if (in_array('klien', $guards, true)) {
            return route('client.login');
        }

        return route('login');
    }
}
