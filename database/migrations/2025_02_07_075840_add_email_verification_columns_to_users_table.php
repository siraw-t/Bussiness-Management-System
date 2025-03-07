<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailVerificationColumnsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Email verification timestamp (nullable because the user might not have verified yet)
            $table->timestamp('email_verified_at')->nullable();
            // Email verification hash (nullable for the same reason)
            $table->string('email_verification_hash')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Dropping the columns when rolling back the migration
            $table->dropColumn(['email_verified_at', 'email_verification_hash']);
        });
    }
}
