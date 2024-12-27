<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestFormTemplate extends Model
{
    protected $table = 'request_form_templates'; // Define the table name
    protected $primaryKey = 'form_id'; // Define the primary key
    public $incrementing = true; // This should be true for auto-incrementing IDs
    protected $keyType = 'int'; // Assuming you're using integers for IDs

    protected $fillable = [
        'form_name', 'fields', 'created_by', 'company_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id'); // Relate to the creator
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id'); // Relate to the Company model
    }
}