<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'              => $this->user_name,
            'worked_hours'      => $this->worked_hour,
            'check_in_at'       => $this->check_in_at,
            'check_out_at'      => $this->check_out_at,
            'attendance_status' => $this->attendance_status,
            'shift'             => $this->shift,
            'branch'            => $this->branch_id ?
                                    [
                                        'id' => $this->branch_id,
                                        'name' => $this->branch_name
                                    ] : null,
            'department'        => $this->department_id ?
                                    [
                                        'id' => $this->department_id,
                                        'name' => $this->department_name
                                    ] : null,
            'user_id'          => $this->user_id,
            'joining_date'     => $this->joining_date,
            'attendance_by' => 'Admin',

        ];
    }
}
