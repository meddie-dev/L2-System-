<?php

namespace App\Notifications\staff;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class staffApprovalRequest extends Notification
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
    public function via( object $notifiable): array
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

        $message = "A new {$type} request ({$approvable->id}) requires approval.";

        // Customize further based on the approval type
        switch ($type) {
            case 'Order':
                $message = "Order request from {$approvable->user->firstName} {$approvable->user->lastName} with Order Number: ({$approvable->orderNumber}) is awaiting for review and approval.";
                break;

            case 'Document':
                $message = "Document Uploaded by {$approvable->user->firstName} {$approvable->user->lastName} with Document Number: ({$approvable->documentNumber}) is awaiting for review and approval.";
                break;

            case 'Payment':
                $message = "Payment by {$approvable->user->firstName} {$approvable->user->lastName} with Payment Number: ({$approvable->paymentNumber}) is awaiting for review and approval.";
                break;

            case 'Vehicle Reservation':
                $message = "Vehicle reservation request by {$approvable->user->firstName} {$approvable->user->lastName} on Request Date and Time: {$approvable->reservationDate}, {$approvable->reservationTime} is awaiting for review and approval.";
                break;

                // Add more cases for other types if needed
        }

        return (new MailMessage)
            ->subject("{$type} Approval Request")
            ->line($message)
            ->line('Please review and take action.');
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
