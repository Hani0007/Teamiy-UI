<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name'              => $this->name,
            'owner_name'        => $this->owner_name,
            'address'           => $this->address,
            'phone'             => $this->phone,
            'is_active'         => $this->is_active,
            'website_url'       => $this->website_url,
            'logo'              => $this->logo ? asset('uploads/company/' . $this->logo) : null,
            'weekend'           => $this->weekend,
            'admin_id'          => $this->admin_id,
            'industry_type'     => $this->industry_type,
            'no_of_employees'   => $this->no_of_employees,
            'contact_number'    => $this->contact_number,
            'country'           => $this->whenLoaded('countries', function () {
                return [
                    'id'   => $this->countries->id,
                    'name' => $this->countries->name,
                ];
            }),
            'country_code'      => $this->country_code,
            'province'          => $this->province,
            'city'              => $this->city,
            'postal_code'       => $this->postal_code,
            'currency_preference' => $this->currency_preference,
            'terms_conditions'  => $this->terms_conditions,
            'created_by'        => $this->created_by,
            'updated_by'        => $this->updated_by,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
