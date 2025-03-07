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
            $table->string('item_name'); // Separate item name
            $table->text('item_description')->nullable(); // Separate item description
            $table->integer('quantity'); // Quantity
            $table->string('unit'); // Unit of measurement
            $table->decimal('price', 10, 2); // Price without currency
            $table->string('currency'); // Currency (e.g., USD)
            $table->decimal('total_price', 10, 2); // Total price
            $table->string('company_country'); // Country of the company
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
