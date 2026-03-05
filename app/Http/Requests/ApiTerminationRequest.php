<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiTerminationRequest extends FormRequest
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
            'employee_id'      => 'required|exists:users,id',
            'notice_date'      => 'required|date|before_or_equal:termination_date',
            'termination_date' => 'required|date|after_or_equal:notice_date',
            'reason'           => 'required|string|max:1000',
            'status'           => 'required|in:pending,approved,cancelled,onReview',
            'branch_id'        => 'required|exists:branches,id',
            'department_id'    => 'required|exists:departments,id',
            'termination_id'   => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required'      => __('employee_required'),
            'employee_id.exists'        => __('employee_not_exist'),
            'notice_date.required'      => __('notice_date_required'),
            'termination_date.required' => __('termination_date_required'),
            'reason.required'           => __('reason_required'),
            'status.in'                 => __('status_invalid'),
            'branch_id.exists'          => __('branch_not_exist'),
            'department_id.exists'      => __('department_not_exist'),
        ];
    }
}
