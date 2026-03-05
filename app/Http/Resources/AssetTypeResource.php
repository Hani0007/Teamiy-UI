<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'is_active'             => $this->is_active,
            'created_by'            => $this->admin
                                            ? [
                                                'id'        => $this->admin->id,
                                                'name'      => $this->admin->name
                                            ] : null,
            'branch'                => $this->branch
                                            ? [
                                                'id'        => $this->branch->id,
                                                'name'      => $this->branch->name
                                            ] : null
        ];
    }
}
