<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyReferenceNoInPaymentRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            // Check if the column exists before dropping
            if (Schema::hasColumn('payment_requests', 'reference_no')) {
                $table->dropColumn('reference_no'); 
            }

            // Add the new column with the unique constraint
            $table->string('reference_no')->unique()->nullable()->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            // Check if the unique index exists before attempting to drop it
            if (Schema::hasColumn('payment_requests', 'reference_no')) {
                // Remove the unique constraint by dropping the column instead
                $table->dropColumn('reference_no'); // Drop the column
            }

            // Recreate the column without unique constraint
            // This line is not necessary since we're dropping the column
            // $table->string('reference_no')->nullable()->after('user_id'); 
        });
    }
}