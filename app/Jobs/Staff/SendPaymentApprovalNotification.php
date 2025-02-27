<?php

namespace App\Jobs\Staff;

use App\Models\Payment;
use App\Models\User;
use App\Notifications\admin\adminApprovalRequest;
use App\Notifications\admin\adminApprovalStatus;
use App\Notifications\NewNotification;
use App\Notifications\staffApprovalStatus;
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

            $admin->notify(new adminApprovalRequest('Payment', $this->payment));
            $admin->notify(new NewNotification("Reviewed Payment from {$this->payment->user->firstName} {$this->payment->user->lastName} with Payment Number: ({$this->payment->paymentNumber}). Waiting for your approval."));
        }
    }
}