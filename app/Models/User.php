<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // Import the email verification contract
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $table = 'users'; // Define the table name
    protected $primaryKey = 'user_id'; // Define the primary key
    public $incrementing = true; // Auto-incrementing IDs
    protected $keyType = 'int'; // Using integers for IDs

    protected $fillable = [
        'username', 
        'full_name', 
        'email', 
        'password', 
        'role', 
        'company_id',
        'profile_image',
        // Adding the new fields to allow mass assignment (optional)
        'email_verified_at',
        'email_verification_hash',
    ];

    protected $hidden = [
        'password', 
        'remember_token',
        // You may choose to hide the verification hash if it should remain confidential
        // 'email_verification_hash',
    ];

    // Cast email_verified_at to a datetime instance
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id'); // Relate to the Company model
    }

    // Relationship to payrolls as an employee
    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'employee_id', 'user_id');
    }

    // Relationship to payrolls as a preparer
    public function preparedPayrolls()
    {
        return $this->hasMany(Payroll::class, 'prepared_by', 'user_id');
    }

    // Relationship to payrolls as an approver
    public function approvedPayrolls()
    {
        return $this->hasMany(Payroll::class, 'approved_by', 'user_id');
    }

    public function managers()
    {
        return $this->hasMany(User::class, 'company_id', 'company_id')->where('role', 'manager');
    }

    public function inventoriesReceived()
    {
        return $this->hasMany(Inventory::class, 'received_by');
    }

    public function budgetForecastings()
    {
        return $this->hasMany(BudgetForecasting::class, 'created_by');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'managed_by');
    }

    public function employeePlans()
    {
        return $this->hasMany(EmployeePlan::class, 'employee_id', 'user_id');
    }

    
}
