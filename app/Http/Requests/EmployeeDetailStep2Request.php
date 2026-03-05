<?php

namespace App\Http\Requests;

use App\Models\EmployeeAccount;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeDetailStep2Request extends FormRequest
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
            'employment_type' => ['nullable','required_unless:role_id,1', 'string', Rule::in(User::EMPLOYMENT_TYPE)],
            'joining_date' => 'nullable|date|before_or_equal:today',
            'supervisor_id' => 'nullable|exists:users,id',
            'office_time_id' => 'nullable|exists:office_times,id',
            'leave_allocated' => 'nullable|numeric|gte:0',
            'workspace_type' => ['nullable', 'boolean', Rule::in([1, 0])],
            'allow_holiday_check_in' => ['nullable'],
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'required|date',
            'pay_grade' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'bank_account_no' => 'nullable|numeric',
            'bank_account_type' => ['nullable', 'string'],
            'account_holder' => 'nullable|string',
            'leave_type_id.*' => 'nullable',
            'days.*' => 'nullable|numeric|gte:0',
            'is_active.*' => 'nullable',
            'upload_contract' => 'nullable',
            'employee_document' => 'nullable',
            'nfc_card' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'days.*.numeric' => 'Each leave day must be a valid number.',
            'days.*.gte' => 'Each leave days must be greater than or equal to 0.',
        ];
    }
}
