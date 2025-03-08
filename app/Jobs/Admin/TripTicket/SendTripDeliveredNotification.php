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
        
        $user = User::find($this->trip->order->user_id);

        if ($user) {
            $user->notify(new NewNotification("Order ({$this->trip->order->orderNumber}) has been delivered. Check trip details."));
        }
    }
}