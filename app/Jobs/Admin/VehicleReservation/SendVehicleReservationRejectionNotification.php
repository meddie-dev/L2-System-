<?php

namespace App\Jobs\Admin\VehicleReservation;

use App\Models\Modules\VehicleReservation;
use App\Models\User;
use App\Notifications\admin\adminApprovalStatus;
use App\Notifications\NewNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendVehicleReservationRejectionNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $vehicleReservation;

    public function __construct(VehicleReservation $vehicleReservation)
    {
        $this->vehicleReservation = $vehicleReservation;
    }

    public function handle()
    {
        $user = User::find($this->vehicleReservation->user_id);
        if ($user) {
            $user->notify(new adminApprovalStatus('Vehicle Reservation', $this->vehicleReservation));
            $user->notify(new NewNotification("Your vehicle reservation ({$this->vehicleReservation->reservationNumber}) has been rejected. Please review and resubmit if necessary."));
        }
    }
}