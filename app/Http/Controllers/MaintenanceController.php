<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use App\Models\Maintenance;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    // Staff

    public function index()
    {
        $vehicle = Vehicle::whereHas('maintenance')->get();
        return view('modules.staff.maintenance.index', compact('vehicle'));
    }

    public function show(Vehicle $vehicle)
    {
        $maintenance = $vehicle->maintenance()->where('conditionStatus', 'poor')->get();
        return view('modules.staff.maintenance.show', compact('vehicle', 'maintenance'));
    }

    public function view(Maintenance $maintenance)
    {
        return view('modules.staff.maintenance.view', compact('maintenance'));
    }

    public function markAsAvailable(Maintenance $maintenance)
    {
        $maintenance->update([
            'conditionStatus' => 'good',
        ]);

        Vehicle::where('id', $maintenance->vehicle_id)->update(['vehicleStatus' => 'available']);

        ActivityLogs::create([
            'user_id' => Auth::user()->id,
            'event' => "Marked maintenance as available at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('staff.fleet.index')->with('success', 'Vehicle marked as available successfully.');
    }
}
