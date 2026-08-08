<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        // Log guards and their authentication state for debugging redirect loops
        $states = [];
        foreach ($guards as $guard) {
            try {
                $states[$guard ?? 'null'] = Auth::guard($guard)->check();
            } catch (\Throwable $e) {
                $states[$guard ?? 'null'] = 'error: '.$e->getMessage();
            }
        }
        Log::info('RedirectIfAuthenticated guards check', ['guards' => $guards, 'states' => $states, 'route' => optional(request()->route())->getName()]);

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard === 'klien') {
                    return redirect()->route('dashboard.klien');
                }

                return redirect()->route('dashboard.internal');
            }
        }

        return $next($request);
    }
}
