<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HolidayApiResource extends JsonResource
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
            'event name'        => $this->event,
            'event_date'        => $this->event_date,
            'note'              => strip_tags($this->note),
            'is_active'         => $this->is_active,
            'is_public_holiday' => $this->is_public_holiday
        ];
    }
}
