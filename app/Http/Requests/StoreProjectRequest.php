<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
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
        $isUpdate = $this->filled('project_id');

        $rules = [
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],

            'branch_id' => [$isUpdate ? 'sometimes' : 'required', 'integer', 'exists:branches,id'],

            'department_ids' => [$isUpdate ? 'sometimes' : 'required', 'array', 'min:1'],
            'department_ids.*' => ['integer', 'exists:departments,id'],

            'name' => [$isUpdate ? 'sometimes' : 'required', 'string', 'max:255'],
            'start_date' => [
                $isUpdate ? 'sometimes' : 'required',
                'date',
                Rule::when(
                    request()->filled('deadline'),
                    ['before_or_equal:deadline']
                ),
            ],

            'deadline' => [
                $isUpdate ? 'sometimes' : 'nullable',
                'nullable',
                'date',
                Rule::when(
                    request()->filled('start_date'),
                    ['after_or_equal:start_date']
                ),
            ],

            'status' => [$isUpdate ? 'sometimes' : 'required', 'in:not_started,in_progress,completed,cancelled'],
            'priority' => [$isUpdate ? 'sometimes' : 'required', 'in:low,medium,high,urgent'],

            'estimated_hours' => [$isUpdate ? 'sometimes' : 'required', 'numeric', 'min:1'],

            'project_leader' => [$isUpdate ? 'sometimes' : 'required', 'array', 'min:1'],
            'project_leader.*' => ['integer', 'exists:users,id'],

            'description' => ['nullable', 'string'],

            'assigned_member' => [$isUpdate ? 'sometimes' : 'required', 'array', 'min:1'],
            'assigned_member.*' => ['integer', 'exists:users,id'],
            'attachments'   => ['sometimes', 'array', 'min:1'],
            'attachments.*' => [
                'sometimes',
                'file',
                'mimes:pdf,jpeg,png,jpg,docx,doc,xls,txt,webp,zip',
                'max:5048'
            ],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => __('Branch is required.'),
            'department_ids.required' => __('At least one department is required.'),
            'project_leader.required' => __('Project leader is required.'),
            'assigned_member.required' => __('At least one member must be assigned.'),
        ];
    }
}
