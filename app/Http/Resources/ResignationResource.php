<?php

namespace App\Http\Resources;

use App\Models\Resignation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResignationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                        => $this->id,
            'employee'                  => $this->employee
                                                ? [
                                                    'id'    => $this->employee->id,
                                                    'name'  => $this->employee->name
                                                ] : null,
            'resignation_date'          => $this->resignation_date,
            'last_working_day'          => $this->last_working_day,
            'reason'                    => $this->reason,
            'status'                    => $this->status,
            'created_by'                => $this->admin
                                                ? [
                                                    'id'           => $this->admin->id,
                                                    'name'         => $this->admin->name
                                                 ] : null,
            'branch'                    => $this->branch
                                                ? [
                                                    'id'        => $this->branch->id,
                                                    'name'      => $this->branch->name
                                                ] : null,
            'department'                => $this->department
                                                ? [
                                                    'id'        => $this->department->id,
                                                    'name'      => $this->department->dept_name
                                                ] : null,
            'document'                  => $this->document
                                                    ? asset(Resignation::UPLOAD_PATH . $this->document)
                                                    : null,
        ];
    }
}
