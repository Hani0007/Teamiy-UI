<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardAPIResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'branches'     => $this['branches'],
            'employees'       => $this['employees'],
            'pending_leaves'  => $this['pending_leaves'],
            'checkins_today'  => $this['checkins_today'],
            'employees_on_leave_today'  => $this['employees_on_leave_today']
        ];
    }
}
