<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class EmployeePersonalDetail extends FormRequest
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
        $employeeId = $this->employee_id ?? null;

        return [
            'employee_code' => 'nullable',
            'name'          => 'required|string|max:100|min:2',
            'work_email' => [
                'required',
                'email',
                Rule::unique('users', 'work_email')->ignore($employeeId),
            ],

            'password' => $employeeId
                ? 'nullable|string|min:4'
                : 'required|string|min:4',

            'username' => [
                'required',
                'string',
                Rule::unique('users')->ignore($employeeId),
            ],

            'nationality'     => 'nullable',
            'address'         => 'nullable',
            'dob'             => 'nullable',
            'phone'           => 'nullable',
            'gender'          => 'nullable',
            'marital_status'  => 'nullable',
            'role_id'         => 'nullable',
            'remarks'     => 'nullable',
            'avatar'          => 'nullable',
            'branch_id' => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:posts,id',
        ];
    }

    public function messages(): array
{
    return [
        'name.required'             => __('name_required'),
        'name.string'               => __('name_string'),
        'name.max'                  => __('name_max'),
        'name.min'                  => __('name_min'),

        'work_email.required'       => __('work_email_required'),
        'work_email.email'          => __('work_email_invalid'),
        'work_email.unique'         => __('work_email_unique'),

        'password.required'         => __('password_required'),
        'password.min'              => __('password_min'),

        'username.required'         => __('username_required'),
        'username.string'           => __('username_string'),
        'username.unique'           => __('username_unique'),

        'branch_id.required'        => __('branch_id_required'),
        'branch_id.exists'          => __('branch_id_exists'),

        'department_id.required'    => __('department_id_required'),
        'department_id.exists'      => __('department_id_exists'),

        'designation_id.required'   => __('designation_id_required'),
        'designation_id.exists'     => __('designation_id_exists'),
    ];
}

}
