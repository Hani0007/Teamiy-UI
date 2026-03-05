<?php

namespace App\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name' => 'required|string|max:255',
            // 'industry_type' => 'required|string|max:255',
            'industry_type' => 'required|exists:industry_types,id',
            'no_of_employees' => 'required|integer|min:1',
            'country_code' => 'required',
            'contact_number' => 'required',
            'country' => 'required',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            'website_url' => 'nullable|max:255',
            'currency_preference' => 'nullable|string',
            'weekend'   => 'array',
            'vat_number' => 'nullable|string|max:255',
            'company_registration' => 'nullable|string|max:255',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:5048'
        ];

        // $rules = [
        //     'name' => 'required|string',
        //     'owner_name' => 'required|string',
        //     'address' => 'required|string',
        //     'phone' => 'required|numeric',
        //     'is_active' => ['nullable', 'boolean', Rule::in([1, 0])],
        //     'website_url' => ['nullable','url'],
        //     'weekend' => 'nullable|array',
        //     'weekend.*' => 'nullable|numeric|digits_between:0,6',
        // ];
        // if ($this->isMethod('put')) {
        //     $rules['logo'] = ['sometimes','file', 'mimes:jpeg,png,jpg,gif,svg','max:5048'];
        //     $rules['email'] = ['required','email',Rule::unique('users')->ignore($this->id)];
        // } else {
        //     $rules['logo'] = ['required','file', 'mimes:jpeg,png,jpg,gif,svg','max:5048'];
        //     $rules['email'] = [ 'required','email','unique:users,email' ];
        // }
        // return $rules;

    }
     public function messages(): array
    {
        return [
            'industry_type.required' => 'Please select an industry type.',
            'industry_type.exists' => 'Selected industry type is invalid.',
        ];
    }

}









