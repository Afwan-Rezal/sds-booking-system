<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();

        $userRole = optional(optional($user)->profile)->role;
        if (! $user || ! $userRole || strtolower($userRole) !== strtolower($role)) {
            abort(403, 'You are not authorized to access this resource.');
        }

        return $next($request);
    }
}

