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
            $table->string('pay_period');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('allowances', 10, 2)->nullable();
            $table->decimal('deductions', 10, 2)->nullable();
            $table->decimal('net_salary', 10, 2);
            $table->string('status')->default('pending'); // e.g., pending, paid
            $table->unsignedBigInteger('prepared_by'); // Reference to users table
            $table->unsignedBigInteger('approved_by')->nullable(); // Reference to users table
            $table->timestamps();
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
