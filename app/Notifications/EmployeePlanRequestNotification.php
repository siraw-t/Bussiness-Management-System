<?php

namespace App\Notifications;

use App\Models\EmployeePlan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmployeePlanRequestNotification extends Notification
{
    use Queueable;

    protected $plan;

    public function __construct(EmployeePlan $plan)
    {
        $this->plan = $plan;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // adjust channels as needed
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A new employee plan request has been submitted.')
                    ->action('Review Request', url('/display/pending'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'plan_id' => $this->plan->id,
            'message' => 'A new employee plan request has been submitted.',
            'url' => url('/display/pending'), // Add the URL
        ];
    }
}
