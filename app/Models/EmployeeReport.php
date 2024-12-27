<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeReport extends Model
{
    protected $table = 'employee_report'; // Define the table name
    protected $primaryKey = 'report_id'; // Define the primary key
    public $incrementing = true; // This should be true for auto-incrementing IDs
    protected $keyType = 'int'; // Assuming you're using integers for IDs

    protected $fillable = [
        'employee_id', 'week', 'report', 'comment', 'submitted_at',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id', 'user_id'); // Relate to the employee
    }

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id'); // Adjust 'employee_id' if your foreign key is different
    }
}