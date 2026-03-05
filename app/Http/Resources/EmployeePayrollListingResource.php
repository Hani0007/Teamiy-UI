<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeePayrollListingResource extends JsonResource
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
            'name'          => $this->name,
            'email'         => $this->email,
            'avatar'        => $this->avatar
                                ? asset('uploads/user/avatar/' . $this->avatar)
                                : null,
            'employeeSalary'  => $this->employeeSalary
                                ? $this->employeeSalary : null,
        ];
    }
}
