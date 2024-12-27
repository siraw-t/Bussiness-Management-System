<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests'; // Define the table name
    protected $primaryKey = 'leave_request_id'; // Define the primary key
    public $incrementing = true; // This should be true for auto-incrementing IDs
    protected $keyType = 'int'; // Assuming you're using integers for IDs

    protected $fillable = [
        'user_id', 'start_date', 'end_date', 'reason', 'status', 'approved_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id'); // Relate to the User model
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id'); // Relate to the approver
    }

    protected $casts = [
        'submitted_at' => 'datetime',
    ];
}