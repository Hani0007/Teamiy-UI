<?php

namespace App\Http\Requests;

use App\Models\LeaveRequestMaster;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeaveRequestApisRequest extends FormRequest
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
            'leave_from'     => 'required|date',
            'leave_to'       => 'required|date|after_or_equal:leave_from',
            'leave_type_id'  => ['required', 'exists:leave_types,id'],
            'reasons'        => 'required|string',
            'branch_id'      => 'required|exists:branches,id',
            'department_id'  => 'required|exists:departments,id',
            'requested_by'   => 'required|exists:users,id',
            'document'  => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', 

        ];
    }
}
