<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneratePayrollApi extends FormRequest
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
            'branch_id'     => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'year'          => 'required|integer',
            'month'         => 'nullable|string',
            'week'          => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required'     => __('branch_is_required'),
            'branch_id.exists'       => __('branch_id_exists'),
    
            'department_id.required' => __('department_required'),
            'department_id.exists'   => __('department_exists'),
    
            'year.required'          => __('year_required'),
            'year.integer'           => __('year_integer'),
    
            'month.string'           => __('month_string'),
    
            'week.string'            => __('week_string'),
        ];
    }
    
}
