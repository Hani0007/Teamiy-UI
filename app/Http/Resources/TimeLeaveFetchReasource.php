<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeLeaveFetchReasource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'leave_date'  => $this->issue_date
                                ? \Carbon\Carbon::parse($this->issue_date)->format('Y-m-d')
                                : null,
            'from'        => $this->start_time
                                ? \Carbon\Carbon::parse($this->start_time)->format('H:i')
                                : null,
            'to'          => $this->end_time
                                ? \Carbon\Carbon::parse($this->end_time)->format('H:i')
                                : null,
            'status'      => $this->status,
            'reason'      => $this->reasons,
            'requested_by'=> $this->employee
                                ? [
                                    'id'   => $this->employee->id,
                                    'name' => $this->employee->name,
                                ]
                                : null,
            'admin_remarks' =>  $this->leaveRequestApproval
                                    ? $this->leaveRequestApproval->reason
                                    : null,
            'branch'=> $this->branch
                                ? [
                                    'id'   => $this->branch->id,
                                    'name' => $this->branch->name,
                                ]
                                : null,
        ];
    }
}
