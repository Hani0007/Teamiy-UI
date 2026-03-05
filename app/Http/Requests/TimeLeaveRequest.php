<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeLeaveRequest extends FormRequest
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
            'branch_id' => ['required','exists:branches,id'],
            'department_id' => ['required','exists:departments,id'],
            'issue_date' => ['required','date'] ,
            'reasons' => ['required','string','min:10'],
            'leave_from' => 'required',
            'leave_to' => ['required','after:leave_from'],
            'requested_by' => 'required',
        ];
    }
}
