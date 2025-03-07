<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';
    protected $primaryKey = 'leave_request_id';
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'leave_type',
        'other_reason',
        'employee_signature',
        'attachment',
        'status',
        'approved_by',
        'manager_comments',
        'company_country',
        'reference_number',
        'annual_leave_limit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }
}