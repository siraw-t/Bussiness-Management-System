<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $table = 'purchase_requests';

    protected $primaryKey = 'purchase_request_id';

    protected $fillable = [
        'user_id',
        'reference_number',
        'item_name', // Updated to match schema
        'item_description', // Updated to match schema
        'quantity', // Updated to match schema
        'unit', // Updated to match schema
        'price', // Updated to match schema
        'currency', // Updated to match schema
        'total_price', // Updated to match schema
        'company_country', // Updated to match schema
        'status',
        'attachments',
        'approved_by',
        'checked_by',
        'manager_comment',
        'finance_comment',
    ];

    protected $casts = [
        'attachments' => 'array', // Cast attachments to an array
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