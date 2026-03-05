<?php

namespace App\Http\Resources;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectManagementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $departmentIds = is_array($this->department_ids)
                            ? $this->department_ids
                            : json_decode($this->department_ids, true);

        $departments = Department::whereIn('id', $departmentIds ?? [])->get(['id', 'dept_name']);

        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'start_date'      => $this->start_date,
            'deadline'        => $this->deadline,
            'estimated_hours' => $this->estimated_hours,
            'status'          => $this->status,
            'priority'        => $this->priority,
            'description'     => $this->description ?? '',
            'is_active'       => (bool) $this->is_active,

            'branch' => [
                'id'   => $this->branch->id ?? null,
                'name' => $this->branch->name ?? null,
            ],

            'departments' => $departments->map(fn($dept) => [
                'id'   => $dept->id,
                'name' => $dept->dept_name,
            ]),

            'project_leaders' => $this->projectLeaders->map(fn($leader) => [
                'id'    => $leader->user->id ?? null,
                'name'  => $leader->user->name ?? null
            ]),

            'assigned_members' => $this->assignedMembers->map(fn($member) => [
                                    'id'     => $member->user->id ?? null,
                                    'name'   => $member->user->name ?? null,
                                    'avatar' => $member->user
                                        ? ($member->user->avatar
                                            ? asset('uploads/user/avatar/' . $member->user->avatar)
                                            : null)
                                        : null,
                                ]),

            'created_by' => [
                'id'   => $this->creator->id ?? null,
                'name' => $this->creator->name ?? null
            ],
            'attachments' => $this->projectAttachments->map(function ($file) {
                return [
                    'id'  => $file->id,
                    'url' => url(\App\Models\Attachment::UPLOAD_PATH . $file->attachment),
                ];
            }),
        ];
    }
}
