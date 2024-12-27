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
        Schema::create('request_form_templates', function (Blueprint $table) {
            $table->bigIncrements('form_id'); // Primary key
            $table->string('form_name');
            $table->text('fields'); // JSON or text representation of fields
            $table->unsignedBigInteger('created_by'); // Reference to users table
            $table->unsignedBigInteger('company_id'); // Reference to companies table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_form_templates');
    }
};
