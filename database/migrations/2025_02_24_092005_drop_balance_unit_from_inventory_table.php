<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            // Check if the column exists before attempting to drop it
            if (Schema::hasColumn('inventory', 'balance_unit')) {
                $table->dropColumn('balance_unit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            // Add the column back in case you need to roll back
            $table->string('balance_unit')->nullable();
        });
    }
};