<?php

namespace App\Http\Controllers\Modules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessFuelTransaction;
use App\Models\ActivityLogs;
use App\Models\FleetCard;
use App\Models\Fuel;
use App\Models\Modules\VehicleReservation;
use App\Models\shipments;
use App\Models\TripTicket;
use App\Models\User;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class FleetController extends Controller
{
    // Admin

    // Fleet - Vehicles
    public function index()
    {
        $vehicles = Vehicle::all();
        return view('modules.admin.fleet.vehicle.index', compact('vehicles'));
    }

    public function create(User $user)
    {
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
            'fuel_efficiency' => 'required|numeric|min:0.1',
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
        $fuels = Fuel::where('vehicle_id', $vehicle->id)->get();
        return view('modules.admin.fleet.vehicle.edit', compact('vehicle', 'fuels'));
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
            'fuel_efficiency' => 'required|numeric|min:0.1',
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
            'fuel_efficiency' => $request->fuel_efficiency
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
        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Deleted Vehicle: {$vehicle->plateNumber} at time: " . now('Asia/Manila')->format('Y-m-d H:i'),
            'ip_address' => request()->ip(),
        ]);

        $vehicle->delete();

        return redirect()->route('admin.fleet.index')->with('success', 'Vehicle deleted successfully.');
    }

    // Fleet - Driver
    public function driverIndex()
    {
        $drivers = User::role('Driver')->get();
        return view('modules.admin.fleet.driver.index', compact('drivers'));
    }


    public function driverDetails(User $user)
    {
        $tripTicket = TripTicket::where('user_id', $user->id)->get();
        $vehicle = Vehicle::where('user_id', $user->id)->get();
        return view('modules.admin.fleet.driver.details', compact('user', 'tripTicket', 'vehicle'));
    }

    public function driverUpdate(User $user)
    {
        try {
            $user->update([
                'firstName' => request()->firstName,
                'lastName' => request()->lastName,
                'email' => request()->email,
                'driverType' => request()->driverType,
            ]);

            ActivityLogs::create([
                'user_id' => Auth::id(),
                'event' => "Updated Driver: {$user->firstName} {$user->lastName} at time: " . now('Asia/Manila')->format('Y-m-d H:i'),
                'ip_address' => request()->ip(),
            ]);

            return redirect()->route('admin.fleet.driver.index')->with('success', 'Driver updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.fleet.driver.index')->with('error', 'Failed to update driver: ' . $e->getMessage());
        }
    }

    // Fleet - Fuel
    public function fuelIndex()
    {
        $fuel = Fuel::all();
        return view('modules.admin.fleet.fuel.index', compact('fuel'));
    }

    public function fuelDetails(Fuel $fuel)
    {
        return view('modules.admin.fleet.fuel.details', compact('fuel',));
    }

    public function fuelUpdate(FleetCard $fleetCard)
    {
        $fleetCard->update([
            'cardNumber' => request()->cardNumber,
            'credit_limit' => request()->credit_limit,
            'balance' => request()->balance,
            'status' => request()->status,
            'expiry_date' => request()->expiry_date,
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Updated Fuel: {$fleetCard->cardNumber} at time: " . now('Asia/Manila')->format('Y-m-d H:i'),
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.fleet.fuel.index')->with('success', 'Fuel updated successfully.');
    }

    //  Driver

    public function driverTask()
    {
        $vehicleReservation = VehicleReservation::where('redirected_to', auth()->user()->id)->get();
        return view('modules.driver.task.index', compact('vehicleReservation'));
    }

    public function driverTaskDetails(VehicleReservation $vehicleReservation)
    {
        $tripTicket = TripTicket::where('user_id', auth()->user()->id)->first();

        return view('modules.driver.task.details', compact('vehicleReservation', 'tripTicket'));
    }

    public function driverTripTicketPdf(VehicleReservation $vehicleReservation)
    {
        $tripTicket = TripTicket::where('user_id', auth()->user()->id)->first();
        $userId = auth()->user()->id;

        $fuel = Fuel::whereHas('fleetCard', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->first();

        if (!$tripTicket) {
            return response()->json(['error' => 'Trip Ticket not found'], 404);
        }

        // Define the filename and storage path
        $filename = $tripTicket->tripNumber . '.pdf';
        $folderPath = "trip_tickets/{$userId}/";
        $fullPath = "public/{$folderPath}{$filename}";

        // Ensure directory exists
        Storage::disk('public')->makeDirectory($folderPath);

        // Check if an existing PDF file needs to be deleted
        if (Storage::disk('public')->exists($folderPath . $filename)) {
            Storage::disk('public')->delete($folderPath . $filename);
        }

        // Generate PDF
        $pdf = Pdf::loadView('pdf.tripTicket', compact('vehicleReservation', 'tripTicket', 'fuel'));

        // Store PDF in public disk
        Storage::disk('public')->put($folderPath . $filename, $pdf->output());

        // Log for debugging
        Log::info("PDF saved at: " . storage_path("app/public/{$folderPath}{$filename}"));

        return $pdf->stream($filename);
    }


    public function driverTrip()
    {
        $tripTicket = TripTicket::where('user_id', auth()->user()->id)->get();
        return view('modules.driver.trip.index', compact('tripTicket'));
    }

    public function driverTripDetails(TripTicket $tripTicket, VehicleReservation $vehicleReservation)
    {
        return view('modules.driver.trip.details', compact('tripTicket', 'vehicleReservation'));
    }

    public function driverCard()
    {
        $fleetCard = FleetCard::where('user_id', auth()->user()->id)->get();
        return view('modules.driver.card.index', compact('fleetCard'));
    }

    public function driverCardDetails(FleetCard $fleetCard)
    {
        return view('modules.driver.card.details', compact('fleetCard'));
    }

    // Gas Station
    public function gasStationIndex()
    {
        return view('pages.gasStation.index');
    }

    public function gasStationVerify(Request $request)
    {
        if (!is_array($request->cardNumber)) {
            return back()->with('error', 'Invalid input.');
        }
    
        $cardNumber = implode('', $request->cardNumber);
        $fleetCard = FleetCard::where('cardNumber', $cardNumber)->first();
    
        if (!$fleetCard) {
            return back()->with('error', 'Invalid Fleet Card Number.');
        }
    
        $fuel = Fuel::where('fleet_card_id', $fleetCard->id)
            ->where('fuelStatus', 'scheduled')
            ->first();
    
        if (!$fuel) {
            return back()->with('error', 'No scheduled fuel transaction found.');
        }
    
        if ($fleetCard->credit_limit < $fuel->estimatedCost) {
            return back()->with('error', 'Insufficient Credit Limit.');
        }
    
        // Update fuel status and deduct credit
        $fuel->update(['fuelStatus' => 'completed']);
        $fleetCard->update([
            'credit_limit' => $fleetCard->credit_limit - $fuel->estimatedCost,
            'balance' => $fleetCard->balance - $fuel->estimatedCost
        ]);
    
        // Dispatch job to generate PDF asynchronously
        ProcessFuelTransaction::dispatch($fleetCard, $fuel);
    
        return back()->with('success', 'Transaction completed successfully. PDF will be available soon.');
    }
}
