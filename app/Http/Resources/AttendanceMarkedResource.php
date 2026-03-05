<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceMarkedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'check_in_at' => $this->check_in_at,
            'check_out_at' => $this->check_out_at,
            'attendance_status' => $this->attendance_status == 1 ? 'approved' : 'pending',
            'attendance_by' => 'Admin',
            'employee' => $this->employee ?
                            [
                                'id' => $this->employee->id,
                                'name' => $this->employee->name
                            ] : null,
        ];
    }
}
