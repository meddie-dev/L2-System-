<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    // Staff

    public function index()
    {
        $threshold = 3000; // Adjust this based on the maintenance type

        // Subquery to calculate total trip distance per vehicle
        $tripSumQuery = DB::table('trip_tickets')
            ->selectRaw('vehicle_id, SUM(distance) as total_distance')
            ->groupBy('vehicle_id');
    
        // Main query
        $vehicle = Vehicle::select(
                'vehicles.id', 
                'vehicles.plateNumber', 
                'vehicles.vehicleType', 
                'vehicles.vehicleStatus',
                'vehicles.created_at',
                DB::raw('(COALESCE(trip_ticket_sums.total_distance, 0) * 2) as total_distance')
            )
            ->leftJoinSub($tripSumQuery, 'trip_ticket_sums', function ($join) {
                $join->on('vehicles.id', '=', 'trip_ticket_sums.vehicle_id');
            })
            ->whereRaw('(COALESCE(trip_ticket_sums.total_distance, 0) * 2) >= ?', [$threshold]) // Using whereRaw instead of HAVING
            ->orderByDesc('total_distance')
            ->get();
        return view('modules.staff.maintenance.index', compact('vehicle'));
    }
}
