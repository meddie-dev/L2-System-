<?php

namespace App\Jobs\Admin\TripTicket;

use App\Models\TripTicket;
use App\Models\User;
use App\Notifications\NewNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTripDeliveredNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $trip;

    public function __construct(TripTicket $trip)
    {
        $this->trip = $trip;
    }

    public function handle()
    {
        
        if ($this->trip->vehicleReservation && $this->trip->vehicleReservation->order_id) {
            $creator = User::find($this->trip->order->user_id);
            if ($creator) {
                $creator->notify(new NewNotification("Your trip ({$this->trip->tripNumber}) has been marked as in transit. Check trip details."));
            }
        }else {
            $this->trip->user->notify(new NewNotification("Your trip ({$this->trip->tripNumber}) has been marked as in transit. Check trip details."));
        }
    }
}