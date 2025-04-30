<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CompanyRegisteredNotification extends Notification
{
    use Queueable;

    protected $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Adjust channels as needed
    }

    public function toMail($notifiable)
    {
        // Retrieve the admin's name from the company relationship (if available)
        $adminName = $this->company->admin ? $this->company->admin->full_name : 'N/A';

        // Prepare the logo URL if available
        $logoUrl = $this->company->logo ? asset('storage/' . $this->company->logo) : 'No logo available';

        return (new MailMessage)
                    ->line('A new company has been successfully registered.')
                    ->line('Company: ' . $this->company->company_name)
                    ->line('Admin: ' . $adminName)
                    ->line('Logo: ' . $logoUrl)
                    ->action('View Company', url('/companies/' . $this->company->company_id))
                    ->line('Thank you for registering!');
    }

    public function toArray($notifiable)
    {
        return [
            'company_id' => $this->company->company_id,
            'message' => 'Company registered with admin: ' . ($this->company->admin ? $this->company->admin->full_name : 'N/A'),
        ];
    }
}