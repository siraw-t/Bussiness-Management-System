<?php

namespace App\Notifications;

use App\Models\BudgetForecasting;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BudgetForecastingRequestNotification extends Notification
{
    use Queueable;

    protected $budget;

    public function __construct(BudgetForecasting $budget)
    {
        $this->budget = $budget;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // adjust channels as needed
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A new budget Forecast has been submitted.')
                    ->action('Review Request', url('/display/pending'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
{
    return [
        'budget_id' => $this->budget->id,
        'message' => 'A new budget forecast has been submitted.',
        'url' => url('/display/pending'), // Add the URL
    ];
}
}
