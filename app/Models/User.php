<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'username', 
        'full_name', 
        'email', 
        'password', 
        'role', 
        'company_id',
        'profile_image',
        'email_verified_at',
        'email_verification_hash',
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'employee_id', 'user_id');
    }

    public function preparedPayrolls()
    {
        return $this->hasMany(Payroll::class, 'prepared_by', 'user_id');
    }

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