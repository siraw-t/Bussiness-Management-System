<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('payment_requests', function (Blueprint $table) {
        $table->boolean('vat_inclusive')->default(false)->after('amount'); // Adjust placement if needed
    });
}

public function down()
{
    Schema::table('payment_requests', function (Blueprint $table) {
        $table->dropColumn('vat_inclusive');
    });
}
};
