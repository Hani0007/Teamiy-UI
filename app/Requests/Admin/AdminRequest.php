<?php

namespace App\Requests\Admin;



use App\Helpers\AppHelper;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
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
        $isUpdate = !empty($this->id);

        $user = auth()->user();
        $roleName = $user->getRoleNames()->first();

        if ($roleName !== 'super-admin') {
            $userId = $user->parent_id;
        } else {
            $user->load('company');
            $userId = $user->parent_id;
        }

        $rules = [
            'name'     => ['required', 'string', 'max:100', 'min:2'],
            'email'    => [
                'required',
                'email',
                'max:255',
                Rule::unique('admins', 'email')
                    ->ignore($this->id)
                    ->where(function ($query) use ($userId) {
                        if ($userId) {
                            $query->where('id', $userId);
                        }
                    }),
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('admins', 'username')
                    ->ignore($this->id)
                    ->where(function ($query) use ($userId) {
                        if ($userId) {
                            $query->where('id', $userId);
                        }
                    }),
            ],
            'avatar'   => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp', 'max:5048'],
            'role_id'  => ['required', 'exists:roles,id'],
            'is_active'=> ['nullable'],
        ];

        if ($isUpdate) {
            $rules['password'] = ['nullable', 'min:6'];
        } else {
            $rules['password'] = ['required', 'min:6'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required'     => __('name_required'),
            'name.string'       => __('name_string'),
            'name.max'          => __('name_max'),
            'name.min'          => __('name_min'),

            'email.required'    => __('email_required'),
            'email.email'       => __('email_invalid'),
            'email.max'         => __('email_max'),
            'email.unique'      => __('email_unique'),

            'username.required' => __('username_required'),
            'username.string'   => __('username_string'),
            'username.max'      => __('username_max'),
            'username.unique'   => __('username_unique'),

            'password.required' => __('password_required'),
            'password.min'      => __('password_min'),

            'avatar.file'       => __('avatar_file'),
            'avatar.mimes'      => __('avatar_mimes'),
            'avatar.max'        => __('avatar_max'),

            'role_id.required'  => __('role_required'),
            'role_id.exists'    => __('role_exists'),
        ];
    }
}
