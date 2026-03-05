<?php

namespace App\Requests\AssetManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetTypeRequest extends FormRequest
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

    public function prepareForValidation()
    {
        if (!auth('admin')->check() && auth()->check()) {
            $this->merge(['branch_id' => auth()->user()->branch_id]);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    // public function rules()
    // {
    //     return [
    //         'branch_id' => ['required','exists:branches,id'],
    //         'name' => ['required', 'string', 'max:255'],
    //         'is_active' => ['nullable', 'boolean', Rule::in([1, 0])],
    //     ];

    // }

    public function rules(): array
    {
        // If route model binding:
        // Route::put('asset-types/{asset_type}')
        $assetTypeId = optional($this->route('asset_type'))->id
                        ?? $this->route('asset_type');

        return [
            'branch_id' => ['required', 'exists:branches,id'],

            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('asset_types')
                    ->where(fn ($query) =>
                        $query->where('branch_id', $this->branch_id)
                    )
                    ->ignore($assetTypeId),
            ],

            'is_active' => ['nullable', 'boolean'],
        ];
    }
}

