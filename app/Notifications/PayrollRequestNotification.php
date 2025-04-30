<?php

namespace App\Notifications;

use App\Models\Payroll;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PayrollRequestNotification extends Notification
{
    use Queueable;

    protected $payroll;

    public function __construct(Payroll $payroll)
    {
        $this->payroll = $payroll;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // adjust channels as needed
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A new Payroll has been submitted.')
                    ->action('Review Request', url('/display/pending'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'payroll_id' => $this->payroll->id,
            'message' => 'A new payroll has been submitted.',
            'url' => url('/display/pending'), // Add the URL
        ];
    }
}
