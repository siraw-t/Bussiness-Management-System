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
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->date('stock_in_date')->nullable();
            $table->date('stock_out_date')->nullable();
            $table->unsignedBigInteger('managed_by'); // Reference to users table
            $table->unsignedBigInteger('approved_by')->nullable(); // Reference to users table
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
