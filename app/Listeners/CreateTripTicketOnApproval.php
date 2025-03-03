<?php

namespace App\Listeners;

use App\Events\VehicleReservationApproved;
use App\Models\FleetCard;
use App\Models\TripTicket;
use App\Models\User;
use App\Notifications\driver\TripTicketNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateTripTicketOnApproval
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VehicleReservationApproved $event)
    {
        $reservation = $event->vehicleReservation;

        // Create the fleet card and store the reference
        $fleetCard = FleetCard::create([
            'user_id' => $reservation->redirected_to,
            'cardNumber' => Str::upper(Str::random(20)),
            'status' => 'active',
            'credit_limit' => '5000'
        ]);

        // Create a trip ticket entry with the correct fleet_card_id
       TripTicket::create([
            'user_id' => $reservation->redirected_to,
            'vehicle_id' => $reservation->vehicle_id,
            'fleet_card_id' => $fleetCard->id,
            'tripNumber' => Str::upper(Str::random(20)),
            'status' => 'scheduled',
            'destination' => $reservation->dropOffLocation,
            'departureTime' => $reservation->reservationTime,
            'arrivalTime' => Carbon::parse($reservation->reservationTime)->addHours(2),
            'allocatedFuel' => $fleetCard->credit_limit,
        ]);
           
    }
}
