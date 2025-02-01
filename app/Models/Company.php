<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies'; // Define the table name
    protected $primaryKey = 'company_id'; // Define the primary key
    public $incrementing = true; // This should be true for auto-incrementing IDs
    protected $keyType = 'int'; // Assuming you're using integers for IDs

    protected $fillable = [
        'company_name', 'country', 'city', 'contact_email', 'contact_phone', 'created_by', 'company_admin', 'registered_at', 'logo'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'company_admin', 'user_id'); // Adjust if necessary
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id'); // Ensure 'user_id' is the primary key in User
    }

    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'company_id'); // Relate to users by company_id
    }

    protected $casts = [
        'registered_at' => 'datetime', // Ensure this is cast to Carbon
        'created_at' => 'datetime', // Ensure this is cast to Carbon
    ];
}