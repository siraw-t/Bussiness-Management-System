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
        Schema::create('inventory', function (Blueprint $table) {
            $table->bigIncrements('inventory_id'); // Primary key
            $table->string('item_name'); 
            $table->string('tag_number')->unique(); // Unique tag number for tracking
            $table->enum('item_type', ['fixed asset', 'consumable']); // Item type
            $table->string('reference_number')->unique(); // Unique reference number
            $table->string('company_country'); // Country of the company

            
            // Stock-in details (Received)
            $table->integer('quantity'); // Quantity received
            $table->decimal('unit_price', 10, 2); // Unit price
            $table->decimal('total_price', 10, 2); // Total price
            $table->text('description')->nullable();
            $table->date('stock_in_date')->nullable(); // Date stock was received
            $table->unsignedBigInteger('managed_by')->nullable(); // User who received the stock
            $table->string('currency'); // Currency for stock-in
            $table->string('unit'); // Unit for stock-in
        
            // Stock-out details (Issued)
            $table->integer('issued_quantity')->nullable(); // Quantity issued
            $table->decimal('issued_unit_price', 10, 2)->nullable(); // Unit price at issuance
            $table->decimal('issued_total_price', 10, 2)->nullable(); // Total price at issuance
            $table->date('stock_out_date')->nullable(); // Date stock was issued
            $table->string('issued_currency')->nullable(); // Currency for stock-out
            $table->string('issued_unit')->nullable(); // Unit for stock-out
        
            // Status & Tracking
            $table->string('issued_department')->nullable(); // Department receiving the issued stock
            $table->unsignedBigInteger('received_by')->nullable(); // Person who received the issued stock
            
            // Stock Balance (Remaining)
            $table->integer('balance_quantity')->nullable(); // Remaining quantity after stock-out
            $table->decimal('balance_total_price', 10, 2)->nullable(); // Remaining total value
        
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
