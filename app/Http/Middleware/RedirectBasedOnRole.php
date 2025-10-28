<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $profile = $user->profile;

            if ($profile) {
                $role = strtolower($profile->role);

                switch ($role) {
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    case 'staff':
                        return redirect()->route('staff.dashboard');
                    case 'student':
                        return redirect()->route('student.dashboard');
                    default:
                        // If role is not recognized, allow the request to continue
                        break;
                }
            }
        }

        return $next($request);
    }
}

