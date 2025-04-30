<?php

namespace App\Notifications;

use App\Models\PurchaseRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PurchaseRequestRejectedNotification extends Notification
{
    use Queueable;

    protected $purchaseRequest;

    public function __construct(PurchaseRequest $purchaseRequest)
    {
        $this->purchaseRequest = $purchaseRequest;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // adjust channels as needed
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Your purchase request has been rejected by your manager.')
                    ->action('View purchase', url('/purchase-requests'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'purchase_request_id' => $this->purchaseRequest->id,
            'message' => 'Your purchase request has been rejected.',
            'url' => url('/purchase-requests'), // Add the URL
        ];
    }
}
