<?php

namespace App\Http\Resources;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TadaStoreApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'branch'        => [
                'id'   => $this->branch->id ?? null,
                'name' => $this->branch->name ?? null,
            ],
            'department'    => [
                'id'        => $this->department->id ?? null,
                'dept_name' => $this->department->dept_name ?? null,
            ],
            'employee'      => [
                'id'   => $this->employeeDetail->id ?? null,
                'name' => $this->employeeDetail->name ?? null,
            ],
            'title'         => $this->title,
            'total_expense' => $this->total_expense,
            'currency'      => AppHelper::getCompanyPaymentCurrencySymbol(),
            'description'   => $this->description,
            'status'        => $this->status,
            'is_settled'    => $this->is_settled ? 'Yes' : 'No',
            'attachments'   => $this->attachments
                ? $this->attachments->map(function ($file) {
                    return [
                        'id'   => $file->id,
                        'url'  => Storage::url($file->file_path),
                        'name' => basename($file->file_path),
                    ];
                })
                : [],
            'created_by'    => optional(auth()->guard('admin-api')->user(), function ($user) {
                return [
                    'id'   => $user->id,
                    'name' => $user->name,
                ];
            }),
        ];
    }
}
