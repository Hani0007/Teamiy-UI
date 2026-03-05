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
            // Ensure is_verified defaults to 0 (false)
            $table->boolean('is_verified')->default(false)->change();
        });

        // Update any existing records that might have is_verified = 1 without proper verification
        \DB::statement("
            UPDATE admins 
            SET is_verified = 0 
            WHERE is_verified = 1 
            AND (email_verification_otp IS NOT NULL OR email_verification_expires_at IS NOT NULL)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            // Revert to previous default if needed
            $table->boolean('is_verified')->default(true)->change();
        });
    }
};
