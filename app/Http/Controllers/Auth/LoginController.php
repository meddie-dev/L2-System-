<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TwoFactorCodeNotification;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\ActivityLogs;
use App\Models\User;

class LoginController extends Controller
{
    use HasRoles;

    /**
     * Show the login form.
     */
    public function index()
    {
        return view('pages.auth.login');
    }

    /**
     * Handle the login attempt.
     */
    public function login(Request $request)
    {
        // Validate the login request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'nullable|boolean'
        ]);

        // Attempt to login the user
        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
            $user = Auth::user();

            // Update the last active time for the authenticated user
            $user->last_active_at = Carbon::now('Asia/Manila')->format('Y-m-d h:i A');
            $user->save();

            // Check if the user has 2FA enabled (0 = false, 1 = true)
            if ($user->two_factor_enabled == 1) {
                $this->sendTwoFactorCode($user);
                return redirect()->route('two-factor.show');
            }

            ActivityLogs::create([
                'user_id' => Auth::id(),
                'event' => "Logged in at: " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => $request->ip(),
            ]);

            if ($user->hasRole('Super Admin')) {
                return redirect()->route('superadmin.dashboard');
            } elseif ($user->hasRole('Admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('Driver')) {
                return redirect()->route('driver.dashboard');
            } else {
                return redirect()->route('staff.dashboard');
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    /**
     * Send the 2FA code to the user's email.
     */
    private function sendTwoFactorCode($user)
    {
        $code = Str::random(6);

        $user->two_factor_code = $code;
        $user->two_factor_expires_at = Carbon::now()->addMinutes(10); // Code valid for 10 minutes
        $user->save();

        $user->notify(new TwoFactorCodeNotification($code));
    }

    /**
     * Handle the logout process.
     */
    public function destroy(Request $request)
    {
        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Logged out at: " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => $request->ip(),
        ]);

        Auth::logout();

        return redirect('/login');
    }
}
