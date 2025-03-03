<?php

namespace App\Http\Controllers\Modules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActivityLogs;
use App\Models\Modules\VehicleReservation;
use App\Models\shipments;
use App\Models\TripTicket;
use App\Models\User;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;


class FleetController extends Controller
{
    // Admin - Fleet - Vehicles

    public function index()
    {
        $vehicles = Vehicle::all();
        return view('modules.admin.fleet.vehicle.index', compact('vehicles'));
    }

    public function create(User $user) {
        return view('modules.admin.fleet.vehicle.create', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plateNumber' => 'required|string|max:255',
            'vehicleType' => 'required|string|max:255',
            'vehicleModel' => 'required|string|max:255',
            'vehicleMake' => 'required|string|max:255',
            'vehicleColor' => 'required|string|max:255',
            'vehicleYear' => 'required|integer|min:1900|max:2099',
            'vehicleFuelType' => 'required|string|in:diesel,gasoline,electric',
            'vehicleCapacity' => 'required|integer|min:1|max:9999',
            'vehicleImage' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Store vehicle image
        $file = $request->file('vehicleImage');
        $path = $file->storeAs("vehicles/{$request->vehicleType}", $request->plateNumber . '.' . $file->getClientOriginalExtension(), 'public');

        $vehicle = Vehicle::create(array_merge($validated, [
            'vehicleImage' => $path,
            'vehicleStatus' => "available",
            'conditionStatus' => 'good',
            'user_id' => Auth::id(),
        ]));

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Added Vehicle: {$vehicle->plateNumber} at time: " . now('Asia/Manila')->format('Y-m-d H:i'),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.fleet.index')->with('success', 'Vehicle added successfully.');
    }
    

    public function edit(Vehicle $vehicle)
    {
        return view('modules.admin.fleet.vehicle.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'plateNumber' => 'required|string|max:255',
            'vehicleType' => 'required|string|max:255',
            'vehicleModel' => 'required|string|max:255',
            'vehicleMake' => 'required|string|max:255',
            'vehicleColor' => 'required|string|max:255',
            'vehicleYear' => 'required|integer|min:1900|max:2099',
            'vehicleFuelType' => 'required|string|in:diesel,gasoline,electric',
            'vehicleCapacity' => 'required|integer|min:1|max:9999',
            'vehicleImage' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Store vehicle image
        if ($request->hasFile('vehicleImage')) {
            $file = $request->file('vehicleImage');
            $path = $file->storeAs("vehicles/{$request->vehicleType}", $request->plateNumber . '.' . $file->getClientOriginalExtension(), 'public');

            if ($vehicle->vehicleImage) {
                Storage::disk('public')->delete($vehicle->vehicleImage);
            }

            $vehicle->vehicleImage = $path;
        }

        $vehicle->update([
            'plateNumber' => $request->plateNumber,
            'vehicleType' => $request->vehicleType,
            'vehicleModel' => $request->vehicleModel,
            'vehicleMake' => $request->vehicleMake,
            'vehicleColor' => $request->vehicleColor,
            'vehicleYear' => $request->vehicleYear,
            'vehicleFuelType' => $request->vehicleFuelType,
            'vehicleCapacity' => $request->vehicleCapacity,
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Updated Vehicle: {$vehicle->plateNumber} at time: " . now('Asia/Manila')->format('Y-m-d H:i'),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.fleet.index')->with('success', 'Vehicle updated successfully.');
    }

    public function details(Vehicle $vehicle)
    {
        return view('modules.admin.fleet.vehicle.details', compact('vehicle'));
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('admin.fleet.index')->with('success', 'Vehicle deleted successfully.');
    }

    // Admin

    // Fleet - Driver
    public function driverIndex()
    {
        $drivers = User::role('Driver')->get();
        return view('modules.admin.fleet.driver.index', compact('drivers'));
    }


    public function driverCreate(User $user)
    {
        return view('modules.admin.fleet.driver.create', compact('user'));
    }

    // Fleet - Shipment
    public function shipmentIndex()
    {
        $shipments = Shipments::all();

        return view('modules.admin.fleet.shipment.manage', compact('shipments'));
    }

    public function shipmentCreate( Shipments $shipment)
    {
        
        return view('modules.admin.fleet.shipment.edit', compact('shipment'));
    }

    // Driver

    public function driverTask()
    {
        $vehicleReservation = VehicleReservation::where('redirected_to', auth()->user()->id)->get();
        return view('modules.driver.task.index', compact('vehicleReservation'));
    }

    public function driverTaskDetails(VehicleReservation $vehicleReservation)
    {
        return view('modules.driver.task.details', compact('vehicleReservation'));
    }

    public function driverTripTicketPdf(VehicleReservation $vehicleReservation)
    {   
        $tripTicket = TripTicket::where('user_id', auth()->user()->id)->first();
     
        $filename =  $tripTicket->tripNumber . '.pdf';
        $folderPath = "trip_tickets/{$vehicleReservation->user_id}/";
        $fullPath = str_replace(['/', '\\'], '_', "{$folderPath}{$filename}");
        
        $pdf = Pdf::loadView('pdf.tripTicket', compact('vehicleReservation'));
        return $pdf->save($fullPath)->stream($filename);
    }

}