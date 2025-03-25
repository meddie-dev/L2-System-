<?php

namespace App\Http\Controllers;

use App\Models\Modules\VehicleReservation;
use App\Models\TripTicket;
use Illuminate\Http\Request;

class AddOnsController extends Controller
{
    // Super Admin, Admin and Staff
    public function calendar()
    {
        $vehicleReservations = VehicleReservation::all();
        return view('pages.addOns.calendar', compact('vehicleReservations'));
    }

    public function calendarDriver()
    {
        $vehicleReservations = VehicleReservation::where('redirected_to', auth()->user()->id)->get();
        return view('pages.addOns.calendarDriver', compact('vehicleReservations'));
    }
    
    public function map()
    {
        $tripTickets = TripTicket::all(['pickUpLat', 'pickUpLng', 'dropOffLat', 'dropOffLng', 'tripNumber']);
        return view('pages.addOns.map', compact('tripTickets'));
    }
}
