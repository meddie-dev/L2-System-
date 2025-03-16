<?php

namespace App\Jobs\Staff\Order;

use App\Models\Modules\Order;
use App\Models\User;
use App\Notifications\admin\adminApprovalRequest;
use App\Notifications\admin\adminApprovalStatus;
use App\Notifications\NewNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class SendOrderApprovalNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        $admin = User::role('Admin')->first();
        if ($admin) {
            $this->order->redirected_to = $admin->id;
            $this->order->save();
        }

        $creator = User::find($this->order->user_id);
        if ($creator) {
            $creator->notify(new adminApprovalRequest('Order', $this->order));
            $creator->notify(new NewNotification("Your order ({$this->order->orderNumber}) has been reviewed. Please wait for approval."));
        }
    }
}