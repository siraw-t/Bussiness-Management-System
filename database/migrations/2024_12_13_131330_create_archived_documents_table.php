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
        Schema::create('archived_documents', function (Blueprint $table) {
            $table->bigIncrements('document_id'); // Primary key
            $table->string('document_name');
            $table->string('document_type');
            $table->unsignedBigInteger('document_owner'); // Reference to users table
            $table->text('description')->nullable();
            $table->unsignedBigInteger('company_id'); // Reference to companies table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archived_documents');
    }
};
