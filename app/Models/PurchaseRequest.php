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
        'item_info',
        'quantity_with_unit',
        'price_with_currency',
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
    return $this->belongsTo(User::class, 'checked_by');
}

public function approvedBy()
{
    return $this->belongsTo(User::class, 'approved_by');
}
}