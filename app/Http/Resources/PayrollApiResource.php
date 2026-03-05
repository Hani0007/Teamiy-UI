<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                        => $this->id,
            'annual_salary'             => $this->annual_salary,
            'payroll_type'              => $this->payroll_type,
            'payment_type'              => $this->payment_type,
            'tax'                       => $this->tax,
            'is_overtime'               => $this->is_overtime,
            'hourly_rate'               => $this->hour_rate,
            'weekly_working_hours'      => $this->weekly_working_hours,
            'employee'               => $this->employee
                                            ? [
                                                'id'    => $this->employee->id,
                                                'name'  => $this->employee->name
                                            ] : null,
        ];
    }
}
