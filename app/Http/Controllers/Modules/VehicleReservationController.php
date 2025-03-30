<?php

namespace App\Http\Controllers\Modules;

use App\Jobs\Admin\VehicleReservation\VehicleReservationApproved;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Admin\VehicleReservation\SendApprovalNotification;
use App\Jobs\Admin\VehicleReservation\SendVehicleReservationRejectionNotification;
use App\Jobs\Staff\VehicleReservation\SendVehicleReservationNotification;
use App\Jobs\Vendor\SendVehicleReservationNotifications;
use App\Models\ActivityLogs;
use App\Models\Modules\Order;
use App\Models\Modules\VehicleReservation;
use App\Models\TripTicket;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleReservationController extends Controller
{

    // Vendor

    // Vechicle Reservation -> Order
    public function vendorIndex()
    {
        $vehicleReservation = VehicleReservation::where('user_id', Auth::id())->get();
        return view('modules.vendor.vehicleReservation.index', compact('vehicleReservation'));
    }

    public function vendorCreate(User $user)
    {

        return view('modules.vendor.vehicleReservation.create', compact('user'));
    }

    public function vendorStore(Request $request, User $user)
    {
        $request->validate([
            'reservationNumber' => 'required|string|max:255',
            'reservationDate' => 'required|date',
            'reservationTime' => 'required|date_format:H:i',
            'vehicle_type' => 'required|string|max:255',
            'pickUpLocation' => 'required|string|max:255',
            'dropOffLocation' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
        ]);

        $apiKey = env('OPENROUTESERVICE_API_KEY');
        $fuelCostPerLitre = env('FUEL_COST_PER_LITRE', 56.55);
        $client = new Client();

        // Booking cost per vehicle type
        $bookingCostRates = [
            'light' => 500,  // Light vehicles: PHP 500
            'medium' => 1000, // Medium vehicles: PHP 1000
            'heavy' => 2000,  // Heavy vehicles: PHP 2000
        ];

        try {
            // Get Coordinates
            $pickUpResponse = $client->get("https://api.openrouteservice.org/geocode/search", [
                'query' => ['api_key' => $apiKey, 'text' => $request->pickUpLocation],
            ]);
            $dropOffResponse = $client->get("https://api.openrouteservice.org/geocode/search", [
                'query' => ['api_key' => $apiKey, 'text' => $request->dropOffLocation],
            ]);

            $pickUpData = json_decode($pickUpResponse->getBody(), true);
            $dropOffData = json_decode($dropOffResponse->getBody(), true);

            if (empty($pickUpData['features']) || empty($dropOffData['features'])) {
                Log::error("Geocode API failed: No features found for locations.");
                return back()->withErrors(['error' => 'Invalid pickup or drop-off location.']);
            }

            $pickUpCoords = $pickUpData['features'][0]['geometry']['coordinates'];
            $dropOffCoords = $dropOffData['features'][0]['geometry']['coordinates'];

            // Get Distance
            $requestDistance = new \GuzzleHttp\Psr7\Request('POST', 'https://api.openrouteservice.org/v2/matrix/driving-car', [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $apiKey,
            ], json_encode([
                'locations' => [[$pickUpCoords[0], $pickUpCoords[1]], [$dropOffCoords[0], $dropOffCoords[1]]],
                'metrics' => ['distance']
            ]));

            $response = $client->send($requestDistance);
            $data = json_decode($response->getBody(), true);

            if (empty($data['distances'][0][1])) {
                Log::error("Distance API failed: No valid distance returned.");
                return back()->withErrors(['error' => 'Could not calculate distance.']);
            }

            $distance = $data['distances'][0][1] / 1000; // Convert meters to KM
            $vehicle = Vehicle::where('vehicleType', $request->vehicle_type)
            ->where('vehicleStatus', 'available')
            ->first();


            if (!$vehicle || !$vehicle->fuel_efficiency || $vehicle->fuel_efficiency <= 0) {
                Log::error("Invalid vehicle data: Missing or zero fuel efficiency.");
                return back()->with('error', 'No vehicle available, please try again later.');
            }

            // Get base booking cost for selected vehicle type
            $selectedVehicleType = strtolower($request->vehicle_type);
            $bookingCost = $bookingCostRates[$selectedVehicleType] ?? 1000; 

            $fuelEfficiency = $vehicle->fuel_efficiency;
            $fuelRequired = 2 * ($distance / $fuelEfficiency); // Round trip
            $fuelCost = ($fuelRequired * $fuelCostPerLitre) + $bookingCost;
        } catch (\Exception $e) {
            Log::error("Error computing fuel and distance: " . $e->getMessage());
            return back()->withErrors(['error' => 'Could not compute distance and fuel cost.']);
        }

        $checkReservationNumber = VehicleReservation::where('reservationNumber', $request->reservationNumber)->first();
        $reservationNumber = $checkReservationNumber ? strtoupper(Str::random(20)) : $request->reservationNumber;

        $vehicleReservation = VehicleReservation::create([
            'user_id' => auth()->id(),
            'vehicle_id' => $vehicle->id,
            'reservationNumber' => $reservationNumber,
            'vehicle_type' => $request->vehicle_type,
            'reservationDate' => $request->reservationDate,
            'reservationTime' => $request->reservationTime,
            'pickUpLocation' => $request->pickUpLocation,
            'dropOffLocation' => $request->dropOffLocation,
            'purpose' => $request->purpose,
            'amount' => $fuelCost,
        ]);

        Vehicle::where('id', $vehicle->id)->update([
            'vehicleStatus' => 'unavailable',
        ]);

        ActivityLogs::create([
            'user_id' => auth()->id(),
            'event' => "Submitted Vehicle Reservation: {$vehicleReservation->reservationNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => $request->ip(),
        ]);

        // Dispatch job asynchronously
        SendVehicleReservationNotifications::dispatch($vehicleReservation, $user);

        return redirect()->route('vendorPortal.vehicleReservation.payment.new', ['vehicleReservation' => $vehicleReservation->id]);
    }

    public function vendorEdit(VehicleReservation $vehicleReservation)
    {
        return view('modules.vendor.vehicleReservation.edit', compact('vehicleReservation'));
    }

    public function vendorUpdate(Request $request, VehicleReservation $vehicleReservation)
    {
        $request->validate([
            'reservationNumber' => 'required|string|max:255',
            'reservationDate' => 'required|date',
            'reservationTime' => 'required|date_format:H:i',
            'vehicle_type' => 'required|string|max:255',
            'pickUpLocation' => 'required|string|max:255',
            'dropOffLocation' => 'required|string|max:255',
            "purpose" => "required|string|max:255",
        ]);


        $vehicleReservation->update([
            'reservationNumber' => $request->reservationNumber,
            'reservationDate' => $request->reservationDate,
            'reservationTime' => $request->reservationTime,
            'vehicle_type' => $request->vehicle_type,
            'pickUpLocation' => $request->pickUpLocation,
            'dropOffLocation' => $request->dropOffLocation,
            'purpose' => $request->purpose
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Updated Vehicle Reservation: {$vehicleReservation->reservation_number} in time of: " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('vendorPortal.vehicleReservation.payment.edit', ['vehicleReservation' => $vehicleReservation->id]);
    }

    public function vendorDestroy(VehicleReservation $vehicleReservation)
    {
        $vehicleReservation->delete();
        return redirect()->route('vendorPortal.vehicleReservation')->with('success', 'Vehicle reservation deleted successfully.');
    }

    public function vendorDetails(VehicleReservation $vehicleReservation)
    {
        return view('modules.vendor.vehicleReservation.details', compact('vehicleReservation'));
    }

    public function vendorStatusIndex()
    {
        $vehicleReservationID = VehicleReservation::where('user_id', auth()->id())->pluck('id');
        $tripTickets = TripTicket::whereIn('vehicle_reservation_id', $vehicleReservationID)->with('vehicleReservation')->get();

        return view('modules.vendor.vehicleReservation.status', compact('tripTickets'));
    }

    public function vendorStatusDetails(TripTicket $tripTicket)
    {
        return view('modules.vendor.vehicleReservation.statusDetails', compact('tripTicket'));
    }

    // Staff

    // Vehicle Reservation -> Order
    public function indexOrder()
    {
        $orders = Order::where('assigned_to', auth()->id())
            ->where(function ($query) {
                $query->where('approval_status', '=', 'reviewed')
                    ->orWhere('approval_status', '=', 'approved');
            })
            ->whereHas('payment', function ($q) {
                $q->where(function ($query) {
                    $query->where('approval_status', 'reviewed')
                        ->orWhere('approval_status', 'approved');
                });
            })
            ->whereHas('document', function ($q) {
                $q->where(function ($query) {
                    $query->where('approval_status', 'reviewed')
                        ->orWhere('approval_status', 'approved');
                });
            })
            ->get();

        return view('modules.staff.vehicleReservation.indexOrder', compact('orders'));
    }

    public function createOrder(Order $order)
    {
        return view('modules.staff.vehicleReservation.createOrder', compact('order'));
    }

    public function storeOrder(Request $request, User $user, Order $order)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'reservationNumber' => 'required|string|max:255',
                'reservationDate' => 'required|date',
                'reservationTime' => 'required|date_format:H:i',
                'vehicle_type' => 'required|string|max:255',
                'pickUpLocation' => 'required|string|max:255',
                'dropOffLocation' => 'required|string|max:255',
            ]);

            $reservationNumber = VehicleReservation::where('reservationNumber', $request->reservationNumber)->exists()
                ? strtoupper(Str::random(20))
                : $request->reservationNumber;

            $vehicleReservation = VehicleReservation::create([
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'reservationNumber' => $reservationNumber,
                'reservationDate' => $request->reservationDate,
                'reservationTime' => $request->reservationTime,
                'vehicle_type' => $request->vehicle_type,
                'pickUpLocation' => $request->pickUpLocation,
                'dropOffLocation' => $request->dropOffLocation,
                'assigned_to' => auth()->id(),
                'reviewed_by' => auth()->id(),
            ]);

            ActivityLogs::create([
                'user_id' => auth()->id(),
                'event' => "Submitted Vehicle Reservation: {$vehicleReservation->reservationNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            // Dispatch notification asynchronously
            SendVehicleReservationNotification::dispatch($vehicleReservation, $user);

            return redirect()->route('staff.vehicleReservation.indexOrder')
                ->with('success', 'Vehicle reservation submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    // Vechicle Reservation -> Vehicle
    public function indexVehicle()
    {
        $vehicleReservations = VehicleReservation::whereNull('order_id')
            ->where('assigned_to', auth()->user()->id)
            ->get();

        return view('modules.staff.vehicleReservation.indexVehicle', compact('vehicleReservations'));
    }

    public function detailsVehicle(VehicleReservation $vehicleReservation)
    {
        return view('modules.staff.vehicleReservation.detailsVehicle', compact('vehicleReservation'));
    }

    public function reviewVehicle(Request $request, VehicleReservation $vehicleReservation)
    {
        $vehicleReservation->update([
            'approval_status' => 'reviewed',
            'reviewed_by' => auth()->id(),
        ]);

        ActivityLogs::create([
            'user_id' => auth()->id(),
            'event' => "Approved Vehicle Reservation: {$vehicleReservation->reservationNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => $request->ip(),
        ]);

        // Dispatch notification asynchronously
        SendVehicleReservationNotification::dispatch($vehicleReservation);


        return redirect()->route('staff.vehicleReservation.indexVehicle')
            ->with('success', 'Vehicle reservation approved successfully.');
    }

    public function rejectVehicle(Request $request, VehicleReservation $vehicleReservation)
    {
        $vehicleReservation->update([
            'reviewed_by' => auth()->id(),
        ]);

        ActivityLogs::create([
            'user_id' => auth()->id(),
            'event' => "Rejected Vehicle Reservation: {$vehicleReservation->reservationNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => $request->ip(),
        ]);

        // Dispatch notification asynchronously
        SendVehicleReservationRejectionNotification::dispatch($vehicleReservation);

        return redirect()->route('staff.vehicleReservation.indexVehicle')
            ->with('success', 'Vehicle reservation rejected successfully.');
    }


    // Admin 
    public function indexAdmin()
    {
        $vehicleReservations = VehicleReservation::all();
        return view('modules.admin.vehicleReservation.manage', compact('vehicleReservations'));
    }

    public function showAdmin(VehicleReservation $vehicleReservation)
    {
        return view('modules.admin.vehicleReservation.show', compact('vehicleReservation'));
    }

    public function approve($id)
    {

        $vehicleReservation = VehicleReservation::findOrFail($id);
        $order = Order::findOrFail($vehicleReservation->order_id);

        // Check if orderNumber is null
        if ($order->orderNumber) {
            // Find an available vehicle that matches all conditions
            $vehicle = Vehicle::where('vehicleType', $vehicleReservation->vehicle_type)
                ->where('vehicleStatus', 'available')
                ->where('vehicleCapacity', '>=', $order->weight)
                ->first();

            // Find an available driver that matches all conditions
            $driver = User::role('Driver')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Driver')
                    ->where('last_active_at', '>=', now()->subMinutes(5));
            })
            ->where('driverType', $vehicleReservation->vehicle_type)
            ->where('status', 'available')
            ->where(function ($query) {
                $query->whereNull('restricted_until')
                    ->orWhere('restricted_until', '<', now());
            })
            ->orderByRaw('CASE WHEN performance_score > 60 THEN 1 ELSE 2 END, last_active_at DESC')
            ->limit(1)
            ->first();


            if ($vehicle && $driver) {
                $vehicleReservation->update([
                    'approval_status' => 'approved',
                    'vehicle_id' => $vehicle->id,
                    'approved_by' => Auth::id(),
                    'redirected_to' => $driver->id
                ]);

                $vehicle->update([
                    'vehicleStatus' => 'unavailable',
                ]);

                $driver->update([
                    'status' => 'scheduled',
                ]);

                $order->update([
                    'approval_status' => 'approved',
                    'approved_by' => Auth::id(),
                    'redirected_to' => Auth::id()

                ]);

                $order->payment()->update([
                    'approval_status' => 'approved',
                    'approved_by' => Auth::id(),
                    'redirected_to' => Auth::id()
                ]);

                $order->document()->update([
                    'approval_status' => 'approved',
                    'approved_by' => Auth::id(),
                    'redirected_to' => Auth::id()
                ]);


            } else {
                return back()->with('error', 'No available vehicle or driver found that meets the requirements.');
            }
        } else {
            // If orderNumber exists, ensure vehicle_id is assigned
            if (!$vehicleReservation->vehicle_id) {
                return back()->with('error', 'Cannot approve reservation without an assigned vehicle.');
            }

            $vehicleReservation->update([
                'approval_status' => 'approved',
                'vehicle_id' => $vehicleReservation->vehicle_id,
                'approved_by' => Auth::id(),
            ]);
        }

        // Dispatch Notification to the assigned driver
        SendApprovalNotification::dispatch($vehicleReservation, $driver);

        // Dispatch event for vehicle reservation approval
        event(new VehicleReservationApproved($vehicleReservation));

        return redirect()->route('admin.vehicleReservation.manage')->with('success', 'Reservation approved successfully.');
    }

    public function approveVehisleReservation($vehicleReservation)
    {
        // I-schedule ang approval job sa reservation date
        // $reservationDate = Carbon::parse($vehicleReservation->reservationDate)->startOfDay();

        $vehicleReservation = VehicleReservation::findOrFail($vehicleReservation);

        VehicleReservationApproved::dispatch($vehicleReservation);

        $vehicleReservation->update([
            'approval_status' => 'scheduled',
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Scheduled Vehicle Reservation: {$vehicleReservation->reservationNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.vehicleReservation.manage')->with('success', 'Vehicle reservation approval is scheduled for the reservation date.');
    }

    public function reject(VehicleReservation $vehicleReservation)
    {
        DB::beginTransaction();

        try {
            $vehicleReservation->update([
                'approval_status' => 'rejected',
                'rejected_by' => Auth::id(),
            ]);

            $vehicleReservation->order->update([
                'approval_status' => 'rejected',
                'rejected_by' => Auth::id(),
            ]);

            $order = $vehicleReservation->order;

            $order->payment->update([
                'approval_status' => 'rejected',
                'rejected_by' => Auth::id(),
            ]);

            $order->document->update([
                'approval_status' => 'rejected',
                'rejected_by' => Auth::id(),
            ]);

            SendVehicleReservationRejectionNotification::dispatch($vehicleReservation);


            ActivityLogs::create([
                'user_id' => Auth::id(),
                'event' => "Rejected Vehicle Reservation: {$vehicleReservation->reservationNumber} in time of: " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            return redirect()->route('admin.vehicleReservation.manage')->with('success', 'Vehicle reservation rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    // Super Admin

    public function indexSA()
    {
        $vehicleReservations = VehicleReservation::all();
        return view('modules.superAdmin.vehicleReservation.index', compact('vehicleReservations'));
    }

    public function showSA(VehicleReservation $vehicleReservation)
    {
        return view('modules.superAdmin.vehicleReservation.show', compact('vehicleReservation'));
    }
}
