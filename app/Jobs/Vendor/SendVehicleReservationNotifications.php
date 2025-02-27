<?php

namespace App\Jobs\Vendor;

use App\Models\Modules\VehicleReservation;
use App\Models\User;
use App\Notifications\staff\staffApprovalRequest;
use App\Notifications\NewNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendVehicleReservationNotifications implements ShouldQueue
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
        $activeStaffs = User::role('Staff')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Staff')
                    ->where('last_active_at', '>=', now()->subMinutes(5));
            })
            ->orderBy('last_active_at', 'desc')
            ->limit(1)
            ->get();

        if ($activeStaffs->isEmpty()) {
            $activeStaffs = User::role('Admin')->get();
        }

        foreach ($activeStaffs as $staff) {
            $this->vehicleReservation->assigned_to = $staff->id;
            $this->vehicleReservation->save();

            $staff->notify(new staffApprovalRequest('VehicleReservation', $this->vehicleReservation));
            $staff->notify(new NewNotification("Vehicle Reservation from {$this->user->firstName} {$this->user->lastName} with Reservation Number: ({$this->vehicleReservation->reservationNumber}). Waiting for your approval."));
        }
    }
}
