<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskManagementApiRequest extends FormRequest
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
            'project_id'       => 'required|integer|exists:projects,id',
            'name'             => 'required|string|max:255',
            'start_date'       => 'required|date|before_or_equal:end_date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'priority'         => 'required|in:low,medium,high,urgent',
            'status'           => 'required|in:not_started,in_progress,completed,cancelled',
            'description'      => 'nullable|string',
            'assigned_member' => 'required|array|min:1',
            'assigned_member.*' => 'integer|exists:users,id',
            'is_active'        => 'required|boolean',
            'attachments'   => ['sometimes', 'array', 'min:1'],
            'attachments.*' => [
                'sometimes',
                'file',
                'mimes:pdf,jpeg,png,jpg,docx,doc,xls,txt,webp,zip',
                'max:5048'
            ],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'project_id.required'       => 'Project ID is required.',
    //         'project_id.exists'         => 'Selected project does not exist.',
    //         'name.required'             => 'Task name is required.',
    //         'start_date.before_or_equal'=> 'Start date must be before or equal to end date.',
    //         'end_date.after_or_equal'   => 'End date must be after or equal to start date.',
    //         'priority.in'               => 'Priority must be one of: low, medium, high, urgent.',
    //         'status.in'                 => 'Invalid status value.',
    //         'assigned_member.required' => 'At least one member must be assigned.',
    //         'assigned_member.*.exists' => 'One or more selected members do not exist.',
    //         'is_active.boolean'         => 'Active status must be true or false.',
    //     ];
    // }
    public function messages(): array
    {
        return [
            'project_id.required'        => __('project_id_required'),
            'project_id.exists'          => __('project_id_exists'),
            'name.required'              => __('task_name_required'),
            'start_date.before_or_equal' => __('start_date_before_or_equal'),
            'end_date.after_or_equal'    => __('end_date_after_or_equal'),
            'priority.in'                => __('invalid_priority'),
            'status.in'                  => __('invalid_status'),
            'assigned_member.required'   => __('assigned_member_required'),
            'assigned_member.*.exists'   => __('assigned_member_exists'),
            'is_active.boolean'          => __('is_active_boolean'),
        ];
    }

}
