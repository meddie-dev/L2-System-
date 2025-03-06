<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use App\Models\Modules\Document;
use App\Models\Modules\Order;
use App\Models\Modules\VehicleReservation;
use App\Models\Payment;
use App\Models\TripTicket;
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
        $activityLogs = ActivityLogs::where('user_id', Auth::id())->get();
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
        return view('components.dashboard.staff', compact('activityLogs', 'months', 'orderCounts'));
    }

    public function vendorPortalDashboard()
    {
        $userOrderIds = Auth::user()->order()->pluck('id'); 

        $tripTickets = TripTicket::whereIn('order_id', $userOrderIds)->with('order')->get();
        $orders = Order::where('user_id', Auth::id())->get();

        $statusCounts = [
            'scheduled' => TripTicket::whereIn('order_id', $userOrderIds)->where('status', 'scheduled')->count(),
            'in_transit' => TripTicket::whereIn('order_id', $userOrderIds)->where('status', 'in_transit')->count(),
            'delivered' => TripTicket::whereIn('order_id', $userOrderIds)->where('status', 'delivered')->count(),
        ];
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

        return view('components.dashboard.vendorPortal', compact('orders','statusCounts', 'tripTickets', 'months', 'orderCounts', 'documents', 'payments'));
    }

    public function driverDashboard()
    {
        $user = Auth::user();
        $logs = ActivityLogs::where('user_id', Auth::id())->get();
        $latestTripTicket = TripTicket::where('user_id', Auth::id())->latest()->first();
        $latestVehicleReservation = VehicleReservation::where('redirected_to', Auth::id())->latest()->first();        
    
        return view('components.dashboard.driver', compact('user', 'latestTripTicket', 'latestVehicleReservation', 'logs'));
    }
    
}
