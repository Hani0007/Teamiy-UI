<?php

namespace App\Requests\TeamMeeting;

use App\Helpers\AppHelper;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class TeamMeetingRequest extends FormRequest
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
        $meetingDate = $this->meeting_date;
        
        // Handle both mm/dd/yyyy and m/d/Y formats
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $meetingDate, $matches)) {
            $year = (int)$matches[3];
            $month = (int)$matches[1];
            $day = (int)$matches[2];
            
            // Validate date range
            $currentYear = (int)date('Y');
            if ($year < $currentYear || $year > 2089 || $month < 1 || $month > 12 || $day < 1 || $day > 31) {
                throw new \Illuminate\Validation\ValidationException([
                    'meeting_date' => 'Please select a valid date between ' . $currentYear . ' and 2089'
                ]);
            }
            
            // Convert to Y-m-d format for validation
            $this->merge([
                'meeting_date' => sprintf('%04d-%02d-%02d', $year, $month, $day),
                'meeting_start_time' => date('H:i',strtotime($this->meeting_start_time)),
            ]);
        } else {
            // Original logic for BS dates
            $this->merge([
                'meeting_start_time' => date('H:i',strtotime($this->meeting_start_time)),
                'meeting_date' => (AppHelper::ifDateInBsEnabled()) ? AppHelper::dateInYmdFormatNepToEng($this->meeting_date) : $this->meeting_date,
            ]);
        }

        if (!auth('admin')->check() && auth()->check()) {
            $this->merge(['branch_id' => auth()->user()->branch_id]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'title' => 'required|string',
            'description' => 'nullable|string|min:10',
            'venue' => 'required|string|min:3',
            'department' => 'required|array|min:1',
            'department.*.department_id' => 'required|exists:departments,id',
            'participator' => 'required|array|min:1',
            'participator.*.meeting_participator_id' => 'required|exists:users,id',
            'meeting_date' => 'required|date|after_or_equal:today',
            'meeting_start_time' => 'required|date_format:H:i',
            'image' => ['sometimes', 'file', 'mimes:jpeg,png,jpg,svg|max:3048'],
            'notification'=> 'nullable',
        ];
    }
    
}
