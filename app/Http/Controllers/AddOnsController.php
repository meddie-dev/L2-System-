<?php

namespace App\Http\Controllers;

use App\Models\Modules\VehicleReservation;
use Illuminate\Http\Request;

class AddOnsController extends Controller
{
    public function calendar()
    {
        $vehicleReservations = VehicleReservation::all();
        return view('pages.addOns.calendar', compact('vehicleReservations'));
    }
}
