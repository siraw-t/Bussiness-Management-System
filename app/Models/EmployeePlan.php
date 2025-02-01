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
        'employee_id', 'week', 'plan', 'status', 'reference_number', 'submitted_at', 'manager_comment', 'approved_by', 'urgent_issues',
    ];

    public function employee()
{
    return $this->belongsTo(User::class, 'employee_id', 'user_id'); // Adjust as necessary
}

    protected $casts = [
        'submitted_at' => 'datetime', // Cast to Carbon instance
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id'); // Assuming employee_id is the foreign key
    }
}