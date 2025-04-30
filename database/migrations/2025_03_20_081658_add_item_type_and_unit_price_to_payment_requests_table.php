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
        Schema::table('payment_requests', function (Blueprint $table) {
            // Make item_type nullable
            $table->string('item_type')->nullable()->after('item_description'); // Add item_type column
            $table->decimal('unit_price', 10, 2)->after('amount'); // Add unit_price column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->dropColumn('item_type'); // Drop item_type column
            $table->dropColumn('unit_price'); // Drop unit_price column
        });
    }
};