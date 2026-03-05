<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            DB::statement("ALTER TABLE `admins` MODIFY `username` VARCHAR(255) DEFAULT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            DB::statement("ALTER TABLE `admins` MODIFY `username` VARCHAR(255) NOT NULL DEFAULT ''");
        });
    }
};
