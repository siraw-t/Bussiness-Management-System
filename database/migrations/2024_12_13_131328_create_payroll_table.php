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
        Schema::create('payroll', function (Blueprint $table) {
            $table->bigIncrements('payroll_id'); // Primary key
            $table->unsignedBigInteger('employee_id'); // Reference to users table
            $table->string('job_title'); // Job title
            $table->decimal('basic_salary', 10, 2); // Basic salary
            $table->integer('no_of_days'); // Number of working days
            $table->decimal('transport_allowance', 10, 2)->nullable(); // Transport allowance
            $table->decimal('taxable_transport_allowance', 10, 2)->nullable(); // Taxable transport allowance
            $table->decimal('position_allowance', 10, 2)->nullable(); // Position allowance
            $table->decimal('total_taxable_earnings', 10, 2); // Total taxable earnings
            $table->decimal('income_tax', 10, 2); // Income tax
            $table->decimal('pension_fund_7', 10, 2); // Pension fund 7%
            $table->decimal('pension_fund_11', 10, 2); // Pension fund 11%
            $table->decimal('salary_advance', 10, 2)->nullable(); // Salary advance
            $table->decimal('net_payment', 10, 2); // Net payment
            $table->string('signature')->nullable(); // Employee signature
            $table->unsignedBigInteger('approved_by')->nullable(); // Reference to users table
            $table->unsignedBigInteger('prepared_by')->nullable(); // Reference to users table
            $table->string('pay_period')->nullable(); // Pay Period
            $table->string('status')->default('Pending'); // Status
            $table->text('manager_comment')->nullable(); 
            $table->timestamps(); // Created at and Updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll');
    }
};
