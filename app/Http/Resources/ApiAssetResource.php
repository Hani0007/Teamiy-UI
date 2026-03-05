<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiAssetResource extends JsonResource
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
            'asset_type'            => $this->type
                                        ? [
                                            'id'       => $this->type->id,
                                            'name'     => $this->type->name
                                        ] : null,
            'asset_code'            => $this->asset_code,
            'asset_serial_no'       => $this->asset_serial_no,
            'is_working'            => $this->is_working,
            'purchase_date'         => $this->purchased_date,
            'warranty_available'    => $this->warranty_available == 1 ? 'yes' : 'no',
            'warranty_end_date'     => $this->warranty_end_date,
            'is_available'          => $this->is_available == 1 ? 'yes' : 'no',
            'is_repaired'           => $this->is_repaired == 1 ? 'yes' : 'no',
            'note'                  => $this->note,
            'branch'                => $this->branch
                                        ? [
                                            'id'    => $this->branch->id,
                                            'name'  => $this->branch->name
                                        ] : null,
            'image'                 => $this->image
                                        ? asset('uploads/asset/' . $this->image)
                                        : null,
            'assigned' =>           (
                                        $this->latestAssignment &&
                                        $this->latestAssignment->status === 'assigned'
                                    ) ? [
                                        'id'               => $this->latestAssignment->id,
                                        'user'             => $this->latestAssignment?->user?->name,
                                        'status'           => $this->latestAssignment->status,
                                        'assigned_date'    => $this->latestAssignment->assigned_date,
                                        'returned_date'    => $this->latestAssignment->returned_date,
                                        'return_condition' => $this->latestAssignment->return_condition,
                                        'note'             => $this->latestAssignment->notes,
                                        'department'       => $this->latestAssignment?->department?->dept_name,
                                    ] : null,
        ];
    }
}
