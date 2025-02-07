<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $table = 'payment_requests'; // Define the table name
    protected $primaryKey = 'payment_request_id'; // Define the primary key
    public $incrementing = true; // This should be true for auto-incrementing IDs
    protected $keyType = 'int'; // Assuming you're using integers for IDs

    protected $fillable = [
        'user_id', 'amount', 'description', 'status', 'finance_comment', 'manager_comment',
        'approved_by', 'reviewed_by', 'unit_and_qty', 'cost_center', 'account_code',
        'country_cost_recharge', 'allocation_for_country', 'vat', 'total', 'net_payment', 'with_holding',
        'ref_no', 'request_date', 'from'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Relate to the User model
    }

    public function checkedBy()
{
    return $this->belongsTo(User::class, 'reviewed_by');
}

public function approvedBy()
{
    return $this->belongsTo(User::class, 'approved_by');
}

    protected $casts = [
        'request_date' => 'datetime', // Cast to DateTime
    ];
}