<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiTeamMeetingRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_start_time' => 'required|date_format:H:i:s',
            'meeting_published_at' => 'required|date_format:Y-m-d H:i:s',
            'branch_id' => 'required|integer|exists:branches,id',
            'meeting_participants' => 'required|array|min:1',
            'meeting_participants.*' => 'integer|exists:users,id',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'meeting_link'  => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => __('title_required'),
            'description.required' => __('description_required'),
            'venue.required' => __('venue_required'),
            'meeting_date.required' => __('meeting_date_required'),
            'meeting_start_time.required' => __('meeting_start_time_required'),
            'branch_id.required' => __('branch_required'),
            'meeting_participants.required' => __('participants_required'),
        ];
    }
}
