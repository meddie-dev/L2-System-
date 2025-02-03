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
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this resource.');
        }

        // Check if the authenticated user has the required role
        if (!Auth::user()->hasRole($role)) {
            if (str_contains($request->url(), '/portal')) {
                return redirect()->route('portal.login')->with('error', 'You do not have the required role to access this resource.');
            }
            return redirect()->route('login')->with('error', 'You do not have the required role to access this resource.');
        }

        return $next($request);
    }
}
