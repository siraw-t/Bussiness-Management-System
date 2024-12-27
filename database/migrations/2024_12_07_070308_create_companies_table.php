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
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('company_id'); // Define the primary key explicitly as 'company_id'
            $table->string('company_name');
            $table->string('country');
            $table->string('city');
            $table->unsignedBigInteger('company_admin')->nullable(); // Admin ID as a nullable column
            $table->unsignedBigInteger('created_by')->nullable(); // Added created_by for future reference
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
