<?php

namespace App\Http\Controllers\Auth\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TwoFactorCodeNotification;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class PortalLoginController extends Controller
{
    use HasRoles;

    /**
     * Show the login form.
     */
    public function index()
    {
        return view('pages.auth.portal.login');
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

            // Check if the user is restricted until a certain date/time
            if (!empty($user->restricted_until) && Carbon::parse($user->restricted_until)->isFuture()) {
                Auth::logout();
                return redirect()->route('portal.login')->withErrors([
                    'email' => 'Your account is restricted until ' . Carbon::parse($user->restricted_until)->format('Y-m-d h:i A') . '.'
                ]);
            }


            // Check if the user has 2FA enabled (0 = false, 1 = true)
            if ($user->two_factor_enabled == 1) {
                $this->sendTwoFactorCode($user);
                return redirect()->route('two-factor.show');
            }

            return redirect()->route('vendorPortal.dashboard');
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
    public function destroy()
    {
        Auth::logout();
        return redirect()->route('portal.login');
    }
}
