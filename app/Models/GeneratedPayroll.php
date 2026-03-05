<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneratedPayroll extends Model
{
    use HasFactory;

    protected $casts = [
        'leave_days_by_type' => 'array',
        'range' => 'array',
    ];

    protected $fillable = [
        'range', 'leave_days_by_type', 'department_id', 'branch_id', 'employee_id', 'status', 'net_salary', 'tax', 'unpaid_leave_deduction',
        'undertime_deduction', 'overtime_pay', 'base_salary', 'total_unpaid_leave_days', 'undertime_hours', 'overtime_hours',
        'worked_hours', 'payment_type', 'payroll_type', 'tada_amount', 'created_at', 'updated_at'
    ];

    const HOURS_PER_DAY = 8;
    const WORK_DAYS_PER_WEEK = 5;

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
