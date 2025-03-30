<?php

namespace App\Http\Controllers;

use App\Models\AssetReport;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::has('assetReport')->get();
        return view('modules.admin.asset.index', compact('vehicles'));
    }

    public function details(Vehicle $vehicle)
    {
        $assetReport = AssetReport::where('vehicle_id', $vehicle->id)->first();
        return view('modules.admin.asset.details', compact( 'assetReport'));
    }

    public function indexSA()
    {
        $vehicles = Vehicle::has('assetReport')->get();
        return view('modules.superAdmin.asset.index', compact('vehicles'));
    }

    public function detailsSA(Vehicle $vehicle)
    {
        $assetReport = AssetReport::where('vehicle_id', $vehicle->id)->first();
        return view('modules.superAdmin.asset.details', compact( 'assetReport'));
    }
}
