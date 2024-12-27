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
        Schema::create('employee_plan', function (Blueprint $table) {
            $table->bigIncrements('plan_id'); // Primary key
            $table->unsignedBigInteger('employee_id'); // Reference to users table
            $table->string('week');
            $table->text('plan');
            $table->timestamp('submitted_at')->useCurrent();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_plan');
    }
};
