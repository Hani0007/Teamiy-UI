<?php

namespace App\Http\Resources;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $departments = Department::whereIn('id', $this->department_ids)->get(['id', 'dept_name']);

        // Eager-loaded tasks will be available in $this->tasks, so no extra query is needed
        $tasks = $this->tasks ?? collect();

        // Count total and completed tasks
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', 'completed')->count();

        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'start_date'      => $this->start_date,
            'deadline'        => $this->deadline,
            'estimated_hours' => $this->estimated_hours,
            'status'          => $this->status,
            'priority'        => $this->priority,
            'description'     => $this->description,
            'is_active'       => $this->is_active,
            'branch'          => $this->branch
                ? [
                    'id'   => $this->branch->id,
                    'name' => $this->branch->name,
                ]
                : null,
            'departments'     => $departments->map(fn($dept) => [
                'id'   => $dept->id,
                'name' => $dept->dept_name,
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
            'total_tasks'     => $totalTasks,
            'completed_tasks' => $completedTasks,
            'document' => $this->document
                          ? url('uploads/documents/projects/' . $this->document)
                          : null,

        ];
    }
}
