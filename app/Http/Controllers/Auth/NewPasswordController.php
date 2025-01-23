<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class NewPasswordController extends Controller
{
    use HasRoles;
    /**
     * Show the "Reset Password" form.
     */
    public function create(Request $request)
    {
        return view('pages.auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle the form submission to reset the password.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Attempt to retrieve the user by email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists
        if (!$user) {
            return back()->withErrors(['email' => __('We could not find a user with that email address.')]);
        }

        // Check if the new password isn't the same as the current password
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => __('Your new password cannot be the same as your current password.')]);
        }

        // Attempt to reset the password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        // Check if the password was successfully reset
        if ($status === Password::PASSWORD_RESET) {
            if (Auth::check()) {
                // Redirect based on role
                if (Auth::user()->hasRole('Super Admin')) {
                    return redirect()->route('superadmin.dashboard')->with('success', __('Your password has been reset successfully.'));
                } elseif (Auth::user()->hasRole('Admin')) {
                    return redirect()->route('admin.dashboard')->with('success', __('Your password has been reset successfully.'));
                } elseif (Auth::user()->hasRole('Staff')) {
                    return redirect()->route('staff.dashboard')->with('success', __('Your password has been reset successfully.'));
                }
            }
            return redirect()->route('login')->with('success', __('Your password has been reset successfully.'));
        } else {
            return back()->withErrors(['email' => __('There was an error resetting your password.')]);
        }
    }
}
