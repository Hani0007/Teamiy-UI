<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => $this->roles->first()?->name,
            'created_at' => $this->created_at?->toDateTimeString(),

            // Add company details
            'company' => $this->company ? [
                'id'                => $this->company->id,
                'name'              => $this->company->name,
                'industry_type'     => $this->company->industry_type,
                'no_of_employees'   => $this->company->no_of_employees,
                'contact_number'    => $this->company->contact_number,
                'country'           => $this->company?->countries
                                        ? [
                                                'id' => $this?->company?->countries?->id,
                                                'name' => $this?->company?->countries?->name,
                                                'code'  => $this?->company?->countries?->code
                                            ] : null,
                'province'          => $this->company->province,
                'city'              => $this->company->city,
                'postal_code'       => $this->company->postal_code,
                'address'           => $this->company->address,
                'website_url'       => $this->company->website_url,
                'logo'              => $this->company->logo ? asset('uploads/company/' . $this->company->logo) : null,
                'currency'          => $this->company?->countries
                                            ? [
                                                'id' => $this?->company?->countries?->id,
                                                'name' => $this?->company?->countries?->currency_name,
                                                'code'  => $this?->company?->countries?->code,
                                                'symbol'    => $this?->company?->countries?->currency_symbol
                                            ] : null,
                'terms_conditions'  => (bool) $this->company->terms_conditions,
                                       ] : null,

            // Subscription
            'subscription' => [
                'plan_id'                   => $this->plan_id,
                'plan_name'                 => $this->plan?->name,
                'trial_expiry'              => $this->trial_expiry,
                'next_payment_date'         => $this->subscription ? $this->subscription->next_payment_date : null,
                'cycle'                     => $this->subscription ? $this->subscription->cycle : null,
                'status'                    => $this->subscription ? $this->subscription->status : null,
                'stripe_subscription_id'    => $this->subscription ? $this->subscription->stripe_subscription_id : null,
            ],
        ];
    }
}
