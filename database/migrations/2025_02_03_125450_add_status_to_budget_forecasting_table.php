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
    Schema::table('budget_forecasting', function (Blueprint $table) {
        $table->string('status')->default('Pending')->after('approved_by'); // Add status column
    });
}

public function down()
{
    Schema::table('budget_forecasting', function (Blueprint $table) {
        $table->dropColumn('status'); // Remove status column
    });
}
};
