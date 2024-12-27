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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->bigIncrements('leave_request_id'); // Primary key
            $table->unsignedBigInteger('user_id'); // Reference to users table
            $table->date('start_date');
            $table->date('end_date');
            $table->string('reason');
            $table->string('status')->default('pending'); // e.g., pending, approved, rejected
            $table->unsignedBigInteger('approved_by')->nullable(); // Reference to users table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
