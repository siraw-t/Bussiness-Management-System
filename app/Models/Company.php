<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    use Notifiable;

    protected $table = 'companies';
    protected $primaryKey = 'company_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'company_name', 'country', 'city', 'contact_email', 'contact_phone', 'created_by', 'company_admin', 'registered_at', 'logo'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'company_admin', 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'company_id');
    }

    // This method tells notifications to use the company's contact_email
    public function routeNotificationForMail($notification)
    {
        return $this->contact_email;
    }

    protected $casts = [
        'registered_at' => 'datetime',
        'created_at'   => 'datetime',
    ];
}