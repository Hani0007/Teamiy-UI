<?php

namespace App\Http\Resources;

use App\Helpers\AppHelper;
use App\Models\TadaAttachment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TadaApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'total_expense'  => $this->total_expense,
            'description'    => $this->description,
            'currency'       => AppHelper::getCompanyPaymentCurrencySymbol(),
            'status'         => $this->status,
            'is_settled'     => $this->is_settled ? 'Yes' : 'No',
            'employee'       => optional($this->employeeDetail)->only(['id', 'name']),
            'submitted_date' => AppHelper::formatDateForView($this->created_at),
            'attachments'    => $this->attachments->map(function($attachment) {
                return [
                    'id'  => $attachment->id,
                    'url' => $attachment->attachment
                            ? asset(TadaAttachment::ATTACHMENT_UPLOAD_PATH . $attachment->attachment)
                            : null,
                ];
            }),
        ];
    }
}
