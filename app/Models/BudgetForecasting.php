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
        'created_by', 'forecast_period', 'budget_description', 'budget_item',
        'budget_code', 'department', 'budget_source', 'quantity', 'unit',
        'unit_cost', 'total_budget', 'status', 'manager_comment',
        'approved_by', 'reference_number', 'company_country', 'currency'
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


    public function employee()
{
    return $this->belongsTo(User::class, 'employee_id', 'user_id'); // Adjust as necessary
}

    
}