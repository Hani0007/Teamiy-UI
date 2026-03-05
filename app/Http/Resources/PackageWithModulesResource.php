<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageWithModulesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'plan' => [
                'id' => $this->id,
                'name' => $this->name,
                'price_per_month' => $this->price_per_month,
                'price_per_year'  => $this->price_per_year
            ],
            'modules' => $this->packageModules->map(function ($pivot) {
                return [
                    'id' => $pivot->module->id,
                    'name' => $pivot->module->name,
                ];
            }),
        ];
    }
}
