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
        Schema::create('generated_payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('payroll_type')->nullable();
            $table->string('payment_type')->nullable();
            $table->integer('worked_hours')->nullable();
            $table->integer('overtime_hours')->nullable();
            $table->integer('undertime_hours')->nullable();
            $table->string('leave_days_by_type')->nullable();
            $table->integer('total_unpaid_leave_days')->nullable();
            $table->float('base_salary')->nullable();
            $table->float('overtime_pay')->nullable();
            $table->float('tada_amount')->nullable();
            $table->float('undertime_deduction')->nullable();
            $table->float('unpaid_leave_deduction')->nullable();
            $table->integer('tax')->nullable();
            $table->float('net_salary')->nullable();
            $table->string('range')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('status')->default('pending')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_payrolls');
    }
};
