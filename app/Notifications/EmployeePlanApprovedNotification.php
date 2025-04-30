<?php

namespace App\Notifications;

use App\Models\EmployeePlan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmployeePlanApprovedNotification extends Notification
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
                    ->line('Your weekly plan has been approved by your manager.')
                    ->action('View Plan', url('/weekly-plan'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'plan_id' => $this->plan->id,
            'message' => 'Your weekly plan has been approved.',
            'url' => url('/weekly-plan'), // Add the URL
        ];
    }
}
