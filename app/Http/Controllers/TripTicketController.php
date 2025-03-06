<?php

namespace App\Http\Controllers;

use App\Models\Modules\Order;
use App\Models\TripTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripTicketController extends Controller
{
    public function markAsInTransit(Request $request, $id)
    {
        $trip = TripTicket::findOrFail($id);
        $trip->status = 'in_transit';
        $trip->save();
        return redirect()->back()->with(['success' => 'Trip marked as in transit.']);
    }

    public function markAsDelivered(Request $request, $id)
    {

        $trip = TripTicket::findOrFail($id);

        if ($trip->status !== 'in_transit') {
            return redirect()->back()->with(['success' => false, 'message' => 'Trip must be in transit before marking as delivered.']);
        }

        $now = Carbon::now();

        // Ensure arrivalTime is valid before parsing
        if (!$trip->arrivalTime) {
            return redirect()->back()->with(['success' => false, 'message' => 'Arrival time is missing.']);
        }

        $scheduledArrival = Carbon::parse($trip->arrivalTime);
        $actualArrival = $now;
        $delayMinutes = $actualArrival->diffInMinutes($scheduledArrival, false); // false keeps the sign (negative if early)

        // Update trip details
        $trip->status = 'delivered';
        $trip->delivered_at = $now;
        $trip->delay_minutes = $delayMinutes; // Store delay for reporting
        $trip->save();

        // Update driver performance
        $driver = $trip->user;
        if ($driver) {
            if ($delayMinutes > 15) {
                $driver->late_deliveries += 1;
            } elseif ($delayMinutes < -10) { // Consider early deliveries as well
                $driver->early_deliveries += 1;
            } else {
                $driver->on_time_deliveries += 1;
            }

            $driver->calculatePerformance(); // Ensure this method properly recalculates scores
            $driver->save();
        }

        return redirect()->back()->with([
            'success' => true,
            'message' => 'Trip marked as delivered.',
            'performance_score' => $driver->performance_score ?? 'N/A'
        ]);
    }

    public function makeRate(Request $request, $id)
    {
        $tripTicket = TripTicket::findOrFail($id);
        return view('modules.vendor.cards.rate.create', compact('tripTicket'));
    }

    public function rateTrip(Request $request, $id)
    {
        $trip = TripTicket::findOrFail($id);
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string'
        ]);

        $trip->rating = $request->rating;
        $trip->feedback = $request->feedback;
        $trip->save();

        $trip->user->calculatePerformance();

        return redirect()->back()->with(['success' => 'Trip rated successfully.', 'new_performance_score' => $trip->user->performance_score]);
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
