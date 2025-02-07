<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class StockOutNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $inventory;

    public function __construct($inventory)
    {
        $this->inventory = $inventory;
    }

    // Specify the channels for the notification
    public function via($notifiable)
    {
        return ['database']; // Store in the database
    }

    // For database notifications, define how it should be represented
    public function toArray($notifiable)
    {
        return [
            'item_name' => $this->inventory->item_name,
            'issued_quantity' => $this->inventory->issued_quantity,
            'message' => 'You can take the stock you requested: ' . $this->inventory->item_name,
        ];
    }
}