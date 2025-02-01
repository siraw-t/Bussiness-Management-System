<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $table = 'payroll'; // Define the table name
    protected $primaryKey = 'payroll_id'; // Define the primary key
    public $incrementing = true; // This should be true for auto-incrementing IDs
    protected $keyType = 'int'; // Assuming you're using integers for IDs

    protected $fillable = [
        'employee_id', 'job_title', 'no_of_days', 'basic_salary', 'transport_allowance',
        'taxable_transport_allowance', 'position_allowance', 'total_taxable_earnings',
        'income_tax', 'pension_fund_7', 'pension_fund_11', 'salary_advance', 'net_payment',
        'pay_period', 'status', 'prepared_by', 'approved_by',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id', 'user_id'); // Relate to the employee
    }

    

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id'); // Relate to the approver
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id'); // Adjust 'employee_id' if necessary
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id'); // Relate to the Company model
    }

    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by', 'user_id'); // Adjust 'prepared_by' to your actual foreign key
    }
}