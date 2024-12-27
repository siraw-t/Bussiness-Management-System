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
            $table->string('item_name');
            $table->integer('quantity');
            $table->decimal('estimated_cost', 10, 2);
            $table->string('status')->default('pending'); // e.g., pending, approved, rejected
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable(); // Reference to users table
            $table->timestamps();
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
