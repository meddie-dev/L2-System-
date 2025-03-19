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

class SendApprovalNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $vehicleReservation;
    protected $driver;

    public function __construct(VehicleReservation $vehicleReservation, $driver)
    {
        $this->vehicleReservation = $vehicleReservation->fresh();
        $this->driver = $driver;
    }

    public function handle()
    {
       $creator = User::find($this->vehicleReservation->order->user_id);
       if ($creator) {
            $creator->notify(new NewNotification("You have been assigned to a new vehicle reservation: {$this->vehicleReservation->reservationNumber}. Please check the system for details."));
            $creator->notify(new adminApprovalStatus('Reservation', $this->vehicleReservation->reservationNumber));
        }
    }
}
