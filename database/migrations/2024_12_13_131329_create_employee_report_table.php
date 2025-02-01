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
        Schema::create('employee_report', function (Blueprint $table) {
            $table->bigIncrements('report_id'); // Primary key
            $table->unsignedBigInteger('employee_id'); // Reference to users table
            $table->string('week'); // Week reference
            $table->text('report'); // General report content
            $table->string('status')->default('pending'); // e.g., pending, approved, rejected
            $table->string('reference_number')->unique(); // Unique reference number for each report
            $table->text('manager_comment')->nullable(); // Comments from the manager
            $table->unsignedBigInteger('approved_by')->nullable(); // Reference to users table for approval
            $table->timestamp('submitted_at')->useCurrent(); // Submission timestamp
            $table->timestamps(); // Timestamps for created_at and updated_at
        
            // New fields for tracking tasks
            $table->text('tasks_in_progress')->nullable(); // Tasks currently in progress
            $table->text('tasks_completed')->nullable(); // Tasks that have been completed
            $table->text('self_assessment')->nullable(); // Employee's self-assessment
            $table->unsignedBigInteger('last_week_plan_id')->nullable(); // Foreign key to employee_plan
        
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_report');
    }
};
