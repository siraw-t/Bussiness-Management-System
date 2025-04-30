<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Low Stock Alert')
                    ->line($this->data['message'])
                    ->action('View Inventory', url('/inventory'));
    }

    public function toArray($notifiable)
{
    return [
        'requisition_id' => $this->data['id'], // Changed to array access
        'message' => $this->data['message'],
        'url' => url('/inventory'),
    ];
}
}