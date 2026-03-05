<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeManagementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'email'            => $this->email,
            'work_email'       => $this->work_email,
            'username'         => $this->username,
            'employee_code'    => $this->employee_code,
            'nationality'      => $this->nationality,
            'avatar'           => $this->avatar
                                    ? asset('uploads/user/avatar/' . $this->avatar)
                                    : null,
            'dob'              => $this->dob,
            'address'          => $this->address,
            'gender'           => $this->gender,
            'phone'            => $this->phone,
            'status'           => $this->status,
            'is_active'        => $this->is_active,
            'marital_status'   => $this->marital_status,
            'employment_type'  => $this->employment_type,
            'user_type'        => $this->user_type,
            'joining_date'     => $this->joining_date,
            'contract_start'   => $this->contract_start_date,
            'contract_end'     => $this->contract_end_date,
            'pay_grade'        => $this->pay_grade,
            'additional_notes' => $this->additional_notes,
            'remarks'          => $this->remarks,
            'workspace_type'   => $this->workspace_type,
            'nfc_card'         => $this->nfc_card,
            'role' => $this->roles->isNotEmpty()
                ? [
                    'id'   => $this->roles->first()->id,
                    'name' => $this->roles->first()->name,
                ]
                : null,
            'branch' => $this->branch
                ? [
                    'id'   => $this->branch->id,
                    'name' => $this->branch->name,
                ]
                : null,
            'department' => $this->department
                ? [
                    'id'   => $this->department->id,
                    'name' => $this->department->dept_name,
                ]
                : null,
            'designation' => $this->post
                ? [
                    'id'   => $this->post->id,
                    'name' => $this->post->post_name,
                ]
                : null,
            'employee_documents' => $this->employeeDocuments
                                        ? [
                                            'contract'  => $this->employeeDocuments->employee_contract
                                                            ? asset('uploads/user/emp-documents/' . $this->employeeDocuments->employee_contract)
                                                            : null,

                                            'documents' => is_array($this->employeeDocuments->employee_document)
                                                ? collect($this->employeeDocuments->employee_document)
                                                    ->map(fn($file) => asset('uploads/user/emp-documents/' . $file))
                                                    ->toArray()
                                                : [],
                                        ]
                                        : null,
            'bank_name'         => $this->accountDetail
                                    ? $this->accountDetail->bank_name : null,
            'bank_account_no'   => $this->accountDetail
                                    ? $this->accountDetail->bank_account_no : null,
            'bank_account_type' => $this->accountDetail
                                    ? $this->accountDetail->bank_account_type : null,
            'account_holder'    => $this->accountDetail
                                    ? $this->accountDetail->account_holder : null,
        ];
    }
}
