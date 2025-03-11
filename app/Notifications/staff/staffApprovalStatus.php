<?php

namespace App\Notifications\staff;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class staffApprovalStatus extends Notification
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

        $message = "A new {$type} request ({$approvable->id}) requires approval.";

        // Customize further based on the approval type
        switch ($type) {
            case 'Order':
                $message = "Order number ({$approvable->orderNumber}) is " . strtolower($approvable->approval_status) . " by " . ($approvable->assigned_to == $approvable->user->id ? $approvable->user->firstName . ' ' . $approvable->user->lastName : 'our monitoring team, please wait for rechecking') . ".";
                break;

            case 'Document':
                $message = "Document Number: ({$approvable->documentNumber}) is " . strtoupper($approvable->approval_status) . " by " . ($approvable->assigned_to == $approvable->user->id ? $approvable->user->firstName . ' ' . $approvable->user->lastName : 'Our Monitoring Team, Please wait for rechecking');
                break;

            case 'Payment':
                $message = "Payment Number: ({$approvable->paymentNumber}) is " . strtoupper($approvable->approval_status) . " by " . ($approvable->assigned_to == $approvable->user->id ? $approvable->user->firstName . ' ' . $approvable->user->lastName : 'Our Monitoring Team, Please wait for rechecking');
                break;

            case 'Vehicle Reservation':
                $message = "Reservation Number: {$approvable->reservationNumber} is " . strtoupper($approvable->approval_status) . " by " . ($approvable->assigned_to == $approvable->user->id ? $approvable->user->firstName . ' ' . $approvable->user->lastName : 'Our Monitoring Team, Please wait for rechecking');
                break;

            case 'Incident':
                $message = "Incident Number: {$approvable->incidentNumber} is " . strtoupper($approvable->approval_status) . " by " . ($approvable->assigned_to == $approvable->user->id ? $approvable->user->firstName . ' ' . $approvable->user->lastName : 'Our Monitoring Team, Please wait for rechecking');

                // Add more cases for other types if needed
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
