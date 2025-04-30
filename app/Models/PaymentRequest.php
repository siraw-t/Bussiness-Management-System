<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $table = 'payment_requests'; // Define the table name
    protected $primaryKey = 'payment_request_id'; // Define the primary key
    public $incrementing = true; // Auto-incrementing ID
    protected $keyType = 'int'; // Integer type for primary key

    protected $fillable = [
        'user_id',
        'reference_no',
        'request_date',
        'department',
        'finance_comment',
        'item_description',
        'item_name',
        'item_name_manual', // Add this line
        'unit',
        'quantity',
        'amount',
        'vat_inclusive', // New field for VAT inclusion
        'vat',
        'total',
        'net_payment',
        'with_holding',
        'cost_center',
        'account_code',
        'company_country',
        'currency',
        'status',
        'manager_comment',
        'reviewed_by',
        'approved_by',
        'item_type', // Add item_type to fillable fields
        'unit_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Relate to the User model
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    protected $casts = [
        'request_date' => 'datetime', // Cast to DateTime
        'vat_inclusive' => 'boolean', // Ensure boolean casting for VAT inclusion
    ];
}
