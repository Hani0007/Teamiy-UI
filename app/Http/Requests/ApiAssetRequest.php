<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiAssetRequest extends FormRequest
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
            'name'               => 'required|string|max:255',
            'type_id'            => 'required|integer|exists:asset_types,id',

            'asset_code'         => 'nullable|string|max:255',
            'image'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'asset_serial_no'    => 'nullable|string|max:255',
            'is_working'         => 'required|in:0,1',

            'purchased_date'     => 'required|date|before_or_equal:today',

            'warranty_available' => 'required|in:0,1',
            'warranty_end_date'  => 'required_if:warranty_available,1|nullable|date|after_or_equal:purchased_date',

            'is_available'       => 'required|in:0,1',
            'note'               => 'nullable|string',

            'branch_id'          => 'required|integer|exists:branches,id',

            'is_repaired'        => 'required|in:0,1',
        ];

    }

    public function messages()
    {
        return [
            'name.required'               => __('Name is required.'),

            'type_id.required'            => __('Asset type is required.'),
            'type_id.exists'              => __('Selected asset type does not exist.'),

            'purchased_date.before_or_equal'
                                        => __('Purchased date cannot be in the future.'),
            'warranty_end_date.required_if'
                                        => __('Warranty end date is required when warranty is available.'),

            'warranty_end_date.after_or_equal'
                                        => __('Warranty end date must be after or equal to purchased date.'),

            'branch_id.required'          => __('Branch is required.'),
            'branch_id.exists'            => __('Selected branch does not exist.'),
        ];
}

}
