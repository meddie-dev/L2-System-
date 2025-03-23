<?php

namespace App\Http\Controllers;

use App\Jobs\Admin\TripTicket\SendTripDeliveredNotification;
use App\Jobs\CheckMaintenance;
use App\Jobs\Vendor\SendTripRatingNotification;
use App\Models\ActivityLogs;
use App\Models\IncidentReport;
use App\Models\Modules\VehicleReservation;
use App\Models\TripTicket;
use App\Models\User;
use App\Notifications\NewNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TripTicketController extends Controller
{

    // Driver
    public function markAsInTransit(Request $request, $id)
    {
        $trip = TripTicket::findOrFail($id);
        $trip->status = 'in_transit';
        $trip->save();


        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Trip marked as in transit at: " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => $request->ip(),
        ]);

        if ($trip->vehicleReservation && $trip->vehicleReservation->order_id) {
            $creator = User::find($trip->order->user_id);

            if ($creator) {
                $creator->notify(new NewNotification("Your trip ({$trip->tripNumber}) has been marked as in transit. Check trip details."));
            }
        }else
        {
            $trip->user->notify(new NewNotification("Your trip ({$trip->tripNumber}) has been marked as in transit. Check trip details."));
        }

        return redirect()->back()->with(['success' => 'Trip marked as in transit.']);
    }

    public function markAsDelivered(Request $request, $id)
    {
        $trip = TripTicket::findOrFail($id);

        if ($trip->status !== 'in_transit') {
            return redirect()->back()->with(['success' => false, 'message' => 'Trip must be in transit before marking as delivered.']);
        }

        if (!$trip->arrivalTime) {
            return redirect()->back()->with(['success' => false, 'message' => 'Arrival time is missing.']);
        }

        $now = Carbon::now('Asia/Manila');
        $scheduledArrival = $trip->arrivalTime;
        $delayMinutes = -$now->diffInMinutes($scheduledArrival, false);

        // Check for valid incident reports
        $incidentReport = IncidentReport::where('trip_ticket_id', $trip->id)
            ->where('approval_status', 'approved')
            ->first();

        $isLate = ($delayMinutes > 15);
        if ($incidentReport && $isLate) {
            $isLate = false; // Do not penalize if there's a valid incident report
        }

        $trip->status = 'delivered';
        $trip->delivered_at = $now;
        $trip->delay_minutes = $delayMinutes;
        $trip->save();

        $driver = $trip->user;
        if ($driver) {
            if ($isLate) {
                $driver->late_deliveries += 1;
                $driver->applyLateDeliveryPenalty(); // Apply restriction separately
            } elseif ($delayMinutes < -10) {
                $driver->early_deliveries += 1;
            } else {
                $driver->on_time_deliveries += 1;
            }

            $driver->save();
        }

        if ($vehicle = $trip->vehicle) {
            $vehicle->update(['vehicleStatus' => 'available']);
            CheckMaintenance::dispatch($vehicle->id);
        }

        if ($driver) {
            $driver->status = 'available';
            $driver->save();
        }

        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Trip marked as delivered at: " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => $request->ip(),
        ]);

        SendTripDeliveredNotification::dispatch($trip);

        return redirect()->back()->with([
            'success' => 'Trip marked as delivered.',
        ]);
    }

    public function makeRate(Request $request, $id)
    {
        $tripTicket = TripTicket::findOrFail($id);
        return view('modules.vendor.cards.rate.create', compact('tripTicket'));
    }

    public function rateTrip(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $trip = TripTicket::findOrFail($id);

            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'feedback' => 'nullable|string|max:1000'
            ]);

            $trip->update([
                'rating' => $validated['rating'],
                'feedback' => $validated['feedback'],
            ]);

            // Update driver's performance score
            if ($trip->user) {
                $trip->user->calculatePerformance();
                $trip->user->save();
            }

            DB::commit();

            // Dispatch Notification to Driver
            SendTripRatingNotification::dispatch($trip);

            return redirect()->route('vendorPortal.dashboard')->with([
                'success' => 'Trip rated successfully.',
                'new_performance_score' => $trip->user->performance_score ?? 'N/A'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    // Vendor

    public function vendorInTransitIndex()
    {
        $userOrderIds = Auth::user()->order()->pluck('id');

        $tripTickets = TripTicket::whereIn('order_id', $userOrderIds)->with('order')->get();
        return view('modules.vendor.cards.inTransit.index', compact('tripTickets'));
    }

    public function vendorScheduledIndex()
    {
        $userOrderIds = Auth::user()->order()->pluck('id');

        $tripTickets = TripTicket::whereIn('order_id', $userOrderIds)->with('order')->get();
        return view('modules.vendor.cards.schedule.index', compact('tripTickets'));
    }

    public function vendorDeliveredIndex()
    {
        $userOrderIds = Auth::user()->order()->pluck('id');

        $tripTickets = TripTicket::whereIn('order_id', $userOrderIds)->with('order')->get();

        return view('modules.vendor.cards.delivered.index', compact('tripTickets'));
    }

    public function vendorInTransitDetails($id)
    {
        $tripTicket = TripTicket::findOrFail($id);
        return view('modules.vendor.cards.inTransit.details', compact('tripTicket'));
    }
}
