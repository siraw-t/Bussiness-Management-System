<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification as BaseNotification;

class DatabaseNotification extends BaseNotification
{
    // You can add additional methods or properties here if needed

    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Add custom methods if needed
     */
    public function markAsRead()
    {
        $this->read_at = now();
        $this->save();
    }
}