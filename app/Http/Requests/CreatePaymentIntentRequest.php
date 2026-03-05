<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentIntentRequest extends FormRequest
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
            'plan_id' => ['required', 'integer'],
            'total_employees' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'string', 'min:0'],
            'cycle' => ['required', 'string', 'in:monthly,yearly'],
        ];
    }

    public function messages(): array
    {
        return [
            'plan_id.required' => 'Plan ID is required.',
            'employees.required' => 'Please enter the number of employees.',
            'price.required' => 'Price is required.',
            'cycle.required' => 'Billing cycle is required.',
        ];
    }
}
