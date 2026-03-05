<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouterApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'router_ssid'       => $this->router_ssid,
            'company'           => $this->company
                                        ? [
                                            'id'    => $this->company->id,
                                            'name'  => $this->company->name
                                        ] : null,
            'branch'            => $this->branch
                                        ? [
                                            'id'    => $this->branch->id,
                                            'name'  => $this->branch->name
                                        ] : null,
            'is_active'         => (int) $this->is_active

        ];
    }
}
