<?php

namespace App\Jobs\Vendor;

use App\Models\TripTicket;
use App\Notifications\NewNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTripRatingNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $trip;

    public function __construct(TripTicket $trip)
    {
        $this->trip = $trip;
    }

    public function handle()
    {
        $driver = $this->trip->user;

        if ($driver) {
            $driver->notify(new NewNotification("Your trip ({$this->trip->tripNumber}) has been rated {$this->trip->rating} stars. Check your performance updates."));
        }
    }
}