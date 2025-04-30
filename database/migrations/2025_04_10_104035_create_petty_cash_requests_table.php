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
        Schema::create('petty_cash_requests', function (Blueprint $table) {
            $table->bigIncrements('petty_cash_request_id'); // Primary key
            $table->unsignedBigInteger('user_id'); // Reference to the user making the request
            $table->string('title'); // Title of the request
            $table->text('description')->nullable(); // Description of the request
            $table->decimal('amount_requested', 10, 2); // Amount requested
            $table->string('amount_requested_in_words')->nullable(); // Amount requested in English words
            $table->decimal('amount_approved', 10, 2)->nullable(); // Approved amount
            $table->string('amount_approved_in_words')->nullable(); // Approved amount in English words
            $table->string('currency'); // Currency for the requested amount
            $table->json('attachments')->nullable(); // Store multiple file paths as JSON
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Request status
            $table->text('manager_comment')->nullable(); // Manager's comment
            $table->text('finance_comment')->nullable(); // Finance comment
            $table->unsignedBigInteger('approved_by')->nullable(); // User who approved the request
            $table->unsignedBigInteger('checked_by')->nullable(); // User who checked the request
            $table->string('reference_number')->unique(); // Unique reference number
            $table->timestamps(); // Created at and Updated at
            $table->string('company_country')->nullable();  // Added here

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cash_requests');
    }
};
