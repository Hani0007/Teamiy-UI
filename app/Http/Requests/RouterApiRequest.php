<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RouterApiRequest extends FormRequest
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
            'router_ssid' => ['required', 'string'],
            'branch_id'   => ['required', 'exists:branches,id'],
            'is_active'   => ['nullable', 'integer']
        ];
    }
    public function messages()
    {
        return [
            'router_ssid.required' => __('router_ssid_required'),
            'router_ssid.string'   => __('router_ssid_string'),
            'branch_id.required'   => __('branch_id_required_new'),
            'branch_id.exists'     => __('branch_id_exists_new'),
            'is_active.integer'    => __('is_active_integer_new'),
        ];
    }
    
    
}
