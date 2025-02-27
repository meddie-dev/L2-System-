<?php

namespace App\Jobs\Staff;

use App\Models\Modules\Order;
use App\Models\User;
use App\Notifications\admin\adminApprovalRequest;
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

            $admin->notify(new adminApprovalRequest('Order', $this->order));
            $admin->notify(new NewNotification("Reviewed Order from {$this->order->user->firstName} {$this->order->user->lastName} with Order Number: ({$this->order->orderNumber}). Waiting for your approval."));
        }
    }
}