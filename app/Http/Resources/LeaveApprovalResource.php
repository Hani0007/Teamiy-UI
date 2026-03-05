<?php

namespace App\Http\Resources;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveApprovalResource extends JsonResource
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
            //'subject' => $this->subject,
            'approver' => $this->approver,
            'branch' => $this->branch
                                ? [
                                    'id' => $this->branch->id,
                                    'name' => $this->branch->name
                                ] : null,

            'departments' => Department::whereIn('id', $this->department_id ?? [])->get()
                                    ->map(fn ($dept) => [
                                        'id' => $dept->id,
                                        'name' => $dept->dept_name,
                                    ]),
        ];
    }
}
