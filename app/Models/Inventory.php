<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory'; // Define the table name
    protected $primaryKey = 'inventory_id'; // Define the primary key
    public $incrementing = true; // This should be true for auto-incrementing IDs
    protected $keyType = 'int'; // Assuming you're using integers for IDs

    protected $fillable = [
        'item_name', 'tag_number', 'item_type', 'reference_number',
        'company_country', 'quantity', 'unit_price', 'total_price',
        'description', 'stock_in_date', 'managed_by', 'currency', 
        'unit', 'issued_quantity', 'issued_unit_price', 
        'issued_total_price', 'stock_out_date', 'issued_department',
        'received_by', 'balance_quantity', 'balance_total_price',
        'issued_currency', 'issued_unit'
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'managed_by', 'user_id'); // Relationship to manager
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id'); // Relationship to approver
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    protected $dates = [
        'stock_in_date', 'stock_out_date', 'created_at', 'updated_at',
    ];

    public function stockRequisition()
    {
        return $this->belongsTo(StockRequisition::class, 'user_id', 'user_id');
    }

    protected $casts = [
        'stock_in_date' => 'datetime',
        'stock_out_date' => 'datetime', // Ensure this is cast to a Carbon date
    ];
}