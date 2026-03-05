<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamMeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                        => $this->id,
            'title'                     => $this->title,
            'description'               => $this->description,
            'venue'                     => $this->venue,
            'meeting_date'              => $this->meeting_date,
            'meeting_start_time'        => $this->meeting_start_time,
            'meeting_published_at'      => $this->meeting_published_at,
            'meeting_link'              => $this->meeting_link,
            'image'                     => $this->image
                                                ? asset('uploads/team-meetings/' . $this->image)
                                                : null,
            'company'                   => $this->company
                                            ? [
                                                'id'       => $this->company->id,
                                                'name'     => $this->company->name
                                            ] : null,
            'created_by'                => $this->admin
                                                ? [
                                                    'id'           => $this->admin->id,
                                                    'name'         => $this->admin->name
                                                 ] : null,
            'branch'                    => $this->branch
                                                ? [
                                                    'id'        => $this->branch->id,
                                                    'name'      => $this->branch->name
                                                ] : null,
            'participants' => $this->teamMeetingParticipator->map(function ($participant) {
                return [
                    'id'   => $participant->participator?->id,
                    'name' => $participant->participator?->name,
                ];
            }),
        ];
    }
}
