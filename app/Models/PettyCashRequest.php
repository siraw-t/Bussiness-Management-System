<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PettyCashRequest extends Model
{
    use HasFactory;

    protected $table = 'petty_cash_requests';

    protected $primaryKey = 'petty_cash_request_id';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'amount_requested',
        'amount_requested_in_words',
        'amount_approved',
        'amount_approved_in_words',
        'currency',
        'attachments',
        'status',
        'manager_comment',
        'finance_comment',
        'approved_by',
        'checked_by',
        'reference_number',
        'company_country',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];
    // Relationship with User (requester)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by', 'user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }
}