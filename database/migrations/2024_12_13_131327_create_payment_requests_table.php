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
            $table->decimal('amount', 10, 2);
            $table->string('description');
            $table->string('status');
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
        Schema::dropIfExists('payment_requests');
    }
};
