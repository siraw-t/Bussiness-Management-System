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
        'item_name', 'quantity', 'description', 'stock_in_date', 'stock_out_date', 'managed_by', 'approved_by',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'managed_by', 'user_id'); // Relationship to manager
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id'); // Relationship to approver
    }

    protected $dates = [
        'stock_in_date',
        'stock_out_date',
        'created_at',
        'updated_at',
    ];
}