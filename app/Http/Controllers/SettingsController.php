<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        return view('pages.settings.settings-view');
    }

    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'driverType' => 'required|string',
        ]);

        $user = Auth::user();

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Updated profile record successfully in time of: " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => $request->ip(),
        ]);

        $user->firstName = $request->first_name;
        $user->lastName = $request->last_name;
        $user->email = $request->email;
        $user->driverType = $request->driverType;
        if ($user instanceof User && $user->update()) {
            return redirect()->route('settings.index')->with('success', 'Profile updated successfully.');
        }
        return back()->withErrors(['error' => 'Failed to update profile. Please try again later.']);
    }

    public function destroyView()
    {
        return view('pages.auth.destroy-view');
    }

    public function destroy(Request $request)
    {
        // Validate the inputs
        $request->validate([
            'password' => 'required',
            'confirmation_text' => 'required|in:DELETE MY ACCOUNT', 
        ]);

        // Check if the entered password matches the logged-in user's password
        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors(['password' => 'The provided password is incorrect. Please try again.']);
        }

        // Double-check if confirmation text matches "DELETE MY ACCOUNT"
        if ($request->confirmation_text !== 'DELETE MY ACCOUNT') {
            return back()->withErrors(['confirmation_text' => 'You must type "DELETE MY ACCOUNT" exactly to confirm account deletion.']);
        }

        // Proceed to delete the user's account
        $user = Auth::user();
        Auth::logout();
        $user->delete();

        // Redirect to login with a success message
        if (Auth::user()->hasRole('Vendor')) {
            return redirect('/portal/login')->with('success', 'Your account has been deleted successfully.');
        } else {
            return redirect('/login')->with('success', 'Your account has been deleted successfully.');
        }
    }
}
