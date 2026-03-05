<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskManagementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'start_date'      => $this->start_date,
            'end_date'        => $this->end_date,
            'status'          => $this->status,
            'priority'        => $this->priority,
            'description'     => $this->description,
            'is_active'       => (bool) $this->is_active,
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
            'attachments' => $this->taskAttachments->map(function ($file) {
                return [
                    'id'  => $file->id,
                    'url' => url(\App\Models\Attachment::UPLOAD_PATH . $file->attachment),
                ];
            }),
        ];
    }
}
