<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DesignationRequest extends FormRequest
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
            'designation_name' => [
                'required',
                'string',
            ],
            'is_active'     => 'required|boolean',
            'branch_id'     => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
        ];
    }

    public function messages(): array
    {
        return [
            'designation_name.required' => __('designation_name_required'),
            'designation_name.string'   => __('designation_name_string'),
    
            'is_active.required'        => __('is_active_required'),
            'is_active.boolean'         => __('is_active_boolean'),
    
            'branch_id.required'        => __('branch_id_required'),
            'branch_id.exists'          => __('branch_id_exists'),
    
            'department_id.required'    => __('department_id_required'),
            'department_id.exists'      => __('department_id_exists'),
        ];
    }
    
}
