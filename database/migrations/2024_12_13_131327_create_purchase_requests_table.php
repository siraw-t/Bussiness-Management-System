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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->bigIncrements('purchase_request_id'); // Primary key
            $table->unsignedBigInteger('user_id'); // Reference to users table
            $table->string('reference_number')->unique(); // Unique reference number for each request
            $table->text('item_info')->nullable(); // Combined item name and description
            $table->string('quantity_with_unit'); // Combined quantity and unit (e.g., "10 pcs")
            $table->string('price_with_currency'); // Combined price and currency (e.g., "100.00 USD")
            $table->string('status')->default('pending'); // e.g., pending, approved, rejected
            $table->json('attachments')->nullable(); // Store multiple file paths as JSON
            $table->text('finance_comment')->nullable(); // Comments from finance
            $table->unsignedBigInteger('checked_by')->nullable(); // Reference to users table for check
            $table->text('manager_comment')->nullable(); // Comments from the manager
            $table->unsignedBigInteger('approved_by')->nullable(); // Reference to users table for approval
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
