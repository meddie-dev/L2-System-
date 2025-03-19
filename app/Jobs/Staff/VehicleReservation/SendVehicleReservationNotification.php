<?php

namespace App\Jobs\Staff\VehicleReservation;

use App\Models\User;
use App\Models\Modules\VehicleReservation;
use App\Notifications\admin\adminApprovalRequest;
use App\Notifications\NewNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendVehicleReservationNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $vehicleReservation;
   

    public function __construct(VehicleReservation $vehicleReservation)
    {
        $this->vehicleReservation = $vehicleReservation;
    }

    public function handle()
    {
        $admin = User::role('Admin')->first();

        if ($admin) {
            $this->vehicleReservation->redirected_to = $admin->id;
            $this->vehicleReservation->save();
        }

        $creator = User::find($this->vehicleReservation->order->user_id);

        if ($creator) {
            $creator->notify(new adminApprovalRequest('VehicleReservation', $this->vehicleReservation));
            $creator->notify(new NewNotification("Your Vehicle Reservation ({$this->vehicleReservation->reservationNumber}) has been reviewed. Please wait for approval."));
        }

    }
}