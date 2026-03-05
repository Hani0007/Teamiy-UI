<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
            'name'                     => 'required|string|max:255',
            'address'                  => 'required|string|max:500',
            'phone'                    => 'required|string',
            'branch_head_id'           => 'nullable',
            'branch_location_latitude' => 'nullable',
            'branch_location_longitude'=> 'nullable',
            'is_active'                => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'      => __('branch_name_required'),
            'name.string'        => __('branch_name_string'),
            'name.max'           => __('branch_name_max'),
    
            'address.required'   => __('branch_address_required'),
            'address.string'     => __('branch_address_string'),
            'address.max'        => __('branch_address_max'),
    
            'phone.required'     => __('branch_phone_required'),
            'phone.string'       => __('branch_phone_string'),
    
            'branch_head_id.nullable' => __('branch_head_nullable'),
            'branch_location_latitude.nullable' => __('branch_latitude_nullable'),
            'branch_location_longitude.nullable' => __('branch_longitude_nullable'),
    
            'is_active.required' => __('branch_is_active_required'),
        ];
    }
    
}
