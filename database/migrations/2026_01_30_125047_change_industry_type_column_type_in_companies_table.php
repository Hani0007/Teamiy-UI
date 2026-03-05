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
        Schema::table('companies', function (Blueprint $table) {

            // pehle foreign key (agar lag chuki ho to)
            // safe side ke liye
            if (Schema::hasColumn('companies', 'industry_type')) {
                $table->dropColumn('industry_type');
            }
        });

        Schema::table('companies', function (Blueprint $table) {

            // same name, but bigint (FK)
            $table->unsignedBigInteger('industry_type')->nullable();
    
            $table->foreign('industry_type')
                  ->references('id')
                  ->on('industry_types')
                  ->cascadeOnDelete();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {

            $table->dropForeign(['industry_type']);
            $table->dropColumn('industry_type');
        });

        Schema::table('companies', function (Blueprint $table) {

            // old string column restore
            $table->string('industry_type')->nullable();
        });
    }

};
