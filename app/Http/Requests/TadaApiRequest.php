<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TadaApiRequest extends FormRequest
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
            'branch_id'     => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'title'         => ['required','string','max:400'],
            'employee_id'   => [
                'required',
                Rule::exists('users','id')
                    ->where('is_active',1)
                    ->where('status','verified')
            ],
            'total_expense' => ['required','numeric','digits_between:1,7'],
            'description'   => ['nullable','string'],
        ];

        if (isset($this->tada_id)) {
            // On update → require attachments if present
            $rules['attachments'] = ['sometimes','array','min:1'];
            $rules['attachments.*'] = ['sometimes','file','mimes:jpeg,png,jpg,docx,doc,xls,pdf','max:5048'];
        } else {
            // On create → allow nullable, but validate format if provided
            $rules['attachments'] = ['nullable','array','min:1'];
            $rules['attachments.*'] = ['nullable','file','mimes:jpeg,png,jpg,docx,doc,xls,pdf','max:5048'];
        }

        return $rules;
    }
}
