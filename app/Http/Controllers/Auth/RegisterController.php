<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RegisterController extends Controller
{
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pages.auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        // Create the user
        $user = $this->create($request->all());

        // Assign default role after registration
        if (User::count() == 1) {
            $user->assignRole('Super Admin');
        } elseif (User::count() == 2) {
            $user->assignRole('Admin');
        } else {
            $user->assignRole('Staff');
        }
        
        // Log the user in
        Auth::login($user);

        switch ($user->roles->first()->name) {
            case 'Super Admin':
                return redirect()->route('superadmin.dashboard')
                    ->with('success', 'Welcome! You have been successfully registered as a Super Admin.');
                break;
            case 'Admin':
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome! You have been successfully registered as an Admin.');
                break;
            default:
                return redirect()->route('staff.dashboard')
                    ->with('success', 'Welcome! You have been successfully registered as a Staff.');
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'firstName' => $data['first_name'],
            'lastName' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
