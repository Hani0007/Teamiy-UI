<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeaveApprovalRequest extends FormRequest
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
            //'subject' => ['required'],
            //'leave_type_id' => ['required'],
            'branch_id' => ['required','exists:branches,id'],
            'department_id' => ['required', 'array', 'min:1'],
            'department_id.*' => [
                'required',
                Rule::exists('departments', 'id')->where('is_active', 1)
            ],

            'approver' => ['required', 'string']
        ];
    }
}
