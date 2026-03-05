<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResource extends JsonResource
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
            'type' => $this->whenLoaded('leaveType', fn () => [
                'id'   => $this->leaveType->id,
                'name' => $this->leaveType->name,
            ]),
            'from' => $this->leave_from
                        ? \Carbon\Carbon::parse($this->leave_from)->format('Y-m-d')
                        : null,
            'to'   => $this->leave_to
                        ? \Carbon\Carbon::parse($this->leave_to)->format('Y-m-d')
                        : null,
            'requested_date' => $this->leave_requested_date
                        ? \Carbon\Carbon::parse($this->leave_requested_date)->format('Y-m-d')
                        : null,
            'requested_by' => $this->whenLoaded('employee', fn () => [
                'id'   => $this->employee->id,
                'name' => $this->employee->name,
            ]),
            'status'            => $this->status,
            'requested_days'    => $this?->leaveType?->leave_allocated ?? null,
            'reason'            => $this->reasons ?? null,
            'branch'            => $this->branch
                                    ? [
                                        'id'    => $this->branch->id,
                                        'name'  => $this->branch->name
                                    ] : null,
            'department'        => $this->department
                                    ? [
                                        'id'    => $this->department->id,
                                        'name'  => $this->department->dept_name
                                    ] : null,
            'employee'          => $this->employee
                                    ? [
                                        'id'    => $this->employee->id,
                                        'name'  => $this->employee->name
                                    ] : null,
            'admin_remarks' =>  $this->leaveRequestApproval
                                    ? $this->leaveRequestApproval->reason
                                    : null,
            'document' => $this->document 
                                    ? url('uploads/documents/leave_requests/' . $this->document)
                                    : null,
                                    
        ];
    }
}
