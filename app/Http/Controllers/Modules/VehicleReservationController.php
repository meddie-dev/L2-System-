<?php

namespace App\Http\Controllers\Modules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Vendor\SendVehicleReservationNotifications;
use App\Models\ActivityLogs;
use App\Models\Modules\Order;
use App\Models\Modules\VehicleReservation;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\NewNotification;
use App\Notifications\staff\staffApprovalRequest;
use App\Notifications\staff\staffApprovalStatus;
use Carbon\Carbon;
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
                'reviewed_by' => null,
                'approved_by' => null,
                'rejected_by' => null,
                'assigned_to' => null,
                'redirected_to' => null,
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
        $orders = Order::where('assigned_to', auth()->user()->id)
            ->where('approval_status', 'approved')
            ->whereHas('payment', function ($q) {
                $q->where('approval_status', 'approved');
            })
            ->whereHas('document', function ($q) {
                $q->where('approval_status', 'approved');
            })
            ->get();
    
        return view('modules.staff.vehicleReservation.indexOrder', compact('orders'));
    }
    

    public function createOrder(Order $order)
    {
        return view('modules.staff.vehicleReservation.createOrder', compact('order'));
    }

    public function storeOrder(Request $request,User $user, Order $order)
    {
        $request->validate([
            'reservationNumber' => 'required|string|max:255',
            'reservationDate' => 'required|date',
            'reservationTime' => 'required|date_format:H:i',
            'vehicle_type' => 'required|string|max:255',
            'pickUpLocation' => 'required|string|max:255',
            'dropOffLocation' => 'required|string|max:255',
        ]);

        $checkReservationNumber = VehicleReservation::where('reservationNumber', $request->reservationNumber)->first();
        if ($checkReservationNumber) {
            $reservationNumber = strtoupper(Str::random(20));
        } else {
            $reservationNumber = $request->reservationNumber;
        }

        $vehicleReservation = VehicleReservation::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'reservationNumber' => $reservationNumber,
            'reservationDate' => $request->reservationDate,
            'reservationTime' => $request->reservationTime,
            'vehicle_type' => $request->vehicle_type,
            'pickUpLocation' => $request->pickUpLocation,
            'dropOffLocation' => $request->dropOffLocation,
        ]);

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Submitted Vehicle Reservation: {$vehicleReservation->reservation_number} in time of: " . now('Asia/Manila')->format('Y-m-d H:i'),
            'ip_address' => $request->ip(),
        ]);

        // Get active admin 
        $activeAdmins = User::role('Admin')->where('last_active_at', '>=', Carbon::now()->subMinutes(5))
            ->orderBy('last_active_at', 'desc')->limit(1)->get();

        foreach ($activeAdmins as $admin) {
            $vehicleReservation->assigned_to = $admin->id;
            $vehicleReservation->save();
            $admin->notify(new staffApprovalRequest('VehicleReservation', $vehicleReservation));
            $admin->notify(new NewNotification("Vehicle Reservation from {$user->firstName} {$user->lastName} with Reservation Number: ({$vehicleReservation->reservationNumber}). Waiting for your approval."));
        }


        return redirect()->route('staff.vehicleReservation.indexOrder')->with('success', 'Vehicle reservation submitted successfully.');
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

    public function showAdmin(VehicleReservation $vehicleReservation) {
        return view('modules.admin.vehicleReservation.show', compact('vehicleReservation'));
    }

    public function approve(VehicleReservation $vehicleReservation)
    {
        if (!$vehicleReservation->order->orderNumber) {
            $vehicle = Vehicle::where('vehicleType', $vehicleReservation->vehicle_type)
                ->where('vehicleCapacity', $vehicleReservation->vehicle_capacity)
                ->whereHas('vehicleReservations', function ($q) use ($vehicleReservation) {
                    $q->where('reservationDate', $vehicleReservation->reservationDate)
                        ->where('reservationTime', '>=', $vehicleReservation->reservationTime)
                        ->where('vehicleStatus', '!=', 'scheduled');
                }, '=', 0)
                ->first();

            if ($vehicle) {
                $vehicleReservation->update(['approval_status' => 'approved', 'vehicle_id' => $vehicle->id]);
                $vehicleReservation->approved_by = Auth::id();
                $vehicleReservation->save();
            }
        } else {
            $vehicleReservation->update(['approval_status' => 'approved']);
            $vehicleReservation->approved_by = Auth::id();
            $vehicleReservation->save();
        }
        
        $vehicleReservation->notify(new staffApprovalStatus('Vehicle reservation', $vehicleReservation));
        return redirect()->route('admin.vehicleReservation.manage')->with('success', 'Vehicle reservation approved successfully.');
    }
}
