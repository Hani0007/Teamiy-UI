<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoticeBoardResource extends JsonResource
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
            'title'                 => $this->title,
            'description'           => $this->description,
            'notice_pulish_date'    => $this->notice_publish_date,
            'company'               => $this->company
                                        ? [
                                            'id'       => $this->company->id,
                                            'name'     => $this->company->name
                                        ] : null,
            'is_active'             => $this->is_active == 1 ? true : false,
            'created_by'            => $this->admin
                                        ? [
                                            'id'       => $this->admin->id,
                                            'name'     => $this->admin->name
                                        ] : null,
            'branch'                => $this->branch
                                        ? [
                                            'id'       => $this->branch->id,
                                            'name'     => $this->branch->name
                                        ] : null,

            'notice_receivers' => $this->noticeReceiversDetail->map(function ($receiver) {
                return [
                    'notice' => $receiver->notice
                        ? [
                            'id'    => $receiver->notice->id,
                            'title' => $receiver->notice->title,
                        ] : null,
                    'employee' => $receiver->employee
                        ? [
                            'id'   => $receiver->employee->id,
                            'name' => $receiver->employee->name,
                        ] : null,
                ];
            }),

        ];
    }
}
