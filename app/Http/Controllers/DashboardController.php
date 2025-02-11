<?php

namespace App\Http\Controllers;

use App\Models\Modules\Document;
use App\Models\Modules\Order;
use App\Models\Payment;
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
        $roles = ['Super Admin', 'Admin', 'Staff', 'Vendor', 'Driver'];
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

    public function vendorPortalDashboard()
    {
        $orders = Order::where('user_id', Auth::id())->get();
        $documents = Document::where('user_id', Auth::id())->get();
        $payments = Payment::where('user_id', Auth::id())->get();
        $months = [];
        $orderCounts = [];

        // Loop through the last 12 months
        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::now()->subMonths(11 - $i); // Starting from 11 months ago to current month
            $months[] = $month->format('M Y');

            // Count orders created in each month
            $orderCounts[] = Order::where('user_id', Auth::id())
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return view('components.dashboard.vendorPortal', compact('orders', 'months', 'orderCounts', 'documents', 'payments'));
    }

    public function driverDashboard()
    {
        return view('components.dashboard.driver');
    }
}
