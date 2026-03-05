<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\LeaveRequestMaster;
use App\Models\LeaveType;
use App\Models\TimeLeave;
use App\Repositories\LeaveTypeRepository;
use App\Repositories\TimeLeaveRepository;
use App\Resources\Leave\LeaveTypeCollection;
use Carbon\Carbon;
use Exception;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaveTypeApiController extends Controller
{

    public function __construct(protected LeaveTypeRepository $leaveTypeRepo, protected TimeLeaveRepository $timeLeaveRepository)
    {}

    public function getAllLeaveTypeWithEmployeeLeaveRecord(Request $request): JsonResponse
    {
        try {
            $user = auth()->guard('api')->user();
            $companyId = $user->company_id;
            $branchId = $user->branch_id;

            // $leavesTypes = LeaveType::select('id', 'name', 'leave_allocated', 'is_active', 'early_exit', 'gender')
            // ->where('company_id', $companyId)->where('branch_id', $branchId)->get();
            $leavesTypes = LeaveType::select('id', 'name', 'leave_allocated', 'is_active', 'early_exit', 'gender')
                    ->where('company_id', $companyId)
                    ->where('branch_id', $branchId)
                    ->withSum(['leaveRequest as leave_taken' => function ($query) use ($user) {
                        $query->where('requested_by', $user->id)
                        ->where('status', 'accepted');
                    }], 'no_of_days')
                    ->get();

            // $filterParameters = AppHelper::leaveYearDetailToFilterData();

            // if($request->type === 'full_day_leave')
            // {
            //     $leaveType = $this->leaveTypeRepo->getAllLeaveTypesWithLeaveTakenbyEmployee($filterParameters);
            //     $leaves = new LeaveTypeCollection($leaveType);
            // }
            // else {
            //     $timeLeave = $this->timeLeaveRepository->getTimeLeaveWithLeaveTakenbyEmployee($filterParameters);
            //     $leaves = collect([$timeLeave]);
            // }

            //$mergedCollection = $getAllLeaveType->merge($timeLeaveCollection);

            return AppHelper::sendSuccessResponse(__('index.data_found'), $leavesTypes);
        } catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), 400);
        }
    }
}
