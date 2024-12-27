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
        Schema::create('budget_forecasting', function (Blueprint $table) {
            $table->bigIncrements('budget_id'); // Primary key
            $table->unsignedBigInteger('created_by'); // Reference to users table
            $table->string('forecast_period');
            $table->decimal('forecast_amount', 10, 2);
            $table->decimal('actual_amount', 10, 2);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('budget_forecasting');
    }
};
