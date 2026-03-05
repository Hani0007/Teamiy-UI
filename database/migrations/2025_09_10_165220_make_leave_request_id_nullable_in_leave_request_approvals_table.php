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
        Schema::table('leave_request_approvals', function (Blueprint $table) {
            DB::statement('ALTER TABLE leave_request_approvals MODIFY leave_request_id BIGINT UNSIGNED NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_request_approvals', function (Blueprint $table) {
            DB::statement('ALTER TABLE leave_request_approvals MODIFY leave_request_id BIGINT UNSIGNED NOT NULL');
        });
    }
};
