<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeneratedPayrollResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'payroll_type'            => $this->payroll_type,
            'payment_type'            => $this->payment_type,
            'worked_hours'            => (float) $this->worked_hours,
            'overtime_hours'          => (float) $this->overtime_hours,
            'undertime_hours'         => (float) $this->undertime_hours,
            'leave_days_by_type'      => $this->leave_days_by_type ?? [],
            'total_unpaid_leave_days' => (float) $this->total_unpaid_leave_days,
            'base_salary'             => (float) $this->base_salary,
            'overtime_pay'            => (float) $this->overtime_pay,
            'tada_amount'             => (float) $this->tada_amount,
            'undertime_deduction'     => (float) $this->undertime_deduction,
            'unpaid_leave_deduction'  => (float) $this->unpaid_leave_deduction,
            'tax'                     => (float) $this->tax,
            'net_salary'              => (float) $this->net_salary,
            'range'                   => $this->range ?? [],
            'branch'               => $this->branch
                                            ? [
                                                'id' => $this->branch->id,
                                                'name' => $this->branch->name
                                            ] : null,
            'department'           => $this->department
                                            ? [
                                                'id' => $this->department->id,
                                                'name' => $this->department->dept_name
                                            ] : null,
            'status'                  => $this->status,
            'created_at'              => $this->created_at?->toISOString(),
            'updated_at'              => $this->updated_at?->toISOString(),

            'employee' => $this->whenLoaded('employee', function () {
                return [
                    'id'    => $this->employee->id,
                    'name'  => $this->employee->name,
                    'email' => $this->employee->email,
                ];
            }),
        ];
    }
}
