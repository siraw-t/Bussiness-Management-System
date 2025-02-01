<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->bigIncrements('leave_request_id');
            $table->unsignedBigInteger('user_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('leave_type', [
                'annual_leave', 
                'bereavement_leave', 
                'maternity_leave', 
                'sick_leave', 
                'unpaid_leave', 
                'parental_leave', 
                'other'
            ]);
            $table->string('other_reason')->nullable();
            $table->string('employee_signature')->nullable();
            $table->string('attachment')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->text('manager_comments')->nullable(); // Add this line
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_requests');
    }
}