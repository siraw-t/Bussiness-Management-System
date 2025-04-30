<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LeaveRequestNotification extends Notification
{
    use Queueable;

    protected $leaveRequest;

    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // adjust channels as needed
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A new leave request has been submitted.')
                    ->action('Review leave', url('/display/pending'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'leave_request_id' => $this->leaveRequest->id,
            'message' => 'A new leave request has been submitted.',
            'url' => url('/display/pending'), // Add the URL
        ];
    }
}
