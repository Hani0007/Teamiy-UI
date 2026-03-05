<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'dept_name'       => $this->dept_name,
            'slug'            => $this->slug,
            'address'         => $this->address ?? '',
            'phone'           => $this->phone ?? '',
            'is_active'       => (int) $this->is_active,
            'dept_head_id'    => $this->dept_head_id,
            'company_id'      => $this->company_id,
            'branch_id'       => $this->branch_id,
            'created_by'      => $this->created_by,
            'updated_by'      => $this->updated_by,
            'created_at'      => optional($this->created_at)->toISOString(),
            'updated_at'      => optional($this->updated_at)->toISOString(),
            'employees_count' => $this->whenCounted('employees'),
        ];
    }
}
