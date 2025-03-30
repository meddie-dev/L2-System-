<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use App\Models\IncidentReport;
use App\Models\Modules\Document;
use App\Models\Modules\Order;
use App\Models\Modules\VehicleReservation;
use App\Models\Payment;
use App\Models\Product;
use App\Models\TripTicket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $fraud = ActivityLogs::where('event', 'LIKE', 'Unauthorized access attempt at:%')
            ->whereMonth('created_at', Carbon::now()->month)
            ->get();

        $product = Product::whereMonth('created_at', Carbon::now()->month)->get();

        $vehicleReservations = VehicleReservation::whereMonth('created_at', Carbon::now()->month)->get();

        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get top 5 most ordered products for the current month
        $demandProducts = DB::table('order_products')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->select('order_products.product_id', DB::raw('SUM(order_products.quantity) as total_quantity'))
            ->whereYear('orders.created_at', $currentYear)
            ->whereMonth('orders.created_at', $currentMonth)
            ->groupBy('order_products.product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // Fetch product names based on product IDs
        $productIds = $demandProducts->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productIds)->pluck('name', 'id');

        // Prepare data for the chart
        $productNames = $demandProducts->map(fn($item) => $products[$item->product_id] ?? 'Unknown Product');
        $orderCounts = $demandProducts->pluck('total_quantity');

        return view('components.dashboard.admin', [
            'productNames' => $productNames,
            'orderCounts' => $orderCounts,
            'fraud' => $fraud,
            'product' => $product,
            'vehicleReservations' => $vehicleReservations
        ]);
    }


    public function staffDashboard()
    {
        $logs = ActivityLogs::where('user_id', Auth::id())
            ->where('event', 'NOT LIKE', 'Unauthorized access attempt at:%')
            ->where('event', 'NOT LIKE', 'Logged in at%')
            ->where('event', 'NOT LIKE', 'Logged out at%')
            ->where('event', 'NOT LIKE', 'Tried to view Unassigned Task at%')
            ->where('event', 'NOT LIKE', 'Tried to view Unowned File at%')
            ->get();
        $months = [];
        $reviewCounts = [];

        $models = [Order::class, Payment::class, Document::class, VehicleReservation::class, IncidentReport::class];

        $pendingCounts = [];
        $reviewedCounts = [];
        $rejectedCounts = [];

        foreach ($models as $model) {
            $pendingCounts[] = (string) $model::where('approval_status', 'pending')
                ->where('assigned_to', Auth::id())
                ->count();

            $reviewedCounts[] = (string) $model::where('approval_status', '!=', 'pending')
                ->where('assigned_to', Auth::id())
                ->count();

            $rejectedCounts[] = (string) $model::where('approval_status', 'rejected')
                ->where('assigned_to', Auth::id())
                ->count();
        }

        // Ensure arrays contain only strings
        $pendingCounts = array_sum(array_map('intval', $pendingCounts));
        $reviewedCounts = array_sum(array_map('intval', $reviewedCounts));
        $rejectedCounts = array_sum(array_map('intval', $rejectedCounts));

        // Loop through the last 12 months
        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::now()->subMonths(11 - $i); // Starting from 11 months ago to current month
            $months[] = $month->format('M Y');

            // Count all models reviewed in each month
            $reviewCounts[] = [
                'orders' => Order::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where('reviewed_by', Auth::id())
                    ->count(),
                'payments' => Payment::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where('reviewed_by', Auth::id())
                    ->count(),
                'documents' => Document::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where('reviewed_by', Auth::id())
                    ->count(),
                'vehicleReservations' => VehicleReservation::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where('reviewed_by', Auth::id())
                    ->count(),
                'incidentReports' => IncidentReport::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where('reviewed_by', Auth::id())
                    ->count(),
            ];
        }
        return view('components.dashboard.staff', compact('logs', 'months', 'reviewCounts', 'pendingCounts', 'reviewedCounts', 'rejectedCounts'));
    }

    public function vendorPortalDashboard()
    {
        $logs = ActivityLogs::where('user_id', Auth::id())
            ->where('event', 'NOT LIKE', 'Unauthorized access attempt at:%')
            ->where('event', 'NOT LIKE', 'Logged in at%')
            ->where('event', 'NOT LIKE', 'Logged out at%') 
            ->where('event', 'NOT LIKE', 'Tried to view Unowned File at%')
            ->get();

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

        return view('components.dashboard.vendorPortal', compact('orders', 'logs', 'statusCounts', 'tripTickets', 'months', 'orderCounts', 'documents', 'payments'));
    }

    public function driverDashboard()
    {
        $user = Auth::user();
        $logs = ActivityLogs::where('user_id', Auth::id())
            ->where('event', 'NOT LIKE', 'Unauthorized access attempt at:%')
            ->where('event', 'NOT LIKE', 'Logged in at%')
            ->where('event', 'NOT LIKE', 'Logged out at%')
            ->where('event', 'NOT LIKE', 'Tried to view Unowned File at%')
            ->get();

        $latestTripTicket = TripTicket::where('user_id', Auth::id())->latest()->first();
        $latestVehicleReservation = VehicleReservation::where('redirected_to', Auth::id())->latest()->first();

        return view('components.dashboard.driver', compact('user', 'latestTripTicket', 'latestVehicleReservation', 'logs'));
    }
}
