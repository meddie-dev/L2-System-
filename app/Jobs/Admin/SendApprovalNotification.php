<?php

namespace App\Jobs\Admin;

use App\Models\Modules\VehicleReservation;
use App\Notifications\admin\adminApprovalRequest;
use App\Notifications\admin\adminApprovalStatus;
use App\Notifications\driver\TripTicketNotification;
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
        $this->vehicleReservation = $vehicleReservation;
        $this->driver = $driver;
    }

    public function handle()
    {
        $this->driver->notify(new NewNotification("You have been assigned to a new vehicle reservation: {$this->vehicleReservation->reservationNumber}. Please check the system for details."));

        $this->driver->notify(new adminApprovalStatus('Reservation', $this->vehicleReservation->reservationNumber));
    }
}