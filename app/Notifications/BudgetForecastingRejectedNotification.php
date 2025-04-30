<?php

namespace App\Notifications;

use App\Models\BudgetForecasting;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BudgetForecastingRejectedNotification extends Notification
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
                    ->line('Your budget Forecast has been rejected by your manager.')
                    ->action('View budget', url('/finance/budgets'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
{
    return [
        'budget_id' => $this->budget->id,
        'message' => 'Your budget forecast has been rejected.',
        'url' => url('/finance/budgets'), // Add the URL
    ];
}
}
