<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Registeration extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'same:password'],
            //'role_in_company' => ['required', 'exists:roles,id'],
            // Company fields
            'name' => ['required', 'string', 'max:255'],
            //'industry_type' => ['required', 'string', 'max:255'],
            'no_of_employees' => ['required', 'integer', 'min:1'],
            'contact_number' => ['required', 'numeric'],
            'country_code' => ['required'],
            // 'province' => ['required', 'string', 'max:100'],
            // 'city' => ['required', 'string', 'max:100'],
            // 'postal_code' => ['required', 'integer'],
            // 'address' => ['required', 'string', 'max:255'],
            // 'website_url' => ['nullable', 'url', 'max:255'],
            // 'currency_preference' => ['nullable'],
            'terms_conditions' => ['required'],
        ];
    }
}
