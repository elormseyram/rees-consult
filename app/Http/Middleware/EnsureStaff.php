<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStaff
{
    /**
     * Allow any active staff member (admin or employee) into the admin panel.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access the admin panel.');
        }

        $user = auth()->user();

        if (! $user->isStaff()) {
            abort(403, 'You do not have access to the admin panel.');
        }

        if (! $user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact an administrator.');
        }

        return $next($request);
    }
}
