<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLeaveTypesRequest;
use App\Http\Resources\LeaveTypesResource;
use App\Models\Company;
use App\Models\LeaveType;
use App\Repositories\LeaveTypeRepository;
use Exception;
use Illuminate\Http\Request;

class LeaveTypesController extends Controller
{
    public function __construct(protected LeaveTypeRepository $leaveTypeRepo)
    {
    }
    public function index()
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if(!$user)
            {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $roleName = $user->getRoleNames()->first();

            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
                $companyId = $company?->id;
            } else {
                $user->load('company');
                $companyId = $user->company->id;
            }

            $leaveTypes = LeaveType::where('company_id', $companyId)->get();

            if ($leaveTypes->isNotEmpty()) {
                return response()->json([
                    'message' =>  __('success'),
                    'data'    => LeaveTypesResource::collection($leaveTypes)
                ], 200);
            }

            return response()->json([
                'message' =>  __('no_leave_types_found'),
                'data'    => []
            ], 404);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function store(ApiLeaveTypesRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $user = auth()->guard('admin-api')->user();

            if(!$user)
            {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $roleName = $user->getRoleNames()->first();

            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
                $companyId = $company?->id;
            } else {
                $user->load('company');
                $companyId = $user->company->id;
            }

            $validatedData['company_id'] = $companyId;

            if (empty($request->leave_type_id)) {
                $result = $this->leaveTypeRepo->store($validatedData);
                $message =  __('leave_type_created');
            } else {
                $leaveType = LeaveType::where('id', $request->leave_type_id)->first();

                if (!$leaveType) {
                    return response()->json(['message' => __('leave_type_not_found')], 404);
                }

                $this->leaveTypeRepo->update($leaveType, $validatedData);
                $result = $leaveType->fresh();
                $message =  __('leave_type_updated');
            }



            if ($result) {
                return response()->json([
                    'message' => $message,
                    'data'    => new LeaveTypesResource($result)
                ], 200);
            }

            return response()->json([
                'message' => __('something_went_wrong'),
                'data'    => []
            ], 404);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function delete($id)
    {
        $user = auth()->guard('admin-api')->user();

        try{
            if(!$user)
            {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $rec = LeaveType::where('id', $id)->first();

            if($rec)
            {
                $rec->delete();

                return response()->json([
                    'message' => __('record_deleted'),
                ], 200);
            }

            return response()->json(['message' => __('record_not_found')], 404);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}
