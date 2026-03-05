<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FetchMembersRequest extends FormRequest
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
            'branch_id' => 'required|integer|exists:branches,id',
            'department_ids' => 'required|array|min:1',
            'department_ids.*' => 'integer|exists:departments,id',
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => __('branch_id_required'),
            'branch_id.integer' => __('branch_id_integer'),
            'branch_id.exists' => __('branch_not_found'),

            'department_ids.required' => __('department_ids_required'),
            'department_ids.array' => __('department_ids_array'),
            'department_ids.min' => __('department_ids_min'),
            'department_ids.*.integer' => __('department_id_integer'),
            'department_ids.*.exists' => __('department_not_found'),
        ];
    }
}
