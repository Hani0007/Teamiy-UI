<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeesListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'email'   => $this->work_email,
            'phone'   => $this->phone,
            'avatar'           => $this->avatar
                                    ? asset('uploads/user/avatar/' . $this->avatar)
                                    : null,
            'role' => $this->roles->isNotEmpty()
                ? [
                    'id'   => $this->roles->first()->id,
                    'name' => $this->roles->first()->name,
                ]
                : null,
            'branch' => $this->branch
                ? ['id' => $this->branch->id, 'name' => $this->branch->name]
                : null,
            'department' => $this->department
                ? ['id' => $this->department->id, 'name' => $this->department->dept_name]
                : null,
            'is_active' => $this->is_active,
            'nationality' => $this->nationality,
            'lang_code'   => $this->lang_code,
            'office_time'   => $this->office_time_id,
            'remarks' => $this->remarks
        ];
    }
}
