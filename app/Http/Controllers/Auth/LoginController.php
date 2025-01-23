<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class LoginController extends Controller
{
    use HasRoles;
    public function index()
    {
        return view('pages.auth.login');
    }

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
            // Redirect based on role
            if (Auth::user()->hasRole('Super Admin')) {
                return redirect()->route('superadmin.dashboard');
            } elseif (Auth::user()->hasRole('Admin')) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('staff.dashboard');
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function destroy()
    {
        Auth::logout();
        return redirect('/login');
    }
}
