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
        Schema::table('admins', function (Blueprint $table) {
            $table->string('email_verification_otp', 6)->after('plan_id')->nullable();
            $table->timestamp('email_verification_expires_at')->after('email_verification_otp')->nullable();
            $table->boolean('is_verified')->after('email_verification_expires_at')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['email_verification_otp', 'email_verification_expires_at', 'is_verified']);
        });
    }
};
