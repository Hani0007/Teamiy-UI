<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HolidayApiRequest extends FormRequest
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
            'event'             => 'required|string',
            'note'              => 'nullable|string|max:500',
            'is_public_holiday' => 'nullable',
        ];

        if ($this->input('holiday_id')) {
            $rules['event_date'] = [
                'required',
                'date',
                Rule::unique('holidays', 'event_date')->ignore($this->input('holiday_id'), 'id'),
            ];
        } else {
            $rules['event_date'] = [
                'required',
                'date',
                'after:yesterday',
                Rule::unique('holidays', 'event_date'),
            ];
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'event_date.after' => __('event_date_after_yesterday'),
        ];
    }

}
