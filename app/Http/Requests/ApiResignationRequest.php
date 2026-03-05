<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiResignationRequest extends FormRequest
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
            'employee_id' => 'required|exists:users,id',
            'resignation_date' => 'required|date',
            'last_working_day' => 'required|date|after_or_equal:resignation_date',
            'reason' => 'required|string|max:1000',
            'status' => 'required|in:pending,approved,rejected,onReview',
            'branch_id' => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'document'      => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => __('employee_required'),
            'employee_id.exists' => __('employee_not_exist'),

            'resignation_date.required' => __('resignation_date_required'),
            'resignation_date.date' => __('resignation_date_invalid'),

            'last_working_day.required' => __('last_working_day_required'),
            'last_working_day.date' => __('last_working_day_invalid'),
            'last_working_day.after_or_equal' => __('last_working_day_after_resignation'),

            'reason.required' => __('reason_required'),
            'reason.string' => __('reason_invalid'),
            'reason.max' => __('reason_too_long'),

            'status.required' => __('status_required'),
            'status.in' => __('status_invalid'),

            'branch_id.required' => __('branch_required'),
            'branch_id.exists' => __('branch_not_exist'),

            'department_id.required' => __('department_required'),
            'department_id.exists' => __('department_not_exist'),
        ];
    }
}
