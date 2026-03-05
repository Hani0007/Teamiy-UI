<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiNoticeBoard extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'notice_publish_date' => 'required|date',
            'is_active' => 'required|boolean',
            'branch_id' => 'required|integer|exists:branches,id',
            'notice_receivers' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('title_required'),
            'description.required' => __('description_required'),
            'notice_publish_date.required' => __('publish_date_required'),
            'notice_publish_date.date' => __('publish_date_invalid'),
            'is_active.required' => __('status_required'),
            'is_active.boolean' => __('status_invalid'),
            'branch_id.required' => __('branch_required'),
            'branch_id.exists' => __('branch_not_exist'),
        ];
    }
}
