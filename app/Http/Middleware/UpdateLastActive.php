<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Update the last active time for the authenticated user
            $user = Auth::user();
            User::where('id', $user->id)->update(['last_active_at' => Carbon::now('Asia/Manila')->format('Y-m-d g:i A')]);
    
            return $next($request);
        }
    }
}
