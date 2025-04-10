<?php

namespace App\Http\Middleware;

use App\Models\ActivityLogs;
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
            
            ActivityLogs::create([
                'user_id' => auth()->id(),
                'event' => "Unauthorized access attempt at: " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => $request->ip(),
            ]);

            abort(401);
        }

        return $next($request);
    }
}
