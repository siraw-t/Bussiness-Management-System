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
        Schema::create('stock_requisitions', function (Blueprint $table) {
            $table->bigIncrements('stock_requisition_id'); // Primary key
            $table->string('item_name_with_description');
            $table->integer('quantity_requested');
            $table->integer('quantity_approved')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('manager_comment')->nullable();
            $table->text('employee_comment')->nullable();
            $table->unsignedBigInteger('user_id'); // Reference to users table
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('approved_by')->nullable();
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_requisitions');
    }
};
