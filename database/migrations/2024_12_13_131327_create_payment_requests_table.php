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
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->bigIncrements('payment_request_id'); // Primary key
            
            $table->unsignedBigInteger('user_id'); // Reference to users table
            $table->string('ref_no')->nullable(); // Reference number
            $table->date('request_date')->nullable(); // Date of the request
            $table->string('from')->nullable(); // Requestor's department
            $table->text('finance_comment')->nullable(); // Finance comment
            $table->text('description'); // Description of Goods/Services
            $table->string('unit_and_qty')->nullable(); // Unit and Quantity
            $table->decimal('amount', 10, 2); // Total amount
            $table->decimal('vat', 10, 2)->nullable(); // VAT 18%
            $table->decimal('total', 10, 2); // Total including VAT
            $table->decimal('net_payment', 10, 2); // Net Payment
            $table->string('cost_center')->nullable(); // Cost Center
            $table->string('account_code')->nullable(); // Account Code
            $table->decimal('country_cost_recharge', 10, 2)->nullable(); // Country cost recharge
            $table->decimal('allocation_for_country', 10, 2)->nullable(); // Cost allocation for the country recharge
            $table->string('status')->default('pending'); // Status (e.g., pending, approved)
            $table->string('manager_comment')->nullable(); // Add this line
            // $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->unsignedBigInteger('reviewed_by')->nullable(); // Finance reviewer

            $table->unsignedBigInteger('approved_by')->nullable(); // Reference to users table
            $table->timestamps(); // Created at and Updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
