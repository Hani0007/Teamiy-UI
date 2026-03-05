<?php

namespace App\Repositories;

use App\Enum\ResignationStatusEnum;
use App\Models\Company;
use App\Models\Resignation;
use App\Models\User;
use App\Traits\ImageService;

class ResignationRepository
{
    use ImageService;

    public function getAllResignationPaginated($filterParameters, $select = ['*'], $with = [])
    {
        $user = auth()->guard('admin')->user();

        if ($user && $user->hasRole('super-admin')) {
            $company = $user->company()->first();
        } else {
            $company = Company::where('admin_id', $user->parent_id)->first();
        }

        if (!$company) {
            return Resignation::whereNull('id')->paginate(getRecordPerPage());
        }

        $employees = User::where('company_id', $company->id)->pluck('id')->toArray();

        return Resignation::with($with)
            ->select($select)

            ->when(!empty($filterParameters['branch_id']), function ($query) use ($filterParameters) {
                $query->where('branch_id', $filterParameters['branch_id']);
            })
            ->when(!empty($filterParameters['department_id']), function ($query) use ($filterParameters) {
                $query->where('department_id', $filterParameters['department_id']);
            })
            ->when(!empty($filterParameters['employee_id']), function ($query) use ($filterParameters) {
                $query->where('employee_id', $filterParameters['employee_id']);
            })
            ->when(!empty($filterParameters['resignation_date']), function ($query) use ($filterParameters) {
                $query->whereDate(
                    'resignation_date',
                    \Carbon\Carbon::parse($filterParameters['resignation_date'])->format('Y-m-d')
                );
            })

            ->whereIn('employee_id', $employees)
            ->paginate(getRecordPerPage());
    }

    public function find($id,$select=['*'],$with=[])
    {
        return Resignation::select($select)
            ->with($with)
            ->where('id',$id)
            ->first();
    }

    public function store($validatedData)
    {
        $validatedData['created_by']= auth()->user()->id ?? null;
        if(isset($validatedData['document'])){
            $validatedData['document'] = $this->storeImage($validatedData['document'], Resignation::UPLOAD_PATH, 500, 250);
        }
        return Resignation::create($validatedData)->fresh();
    }

    public function update($resignationDetail,$validatedData)
    {
        $validatedData['updated_by']= auth()->user()->id ?? null;
        if (isset($validatedData['document'])) {
            if ($resignationDetail['document']) {
                $this->removeImage(Resignation::UPLOAD_PATH, $resignationDetail['document']);
            }
            $validatedData['document'] = $this->storeImage($validatedData['document'], Resignation::UPLOAD_PATH, 500, 250);
        }
        return $resignationDetail->update($validatedData);
    }


    public function delete($resignationDetail)
    {
        if ($resignationDetail['document']) {
            $this->removeImage(Resignation::UPLOAD_PATH, $resignationDetail['document']);
        }
        return $resignationDetail->delete();
    }

    public function findByEmployeeId($employeeId,$select=['*'])
    {
        return Resignation::select($select)
            ->where('employee_id',$employeeId)
            ->orderBy('created_at','desc')
            ->first();
    }

}
