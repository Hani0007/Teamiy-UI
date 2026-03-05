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
        Schema::table('leave_approvals', function (Blueprint $table) {
            DB::statement('ALTER TABLE leave_approvals DROP FOREIGN KEY leave_approvals_leave_type_id_foreign');

            // 2. Make leave_type_id nullable
            DB::statement('ALTER TABLE leave_approvals MODIFY leave_type_id BIGINT UNSIGNED NULL');

            // 3. Make subject nullable
            DB::statement('ALTER TABLE leave_approvals MODIFY subject VARCHAR(255) NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_approvals', function (Blueprint $table) {
           DB::statement('ALTER TABLE leave_approvals MODIFY subject VARCHAR(255) NOT NULL');

            // Revert leave_type_id back to NOT NULL
            DB::statement('ALTER TABLE leave_approvals MODIFY leave_type_id BIGINT UNSIGNED NOT NULL');

            // Re-add the foreign key
            DB::statement('ALTER TABLE leave_approvals
                ADD CONSTRAINT leave_approvals_leave_type_id_foreign
                FOREIGN KEY (leave_type_id) REFERENCES leave_types(id)
                ON DELETE CASCADE');
        });
    }
};
