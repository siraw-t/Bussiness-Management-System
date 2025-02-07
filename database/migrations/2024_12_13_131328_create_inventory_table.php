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
            $table->string('tag_number')->nullable(); // Tag number for tracking
            $table->enum('item_type', ['fixed asset', 'consumable']); // Item type
            $table->string('reference_number')->unique(); // Unique reference number
        
            // Stock-in details (Received)
            $table->integer('quantity'); // Quantity received
            $table->decimal('unit_price', 10, 2); // Unit price
            $table->decimal('total_price', 10, 2); // Total price
            $table->text('description')->nullable();
            $table->date('stock_in_date')->nullable(); // Date stock was received
            $table->unsignedBigInteger('managed_by')->nullable(); // User who received the stock
        
            // Stock-out details (Issued)
            $table->integer('issued_quantity')->nullable(); // Quantity issued
            $table->decimal('issued_unit_price', 10, 2)->nullable(); // Unit price at issuance
            $table->decimal('issued_total_price', 10, 2)->nullable(); // Total price at issuance
            $table->date('stock_out_date')->nullable(); // Date stock was issued
        
            // Status & Tracking
            $table->string('issued_department')->nullable(); // Department receiving the issued stock
            $table->unsignedBigInteger('received_by')->nullable(); // Person who received the issued stock
        
            // Stock Balance (Remaining)
            $table->integer('balance_quantity')->nullable(); // Remaining quantity after stock-out
            $table->decimal('balance_total_price', 10, 2)->nullable(); // Remaining total value
        
            $table->timestamps();
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
