<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeePersonalDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'employee_code'  => $this->employee_code,
            'name'           => $this->name,
            'email'          => $this->email,
            'work_email'     => $this->work_email,
            'username'       => $this->username,
            'nationality'    => $this->nationality,
            'address'        => $this->address,
            'dob'            => $this->dob,
            'phone'          => $this->phone,
            'gender'         => $this->gender,
            'marital_status' => $this->marital_status,
            'role_id'        => $this->role_id,
            'remarks'    => $this->remarks,
            'role' => $this->whenLoaded('role', function () {
                return [
                    'id'   => $this->role->id,
                    'name' => $this->role->name,
                ];
            }),
            'branch' => $this->whenLoaded('branch', function () {
                return [
                    'id'   => $this->branch->id,
                    'name' => $this->branch->name,
                ];
            }),
            'department' => $this->whenLoaded('department', function () {
                return [
                    'id'   => $this->department->id,
                    'name' => $this->department->dept_name,
                ];
            }),
            'designation' => $this->whenLoaded('post', function () {
                return [
                    'id'   => $this->post->id,
                    'name' => $this->post->post_name,
                ];
            }),
            'avatar'       => $this->avatar
                                    ? asset('uploads/user/avatar/' . $this->avatar)
                                    : null,
        ];
    }
}
