<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockRequisition extends Model
{
    protected $table = 'stock_requisitions'; // Define the table name
    protected $primaryKey = 'stock_requisition_id'; // Define the primary key

    protected $fillable = [
        'item_name_with_description', 'quantity_requested', 'quantity_approved', 
        'status', 'manager_comment', 'employee_comment', 
        'user_id', 'requested_by', 'approved_by',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by', 'user_id'); // Relationship to requester
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id'); // Relationship to approver
    }

    protected $dates = [
        'created_at', 'updated_at',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id'); // Adjust 'user_id' if the foreign key is different
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}