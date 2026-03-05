<?php

namespace App\Http\Requests;

use App\Enum\LeaveGenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApiLeaveTypesRequest extends FormRequest
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
        $rules = [
            'branch_id'       => ['required','exists:branches,id'],
            'leave_paid'      => ['required', 'boolean', Rule::in([1, 0])],
            'leave_allocated' => [
                'nullable',
                'required_if:leave_paid,1',
                'numeric',
                function ($attribute, $value, $fail) {
                    if (request('leave_paid') == 1 && $value < 1) {
                        $fail('The '.$attribute.' must be at least 1 when leave is paid.');
                    }
                },
            ],
            'gender' => [
                'required',
                Rule::in(array_column(LeaveGenderEnum::cases(), 'value'))
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('leave_types', 'name')
                    ->ignore($this->leave_type_id) // allow updating same record
                    ->where(fn($query) => $query->where('company_id', $this->company_id ?? request('company_id'))),
            ],
        ];

        return $rules;
    }

    protected function prepareForValidation()
    {
        if (auth()->guard('admin-api')->check()) {
            $user = auth()->guard('admin-api')->user();

            $companyId = $user->getRoleNames()->first() !== 'super-admin'
                ? optional(\App\Models\Company::where('admin_id', $user->parent_id)->first())->id
                : optional($user->company)->id;

            $this->merge(['company_id' => $companyId]);
        }
    }
}
