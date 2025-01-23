<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Show the "Forgot Password" form.
     */
    public function create()
    {
        return view('pages.auth.forgot-password');
    }

    /**
     * Handle the form submission for sending the password reset link.
     */
    public function store(Request $request)
    {
        // Validate the email address
        $request->validate(['email' => 'required|email']);

        // Attempt to send the password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Check if the email was sent successfully
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', trans($status));
        } else {
            return back()->withErrors(['email' => trans($status)]);
        }
    }
}
