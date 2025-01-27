<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function superAdminDashboard()
    {
        $months = [];
        $userCounts = [];
        $roles = ['Super Admin', 'Admin', 'Staff'];
        $roleCounts = [];

        // Loop through the last 12 months
        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::now()->subMonths(11 - $i); // Starting from 11 months ago to current month
            $months[] = $month->format('M Y');

            // Count users created in each month
            $userCounts[] = User::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // Count users based on their roles
        foreach ($roles as $role) {
            $roleCounts[] = User::role($role)->count(); // Using HasRoles `role()` scope for cleaner code
        }

        // Get all users
        $users = User::all();

        // Return the view with data
        return view('components.dashboard.superAdmin', compact('months', 'userCounts', 'users', 'roles', 'roleCounts'));
    }


    public function adminDashboard()
    {
        return view('components.dashboard.admin');
    }

    public function staffDashboard()
    {
        return view('components.dashboard.staff');
    }
}
