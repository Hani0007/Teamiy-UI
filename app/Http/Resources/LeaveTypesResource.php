<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveTypesResource extends JsonResource
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
            'type'           => $this->name ?? null,
            'is_paid'        => $this->leave_allocated ? 'yes' : 'no',
            'allocated_days' => $this->leave_allocated,
            'status'         => (bool) $this->is_active,
        ];
    }
}
