<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payroll_type' => 'required|in:annual,hourly',
            'annual_salary' => 'required|numeric|min:0',
            'payment_type' => 'required|in:monthly,weekly',
            'tax' => 'required|numeric|min:0|max:100',
            'is_overtime' => 'required|boolean',
            'hour_rate' => 'nullable|numeric|min:0',
            'weekly_working_hours' => 'nullable|numeric|min:0',
            'employee_id' => 'required|integer'
        ];
    }

    /**
     * Cast numeric values to float before passing to controller.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'annual_salary' => $this->annual_salary !== null ? (float) $this->annual_salary : null,
            'hourly_rate' => $this->hourly_rate !== null ? (float) $this->hourly_rate : null,
            'tax' => $this->tax !== null ? (float) $this->tax : null,
            'is_overtime' => $this->is_overtime == '1' || $this->is_overtime === true,
        ]);
    }

    public function messages(): array
    {
        return [
            'payroll_type.required' => __('payroll_type_required'),
            'payroll_type.in' => __('payroll_type_invalid'),
    
            'annual_salary.required' => __('annual_salary_required'),
            'annual_salary.numeric' => __('annual_salary_numeric'),
            'annual_salary.min' => __('annual_salary_min'),
    
            'payment_type.required' => __('payment_type_required'),
            'payment_type.in' => __('payment_type_invalid'),
    
            'tax.required' => __('tax_required'),
            'tax.numeric' => __('tax_numeric'),
            'tax.min' => __('tax_min'),
            'tax.max' => __('tax_max'),
    
            'is_overtime.required' => __('is_overtime_required'),
            'is_overtime.boolean' => __('is_overtime_boolean'),
    
            'hour_rate.numeric' => __('hour_rate_numeric'),
            'hour_rate.min' => __('hour_rate_min'),
    
            'weekly_working_hours.numeric' => __('weekly_working_hours_numeric'),
            'weekly_working_hours.min' => __('weekly_working_hours_min'),
    
            'employee_id.required' => __('employee_required'),
            'employee_id.integer' => __('employee_integer'),
        ];
    }

}
