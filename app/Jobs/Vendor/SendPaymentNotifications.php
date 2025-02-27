<?php

namespace App\Jobs\Vendor;

use App\Models\Payment;
use App\Models\User;
use App\Notifications\staff\staffApprovalRequest;
use App\Notifications\NewNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPaymentNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payment;
    protected $user;

    public function __construct(Payment $payment, User $user)
    {
        $this->payment = $payment;
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
            $this->payment->assigned_to = $staff->id;
            $this->payment->save();

            $staff->notify(new staffApprovalRequest('Payment', $this->payment));
            $staff->notify(new NewNotification("Payment by {$this->user->firstName} {$this->user->lastName} with Payment Number: ({$this->payment->paymentNumber}). Waiting for your approval."));
        }

        // Notify user
        $this->user->notify(new NewNotification("Your order ({$this->payment->order->orderNumber}) has been submitted. Please wait for approval."));
    }
}
