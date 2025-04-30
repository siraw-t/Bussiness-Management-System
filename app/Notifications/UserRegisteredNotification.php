<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserRegisteredNotification extends Notification
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Adjust channels as needed
    }

    public function toMail($notifiable)
    {
        $company = Company::find($this->user->company_id);

        // Generate a password reset token for the user
        $token = app('auth.password.broker')->createToken($this->user);

        // Build the reset URL with the token and the user's email
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $this->user->email,
        ], false));

        return (new MailMessage)
                    ->line('Congratulations! Your account has been successfully registered.')
                    ->line('Company: ' . ($company ? $company->company_name : 'N/A'))
                    ->line('Role: ' . $this->user->role)
                    ->line('Email: ' . $this->user->email)
                    ->action('Set Your Password', $resetUrl)
                    ->line('Thank you for registering!');
    }

    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->user->user_id,
            'company' => Company::find($this->user->company_id)->company_name ?? 'N/A',
            'message' => 'Your account has been successfully created.',
        ];
    }
}