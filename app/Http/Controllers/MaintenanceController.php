<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $maintenance = $vehicle->maintenance()->get();
        return view('modules.staff.maintenance.show', compact('vehicle', 'maintenance'));
    }
}
