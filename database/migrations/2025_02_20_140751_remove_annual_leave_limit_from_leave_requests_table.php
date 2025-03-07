<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAnnualLeaveLimitFromLeaveRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            // Attempt to drop the column
            if (Schema::hasColumn('leave_requests', 'annual_leave_limit')) {
                $table->dropColumn('annual_leave_limit');
            }
        });
    }

    public function down()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            // Add the column back in case you need to roll back
            $table->integer('annual_leave_limit')->default(0); // Adjust default as needed
        });
    }
}