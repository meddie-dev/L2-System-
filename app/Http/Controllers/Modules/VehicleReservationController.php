<?php

namespace App\Http\Controllers\Modules;

use App\Events\VehicleReservationApproved;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Admin\GenerateTripTicketPDF;
use App\Jobs\Admin\SendApprovalNotification;
use App\Jobs\Staff\SendVehicleReservationNotification;
use App\Jobs\Vendor\SendVehicleReservationNotifications;
use App\Models\ActivityLogs;
use App\Models\Modules\Order;
use App\Models\Modules\VehicleReservation;
use App\Models\User;
use App\Models\Vehicle;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VehicleReservationController extends Controller
{
    public function vendorIndex()
    {
        $vehicleReservation = VehicleReservation::where('user_id', Auth::id())->get();
        return view('modules.vendor.vehicleReservation.index', compact('vehicleReservation'));
    }

    public function vendorCreate(User $user)
    {
        return view('modules.vendor.vehicleReservation.create', compact('user'));
    }

    public function vendorStore(Request $request, User $user, Order $order)
    {
        $request->validate([
            'reservationNumber' => 'required|string|max:255',
            'reservationDate' => 'required|date',
            'reservationTime' => 'required|date_format:H:i',
            'vehicle_type' => 'required|string|max:255',
            'pickUpLocation' => 'required|string|max:255',
            'dropOffLocation' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $checkReservationNumber = VehicleReservation::where('reservationNumber', $request->reservationNumber)->first();
            $reservationNumber = $checkReservationNumber ? strtoupper(Str::random(20)) : $request->reservationNumber;

            $order = Order::create([
                'user_id' => $user->id,
            ]);

            $vehicleReservation = VehicleReservation::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'reservationNumber' => $reservationNumber,
                'vehicle_type' => $request->vehicle_type,
                'reservationDate' => $request->reservationDate,
                'reservationTime' => $request->reservationTime,
                'pickUpLocation' => $request->pickUpLocation,
                'dropOffLocation' => $request->dropOffLocation,
                'approval_status' => 'pending',
            ]);

            ActivityLogs::create([
                'user_id' => auth()->id(),
                'event' => "Submitted Vehicle Reservation: {$vehicleReservation->reservationNumber} at " . now('Asia/Manila')->format('Y-m-d H:i'),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            // Dispatch job asynchronously
            SendVehicleReservationNotifications::dispatch($vehicleReservation, $user);

            return redirect()->route('vendorPortal.vehicleReservation')->with('success', 'Vehicle reservation submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
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
        ]);

        try {
            $vehicleReservation->update([
                'reservationNumber' => $request->reservationNumber,
                'reservationDate' => $request->reservationDate,
                'reservationTime' => $request->reservationTime,
                'vehicle_type' => $request->vehicle_type,
                'pickUpLocation' => $request->pickUpLocation,
                'dropOffLocation' => $request->dropOffLocation,
            ]);

            ActivityLogs::create([
                'user_id' => Auth::id(),
                'event' => "Updated Vehicle Reservation: {$vehicleReservation->reservation_number} in time of: " . now('Asia/Manila')->format('Y-m-d H:i'),
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('vendorPortal.vehicleReservation')->with('success', 'Vehicle reservation updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
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

    // Staff

    public function indexOrder()
    {
        $orders = Order::where('assigned_to', auth()->id())
            ->where('approval_status', 'reviewed')
            ->whereHas('payment', function ($q) {
                $q->where('approval_status', 'reviewed');
            })
            ->whereHas('document', function ($q) {
                $q->where('approval_status', 'reviewed');
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
                'event' => "Submitted Vehicle Reservation: {$vehicleReservation->reservationNumber} at " . now('Asia/Manila')->format('Y-m-d H:i'),
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


    public function indexVehicle()
    {
        $vehicleReservations = VehicleReservation::where('assigned_to', auth()->user()->id)->get();

        return view('modules.staff.vehicleReservation.indexVehicle', compact('vehicleReservations'));
    }

    public function detailsVehicle(VehicleReservation $vehicleReservation)
    {
        return view('modules.staff.vehicleReservation.detailsVehicle', compact('vehicleReservation'));
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
                ->orderBy('last_active_at', 'desc')
                ->limit(1)
                ->first();;

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
                    'status' => 'unavailable',
                ]);

                $order->update([
                    'approval_status' => 'approved'
                ]);

                $order->payment()->update([
                    'approval_status' => 'approved'
                ]);

                $order->document()->update([
                    'approval_status' => 'approved'
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
}
