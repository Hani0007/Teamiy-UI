<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskFilterRequest extends FormRequest
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
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            // 'priority'   => ['nullable', 'string', 'in:low,medium,high,urgent'],
            // 'status'     => ['nullable', 'string', 'in:pending,in_progress,completed,cancelled'],
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'The project field is required.',
            'project_id.exists'   => 'The selected project does not exist.',
            //'priority.in'         => 'Priority must be one of: low, medium, high, or urgent.',
            //'status.in'           => 'Status must be one of: pending, in_progress, completed, or cancelled.',
        ];
    }
}
