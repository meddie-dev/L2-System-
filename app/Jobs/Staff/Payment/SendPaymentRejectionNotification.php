<?php

namespace App\Jobs\Staff\Payment;

use App\Models\Payment;
use App\Models\User;
use App\Notifications\NewNotification;
use App\Notifications\staff\staffApprovalStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPaymentRejectionNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function handle()
    {
        $user = User::find($this->payment->user_id);
        if ($user) {
            $user->notify(new staffApprovalStatus('Payment', $this->payment));
            $user->notify(new NewNotification("Your payment ({$this->payment->paymentNumber}) has been rejected. Please review and resubmit."));
        }
    }
}