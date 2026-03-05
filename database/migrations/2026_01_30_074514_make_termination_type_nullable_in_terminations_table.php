<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE terminations DROP FOREIGN KEY terminations_termination_type_id_foreign');
     
        DB::statement('ALTER TABLE terminations MODIFY termination_type_id BIGINT UNSIGNED NULL');
     
        DB::statement('ALTER TABLE terminations 
            ADD CONSTRAINT terminations_termination_type_id_foreign 
            FOREIGN KEY (termination_type_id) 
            REFERENCES termination_types(id) 
            ON DELETE CASCADE
        ');
    }
 
    public function down(): void
    {
        DB::statement('ALTER TABLE terminations DROP FOREIGN KEY terminations_termination_type_id_foreign');
     
        DB::statement('ALTER TABLE terminations MODIFY termination_type_id BIGINT UNSIGNED NOT NULL');
     
        DB::statement('ALTER TABLE terminations 
            ADD CONSTRAINT terminations_termination_type_id_foreign 
            FOREIGN KEY (termination_type_id) 
            REFERENCES termination_types(id) 
            ON DELETE CASCADE
        ');
}
};
