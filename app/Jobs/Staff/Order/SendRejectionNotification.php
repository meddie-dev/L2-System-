<?php

namespace App\Jobs\Staff\Order;

use App\Models\Modules\Order;
use App\Models\User;
use App\Notifications\NewNotification;
use App\Notifications\staff\staffApprovalStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendRejectionNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        $user = User::find($this->order->user_id);
        if ($user) {
            $user->notify(new staffApprovalStatus('Order', $this->order));
            $user->notify(new NewNotification("Your order ({$this->order->orderNumber}) has been rejected. Please wait for a recheck."));
        }
    }
}