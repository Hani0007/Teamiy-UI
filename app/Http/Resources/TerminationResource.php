<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TerminationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'employee'          => $this->employee
                                    ? [
                                        'id'        => $this->employee->id,
                                        'name'      => $this->employee->name
                                    ] : null,
            'notice_date'       => $this->notice_date,
            'termination_date'  => $this->termination_date,
            'reason'            => $this->reason,
            'status'            => $this->status,
            'created_by'        => $this->admin
                                    ? [
                                        'id'    => $this->admin->id,
                                        'name'  => $this->admin->name
                                    ] : null,
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
        ];
    }
}
