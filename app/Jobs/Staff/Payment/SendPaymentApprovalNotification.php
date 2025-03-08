<?php

namespace App\Jobs\Staff\Payment;

use App\Models\Payment;
use App\Models\User;
use App\Notifications\admin\adminApprovalRequest;
use App\Notifications\NewNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPaymentApprovalNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function handle()
    {
        $admin = User::role('Admin')->first();
        if ($admin) {
            $this->payment->redirected_to = $admin->id;
            $this->payment->save();
        }

        $creator = User::find($this->payment->user_id);
        if ($creator) {
            $creator->notify(new adminApprovalRequest('Payment', $this->payment));
            $creator->notify(new NewNotification("Your payment ({$this->payment->paymentNumber}) has been reviewed. Please wait for approval."));
        }
    }
}