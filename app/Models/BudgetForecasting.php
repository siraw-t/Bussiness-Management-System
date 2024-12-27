<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetForecasting extends Model
{
    protected $table = 'budget_forecasting'; // Define the table name
    protected $primaryKey = 'budget_id'; // Define the primary key
    public $incrementing = true; // This should be true for auto-incrementing IDs
    protected $keyType = 'int'; // Assuming you're using integers for IDs

    protected $fillable = [
        'created_by', 'forecast_period', 'forecast_amount', 'actual_amount', 'description', 'comment', 'approved_by',
    ];

    // In BudgetForecasting.php
public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function approver()
{
    return $this->belongsTo(User::class, 'approved_by');
}

public function user()
{
    return $this->belongsTo(User::class, 'created_by'); // Adjust 'created_by' if necessary
}

public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id'); // Relate to the Company model
    }


}