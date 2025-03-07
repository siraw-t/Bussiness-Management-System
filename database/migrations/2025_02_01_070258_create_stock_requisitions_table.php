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
        Schema::create('stock_requisitions', function (Blueprint $table) {
            $table->bigIncrements('stock_requisition_id'); // Primary key
            $table->string('item_name'); 
            $table->text('description')->nullable(); // Item description
        
            $table->integer('quantity_requested'); // Quantity requested
            $table->string('unit_requested')->nullable(); // Unit for the requested quantity
            $table->integer('quantity_approved')->nullable(); // Quantity approved
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status
            $table->text('manager_comment')->nullable(); // Manager's comment
            $table->text('employee_comment')->nullable(); // Employee's comment
            $table->unsignedBigInteger('user_id'); // Reference to users table
            $table->unsignedBigInteger('requested_by'); // User who requested
            $table->unsignedBigInteger('approved_by')->nullable(); // User who approved
        
            $table->string('reference_number')->unique(); // Unique reference number for the requisition
            $table->string('company_country'); // Country of the company
        
            $table->timestamps(); // Created at and Updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_requisitions');
    }
};
