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
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $user = Auth::user();

        if ($role === 'admin' && !$user->isAdmin()) {
            abort(403, 'Unauthorized. Admin privileges required.');
        }

        if ($role === 'user' && $user->isAdmin()) {
            // Admin can also view user pages if desired, or let them proceed
            return $next($request);
        }

        return $next($request);
    }
}
