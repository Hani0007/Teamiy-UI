<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceMachineListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this?->id,
            'device_sn'             => $this->device_sn,
            'is_active'             => $this->is_active,
            'branch'                => $this->branch
                                        ? [
                                            'id'        => $this?->branch?->id,
                                            'name'      => $this?->branch?->name
                                        ] : null,
            'company'               => $this->company
                                        ? [
                                            'id'        => $this->company->id,
                                            'name'      => $this->company->name
                                        ] : null
        ];
    }
}
