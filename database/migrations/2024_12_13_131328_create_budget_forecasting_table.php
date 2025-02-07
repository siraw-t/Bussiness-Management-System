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
            $table->string('forecast_period'); // in dropdown: weekly, monthly, quarterly, annually
            $table->text('budget_description')->nullable();
            $table->text('budget_item')->nullable();
            $table->string('budget_code')->nullable(); // Unique code for the budget
            $table->string('department')->nullable(); // Department associated with the budget
            $table->string('budget_source')->nullable(); // Source of the budget
            $table->string('quantity_with_unit')->nullable(); // Quantity with units (e.g., "2 pcs")
            $table->decimal('unit_cost', 10, 2)->nullable(); // Cost per unit
            $table->decimal('total_budget', 10, 2)->nullable(); // Total budget amount
            $table->text('manager_comment')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable(); // Reference to users table
            $table->timestamps(); // Created at and updated at timestamps
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
