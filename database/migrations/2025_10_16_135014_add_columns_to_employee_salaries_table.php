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
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->float('tax')->nullable();
            $table->tinyInteger('is_overtime')->default(0);
            $table->integer('weekly_working_hours')->nullable();

            $table->enum('payroll_type', ['annual', 'hourly'])->nullable();
            $table->enum('payment_type', ['monthly', 'weekly'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->dropColumn(['tax', 'is_overtime', 'weekly_working_hours', 'payroll_type', 'payment_type']);
        });
    }
};
