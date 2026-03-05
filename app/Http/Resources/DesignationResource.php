<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'post_name'  => $this->post_name,
            'is_active'  => (int) $this->is_active,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),

            // Relations
            'branch' => $this->whenLoaded('branch', function () {
                return [
                    'id'      => $this->branch->id,
                    'name'    => $this->branch->name,
                    'phone'   => $this->branch->phone,
                    'address' => $this->branch->address,
                ];
            }),

            'department' => $this->whenLoaded('department', function () {
                return [
                    'id'      => $this->department->id,
                    'name'    => $this->department->dept_name,
                    'slug'    => $this->department->slug,
                    'phone'   => $this->department->phone,
                    'address' => $this->department->address,
                ];
            }),
        ];
    }
}
