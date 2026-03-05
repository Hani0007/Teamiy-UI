<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Company;
use App\Models\Department;
use Exception;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function fetchDepartments(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        $roleName = $user->getRoleNames()->first();

        if ($roleName !== 'super-admin') {
            $company = Company::where('admin_id', $user->parent_id)->first();
            $companyId = $company?->id;
        } else {
            $user->load('company');
            $companyId = $user->company->id;
        }

        try {
            $departments = Department::where('branch_id', $request->branch_id)
                ->where('company_id', $companyId)
                ->whereHas('branch', function ($q) {
                    $q->where('is_active', 1);
                })
                ->withCount('employees')
                ->get();

            if(!$departments)
            {
                return response()->json(['message' => __('record_not_found')], 404);
            }

            return response()->json([
            'message' =>  __('success'),
            'data'    => DepartmentResource::collection($departments)
        ], 200);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function storeDepartment(DepartmentRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        $roleName = $user->getRoleNames()->first();

        try {
            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
                $companyId = $company?->id;
            } else {
                $user->load('company');
                $companyId = $user->company->id;
            }

            $validated = $request->validated();

            if (!empty($request->department_id)) {
                $department = Department::where('id', $request->department_id)
                    ->where('company_id', $companyId)
                    ->where('branch_id', $validated['branch_id'])
                    ->first();

                if (!$department) {
                    return response()->json(['message' => __('department_not_found')], 404);
                }

                $department->update(array_merge($validated, [
                    'company_id' => $companyId,
                ]));
            } else {
                $exists = Department::where('dept_name', $validated['dept_name'])
                    ->where('branch_id', $validated['branch_id'])
                    ->exists();

                if ($exists) {
                    return response()->json(['message' => __('department_exist')], 422);
                }

                $department = Department::create(array_merge($validated, [
                    'company_id' => $companyId,
                ]));
            }

            $department['is_active'] = (int) $department['is_active'];
            $department['branch_id'] = (int) $department['branch_id'];
            $department['dept_head_id'] = $department['dept_head_id'] == null ? '' : (int) $department['dept_head_id'];

            return response()->json([
                'message' =>  __('success'),
                'data'    => $department
            ], 200);

        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function deleteDepartment($id)
    {
        $user = auth()->guard('admin-api')->user();

        $roleName = $user->getRoleNames()->first();

        try{
            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
                $companyId = $company?->id;
            } else {
                $user->load('company');
                $companyId = $user->company->id;
            }

            $rec = Department::where('id', $id)->where('company_id', $companyId)->first();

            if($rec)
            {
                $rec->delete();

                return response()->json([
                    'message' =>  __('record_deleted'),
                ], 200);
            }

            return response()->json(['message' => __('record_not_found')], 404);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}
