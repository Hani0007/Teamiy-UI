<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
            'dept_name'    => 'required|string',
            'slug'         => 'required|string',
            //'address'      => 'nullable|string',
            'phone'        => 'nullable|string',
            'is_active'    => 'required',
            'dept_head_id' => 'nullable',
            'branch_id'    => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'dept_name.required'    => __('dept_name_required'),
            'dept_name.string'      => __('dept_name_string'),

            'slug.required'         => __('slug_required'),
            'slug.string'           => __('slug_string'),

            'phone.string'          => __('phone_string'),

            'is_active.required'    => __('is_active_required'),

            'dept_head_id.integer'  => __('dept_head_id_integer'),

            'branch_id.required'    => __('branch_id_required'),
            'branch_id.exists'      => __('branch_id_exists'),
        ];
    }
}
