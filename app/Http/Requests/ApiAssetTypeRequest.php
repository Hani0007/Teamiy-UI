<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiAssetTypeRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'branch_id' => 'required|integer|exists:branches,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('name_required'),
            'branch_id.required' => __('branch_required'),
            'branch_id.exists' => __('branch_not_exist'),
        ];
    }
}
