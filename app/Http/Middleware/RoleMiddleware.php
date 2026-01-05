<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('Please login to access this page.');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            // Redirect based on user role to their appropriate dashboard
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->withErrors('You do not have permission to access this page.');
            } elseif ($user->isPsychologist()) {
                return redirect()->route('psychologist.dashboard')->withErrors('You do not have permission to access this page.');
            } elseif ($user->isPatient()) {
                return redirect()->route('patient.dashboard')->withErrors('You do not have permission to access this page.');
            } else {
                // Unknown role, redirect to home
                Auth::logout();
                return redirect()->route('index')->withErrors('You do not have a valid role. Please contact administrator.');
            }
        }

        return $next($request);
    }
}
