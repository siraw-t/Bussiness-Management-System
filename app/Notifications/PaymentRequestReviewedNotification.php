<?php

namespace App\Notifications;

use App\Models\PaymentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentRequestReviewedNotification extends Notification
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
                    ->line('A new payment request reviewed by finance has been submitted.')
                    ->action('Review payment reviewed', url('/display/pending'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'payment_request_id' => $this->paymentRequest->id,
            'message' => 'A new payment request reviewed by finance has been submitted.',
            'url' => url('/display/pending'), // Add the URL
        ];
    }
}
