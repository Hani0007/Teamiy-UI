<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyProfileResource extends JsonResource
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
            'email'             => $this->email,
            'address'           => $this->address,
            'phone'             => $this->phone,
            'is_active'         => $this->is_active,
            'website_url'       => $this->website_url,
            'logo'              => $this->logo,
            'weekend'           => $this->weekend,
            'admin_id'          => $this->admin_id,
            'industry_type'     => $this->industry_type,
            'no_of_employees'   => $this->no_of_employees,
            'contact_number'    => $this->contact_number,
            'country'           => $this->countries,
            'country_code'      => $this->country_code,
            'province'          => $this->province,
            'city'              => $this->city,
            'postal_code'       => $this->postal_code,
            'currency'          => $this->currency,
            'terms_conditions'  => $this->terms_conditions,
            'created_by'        => $this->created_by,
            'updated_by'        => $this->updated_by,
            'created_at'        => $this->created_at?->toDateTimeString(),
            'updated_at'        => $this->updated_at?->toDateTimeString(),
        ];
    }
}
