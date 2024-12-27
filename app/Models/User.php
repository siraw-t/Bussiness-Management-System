<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users'; // Define the table name
    protected $primaryKey = 'user_id'; // Define the primary key
    public $incrementing = true; // This should be true for auto-incrementing IDs
    protected $keyType = 'int'; // Assuming you're using integers for IDs

    protected $fillable = [
        'username', 'full_name', 'email', 'password', 'role', 'company_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id'); // Relate to the Company model
    }


    // Relationship to payrolls as an employee
    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'employee_id', 'user_id'); // User can have many payrolls as an employee
    }

    // Relationship to payrolls as a preparer
    public function preparedPayrolls()
    {
        return $this->hasMany(Payroll::class, 'prepared_by', 'user_id'); // User can prepare many payrolls
    }

    // Relationship to payrolls as an approver
    public function approvedPayrolls()
    {
        return $this->hasMany(Payroll::class, 'approved_by', 'user_id'); // User can approve many payrolls
    }

    public function managers()
    {
        return $this->hasMany(User::class, 'company_id', 'company_id')->where('role', 'manager');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id'); // Adjust 'employee_id' if your foreign key is different
    }

    public function budgetForecastings()
    {
        return $this->hasMany(BudgetForecasting::class, 'created_by'); // Adjust 'created_by' if necessary
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'managed_by');
    }

    
}