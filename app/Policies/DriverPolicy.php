<?php

namespace App\Policies;

use App\Models\ActivityLogs;
use App\Models\FleetCard;
use App\Models\Modules\VehicleReservation;
use App\Models\TripTicket;
use App\Models\User;

class DriverPolicy
{
    public function viewVehicleReservation(User $user, VehicleReservation $vehicleReservation): bool
    {
        if ($user->id !== $vehicleReservation->redirected_to) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unowned File at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }

    
    public function viewIncidentReport(User $user, VehicleReservation $vehicleReservation): bool
    {
        if ($user->id !== $vehicleReservation->user_id) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unowned File at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }

    
    public function viewTripTicket(User $user, TripTicket $tripTicket): bool
    {
        if ($user->id !== $tripTicket->user_id) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unowned File at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }

    
    public function viewFleetCard(User $user, FleetCard $fleetCard): bool
    {
        if ($user->id !== $fleetCard->user_id) {
            ActivityLogs::create([
                'user_id' => $user->id,
                'event' => "Tried to view Unowned File at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
            return false;
        }
        return true;
    }
}
