<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_plan', function (Blueprint $table) {
            $table->bigIncrements('plan_id'); // Primary key
            $table->unsignedBigInteger('employee_id'); // Reference to users table
            $table->string('week'); // Week date from Monday to Saturday
            $table->text('plan'); // Plan details for the next week
            $table->string('status')->default('pending'); // e.g., pending, approved, rejected
            $table->string('reference_number')->unique(); // Unique reference number for each plan
            $table->timestamp('submitted_at')->useCurrent(); // Submission timestamp
            $table->text('manager_comment')->nullable(); // Comments from the manager
            $table->unsignedBigInteger('approved_by')->nullable(); // Reference to users table for approval
            $table->text('urgent_issues')->nullable(); // Field for urgent issues
        
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_plan');
    }
};
