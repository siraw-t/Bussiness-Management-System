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
            $table->string('reference_no')->nullable(); // Reference number
            $table->date('request_date')->nullable(); // Date of the request
            $table->string('department')->nullable(); // Requestor's department
            $table->text('finance_comment')->nullable(); // Finance comment
            $table->text('item_description'); // Description of Goods/Services
            $table->string('item_name')->nullable(); // Item name
            $table->string('unit')->nullable(); // Unit of measurement
            $table->integer('quantity')->nullable(); // Quantity
            $table->decimal('amount', 10, 2); // Total amount
            $table->decimal('vat', 10, 2)->nullable(); // VAT 18%
            $table->decimal('total', 10, 2); // Total including VAT
            $table->decimal('net_payment', 10, 2); // Net Payment
            $table->decimal('with_holding', 10, 2)->nullable(); // 6%
            $table->string('cost_center')->nullable(); // Cost Center
            $table->string('account_code')->nullable(); // Account Code
            
            $table->string('company_country'); // Country of the company
            $table->string('currency')->nullable(); // Currency (e.g., USD, EUR)
            
            $table->string('status')->default('pending'); // Status (e.g., pending, approved)
            $table->string('manager_comment')->nullable(); // Manager's comment
            $table->unsignedBigInteger('reviewed_by')->nullable(); // Finance reviewer
            $table->unsignedBigInteger('approved_by')->nullable(); // Reference to users table
            $table->enum('cash_type', ['petty_cash', 'account_payable']); // Cash type

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
