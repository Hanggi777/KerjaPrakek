<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $allowedRoles = preg_split('/[|,]/', $role, -1, PREG_SPLIT_NO_EMPTY);

        if (! in_array($user->role, $allowedRoles, true)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
