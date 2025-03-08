<?php
namespace App\Jobs\Vendor;

use App\Models\Modules\Order;
use App\Models\User;
use App\Notifications\staff\staffApprovalRequest;
use App\Notifications\NewNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $user;

    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
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
            $this->order->assigned_to = $staff->id;
            $this->order->save();

            $staff->notify(new staffApprovalRequest('Order', $this->order));
            $staff->notify(new NewNotification("Order Request by {$this->user->firstName} {$this->user->lastName} with Order Number: ({$this->order->orderNumber}). Waiting for your approval."));
        }
    }
}
