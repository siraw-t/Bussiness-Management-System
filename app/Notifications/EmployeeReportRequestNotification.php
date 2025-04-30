<?php

namespace App\Notifications;

use App\Models\EmployeeReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmployeeReportRequestNotification extends Notification
{
    use Queueable;

    protected $plan;

    public function __construct(EmployeeReport $report)
    {
        $this->report = $report;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // adjust channels as needed
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A new employee report request has been submitted.')
                    ->action('Review Request', url('/display/pending'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'report_id' => $this->report->id,
            'message' => 'A new employee report request has been submitted.',
            'url' => url('/display/pending'), // Add the URL
        ];
    }
}
