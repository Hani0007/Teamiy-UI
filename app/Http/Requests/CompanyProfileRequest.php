<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyProfileRequest extends FormRequest
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
            'name'              => 'required|string|max:255',
            'industry_type'     => 'required|integer|max:255',
            'no_of_employees'   => 'required|integer',
            'contact_number'    => 'required|string|max:20',
            'country'           => 'required|integer',
            'country_code'      => 'required|string',
            'province'          => 'required|string',
            'city'              => 'required|string',
            'postal_code'       => 'required|string',
            'address'           => 'required|string',
            'website_url'       => 'nullable|string',
            'currency_preference'=> 'nullable|integer',
            'weekend'           => 'required|array',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',

        ];
    }

    
    public function messages(): array
    {
        return [
            'name.required'                => __('company_name_required'),
            'name.string'                  => __('company_name_string'),
            'name.max'                     => __('company_name_max'),

            'industry_type.required'       => __('industry_required'),
            'industry_type.integer'        => __('industry_integer'),
            'industry_type.max'            => __('industry_max'),

            'no_of_employees.required'     => __('employees_required'),
            'no_of_employees.integer'      => __('employees_integer'),

            'contact_number.required'      => __('contact_number_required'),
            'contact_number.string'        => __('contact_number_string'),
            'contact_number.max'           => __('contact_number_max'),

            'country.required'             => __('country_required'),
            'country.integer'              => __('country_integer'),

            'country_code.required'        => __('country_code_required'),
            'province.required'            => __('province_required'),
            'city.required'                => __('city_required'),
            'postal_code.required'         => __('postal_code_required'),
            'address.required'             => __('address_required'),

            'website_url.string'            => __('website_url_string'),
            'currency_preference.integer'  => __('currency_preference_integer'),

            'weekend.required'             => __('weekend_required'),
            'weekend.array'                => __('weekend_array'),

            'logo.image'                   => __('logo_image'),
            'logo.mimes'                   => __('logo_mimes'),
            'logo.max'                     => __('logo_max'),
        ];
    }
}
