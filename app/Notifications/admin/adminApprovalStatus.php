<?php

namespace App\Notifications\admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class adminApprovalStatus extends Notification
{
    use Queueable;

    protected $approvalType;
    protected $approvable;

    /**
     * Create a new notification instance.
     */
    public function __construct($approvalType, $approvable)
    {
        $this->approvalType = $approvalType;
        $this->approvable = $approvable;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Check the approval type to generate the correct message
        $type = $this->approvalType;
        $approvable = $this->approvable;

        $message = "A new {$type} review ({$approvable->id}) requires approval.";

        // Customize further based on the approval type
        switch ($type) {
            case 'Order':
                $message = "Order Number: ({$approvable->orderNumber}) is " . ucfirst($approvable->approval_status) . " by " . ($approvable->reviwed_by == $approvable->user->id ? $approvable->user->firstName . ' ' . $approvable->user->lastName : 'our monitoring team, please wait for rechecking') . ".";
                break;

            case 'Document':
                $message = "Document Number: ({$approvable->documentNumber}) is " . ucfirst($approvable->approval_status) . " by " . ($approvable->reviwed_by == $approvable->user->id ? $approvable->user->firstName . ' ' . $approvable->user->lastName : 'our monitoring team, please wait for rechecking') . ".";
                break;

            case 'Payment':
                $message = "Payment Number: ({$approvable->paymentNumber}) is " . ucfirst($approvable->approval_status) . " by " . ($approvable->reviwed_by == $approvable->user->id ? $approvable->user->firstName . ' ' . $approvable->user->lastName : 'our monitoring team, please wait for rechecking') . ".";
                break;

            case 'Vehicle Reservation':
                $message = "Reservation Number: {$approvable->reservationNumber} is " . ucfirst($approvable->approval_status) . " by " . ($approvable->assigned_to == $approvable->user->id ? $approvable->user->firstName . ' ' . $approvable->user->lastName : 'our monitoring team, please wait for rechecking') . ".";
                break;

            case 'Reservation':
                $message = "You are Assigned to Reservation Number: {$approvable->reservationNumber} has been {$approvable->approval_status}. Prepare for your Journey, please check the system for details.";
                break;
            case 'Incident':
                $message = "Report Number: ({$approvable->reportNumber}) has been {$approvable->approval_status}.";
                break;
        }

        return (new MailMessage)
            ->subject("{$type} Approval Status")
            ->line($message)
            ->line('Thank you for your patience and understanding.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
