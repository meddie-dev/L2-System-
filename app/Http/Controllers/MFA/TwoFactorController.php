<?php

namespace App\Http\Controllers\MFA;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\TwoFactorCodeNotification;
use App\Notifications\NewNotification;
use App\Http\Controllers\Controller;
use App\Models\ActivityLogs;
use Spatie\Permission\Traits\HasRoles;

class TwoFactorController extends Controller
{
    use HasRoles;
    /**
     * Show the form for entering the 2FA code.
     */
    public function show()
    {
        return view('pages.mfa.two-factor');
    }

    /**
     * Generate and send the 2FA code via email.
     */
    public function sendCode()
    {
        $user = Auth::user();

        // Generate a 6-digit random code
        $code = Str::random(6);

        // Store the code and expiration time in the database
        $user->two_factor_code = $code;
        $user->two_factor_expires_at = Carbon::now()->addMinutes(10); // Code valid for 10 minutes
        $user->save();

        // Send the code via email using the notification
        try {
            $user->notify(new TwoFactorCodeNotification($code));
            return redirect()->route('two-factor.show')->with('success', 'A verification code has been sent to your email.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to send the verification code. Please try again.'])->withInput();
        }
    }

    /**
     * Resend the 2FA code to the user's email.
     */
    public function resendCode()
    {
        $user = Auth::user();

        // Generate a new 6-digit random code
        $code = Str::random(6);

        // Update the code and expiration time in the database
        $user->two_factor_code = $code;
        $user->two_factor_expires_at = Carbon::now()->addMinutes(10); // Code valid for 10 minutes
        $user->save();

        // Send the code via email using the notification
        try {
            $user->notify(new TwoFactorCodeNotification($code));
            return redirect()->route('two-factor.show')->with('success', 'A new verification code has been sent to your email.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to resend the verification code. Please try again.']);
        }
    }


    /**
     * Verify the entered 2FA code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|min:6',
        ]);

        $user = Auth::user();

        // Check if the code is valid and not expired
        if ($user->two_factor_code === $request->code && Carbon::now()->lt($user->two_factor_expires_at)) {
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->two_factor_enabled = true;
            $user->save();

            $user->notify(new NewNotification('Two-factor authentication Code has been verified successfully.'));

            ActivityLogs::create([
                'user_id' => Auth::id(),
                'event' => "Two-factor authentication Code has been verified time of: " . now()->format('Y-m-d H:i:s'),
                'ip_address' => $request->ip(),
            ]);

            $route = match (true) {
                Auth::user()->hasRole('Super Admin') => 'superadmin.dashboard',
                Auth::user()->hasRole('Admin') => 'admin.dashboard',
                Auth::user()->hasRole('Staff') => 'staff.dashboard',
                Auth::user()->hasRole('Vendor') => 'vendorPortal.dashboard',
                Auth::user()->hasRole('Driver') => 'driver.dashboard',
                default => throw new \Exception('Unknown user role'),
            };

            return redirect()->route($route)->with('success', 'The Verification code was successful. This session is now authenticated.');
        }

        return back()->withErrors(['code' => 'The verification code is invalid or has expired.'])->withInput($request->only('code'));
    }

    /**
     * Turn off 2FA for the user.
     */
    public function disable(Request $request)
    {
        $user = Auth::user();

        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->two_factor_enabled = false;
        $user->save();

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Two-factor authentication has been disabled time of: " . now()->format('Y-m-d H:i:s'),
            'ip_address' => $request->ip(),
        ]);

        $user->notify(new NewNotification('Two-factor authentication has been successfully disabled for your account. We recommend that you enable two-factor authentication again to enhance the security of your account.'));

        return redirect()->route('settings.index')->with('success', 'Two-factor authentication has been disabled. This session is now unauthenticated.');
    }
}
