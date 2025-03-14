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

class SendVehicleReservationNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $vehicleReservation;
    protected $user;

    public function __construct(VehicleReservation $vehicleReservation, User $user)
    {
        $this->vehicleReservation = $vehicleReservation;
        $this->user = $user;
    }

    public function handle()
    {
        $activeAdmins = User::role('Admin')->get();
        foreach ($activeAdmins as $admin) {
            $admin->notify(new adminApprovalRequest('VehicleReservation', $this->vehicleReservation));
            $admin->notify(new NewNotification("Vehicle Reservation by {$this->user->firstName} {$this->user->lastName} with Reservation Number: ({$this->vehicleReservation->reservationNumber}). Waiting for your approval."));
        }
    }
}