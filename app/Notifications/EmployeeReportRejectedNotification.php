<?php

namespace App\Notifications;

use App\Models\EmployeeReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmployeeReportRejectedNotification extends Notification
{
    use Queueable;

    protected $report;

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
                    ->line('Your weekly report has been rejected by your manager.')
                    ->action('View report', url('/weekly-reports'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'report_id' => $this->report->id,
            'message' => 'Your weekly report has been rejected.',
            'url' => url('/weekly-reports'), // Add the URL
        ];
    }
}
