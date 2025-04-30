<?php

namespace App\Notifications;

use App\Models\PaymentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentRequestApprovedNotification extends Notification
{
    use Queueable;

    protected $paymentRequest;

    public function __construct(PaymentRequest $paymentRequest)
    {
        $this->paymentRequest = $paymentRequest;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // adjust channels as needed
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Your payment request has been approved by your manager.')
                    ->action('View payment', url('/payment-requests'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'payment_request_id' => $this->paymentRequest->id,
            'message' => 'Your payment request has been approved.',
            'url' => url('/payment-requests'), // Add the URL
        ];
    }
}
