<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserManagementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'username'    => $this->username,
            'email'       => $this->email,
            'parent_id'   => $this->parent_id,
            'plan_id'     => $this->plan_id,
            'is_verified' => $this->is_verified,
            'is_active' => $this->is_active,
            'avatar'      => $this->avatar
                                ? asset('uploads/admin/avatar/' . $this->avatar)
                                : null,
            'role'        => $this->roles
                                ? [
                                    'id' => $this->roles->pluck('id')->first(),
                                    'name' => $this->roles->pluck('name')->first()
                                ] : null,
        ];
    }
}
