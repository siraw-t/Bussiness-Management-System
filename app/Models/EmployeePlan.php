<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePlan extends Model
{
    protected $table = 'employee_plan'; // Define the table name
    protected $primaryKey = 'plan_id'; // Define the primary key
    public $incrementing = true; // This should be true for auto-incrementing IDs
    protected $keyType = 'int'; // Assuming you're using integers for IDs

    protected $fillable = [
        'employee_id', 'week', 'plan', 'submitted_at', 'comment',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id', 'user_id'); // Relate to the employee
    }

    protected $casts = [
        'submitted_at' => 'datetime', // Cast to Carbon instance
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id'); // Change 'employee_id' if your foreign key is different
    }
}