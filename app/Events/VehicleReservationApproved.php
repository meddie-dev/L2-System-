<?php

namespace App\Events;

use App\Models\Modules\VehicleReservation;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VehicleReservationApproved
{
    use Dispatchable, SerializesModels;

    public $vehicleReservation;

    public function __construct(VehicleReservation $vehicleReservation)
    {
        $this->vehicleReservation = $vehicleReservation;
    }
}
