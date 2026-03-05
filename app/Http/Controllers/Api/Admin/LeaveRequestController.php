<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequestApisRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Http\Resources\TimeLeaveFetchReasource;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\LeaveRequestMaster;
use App\Models\LeaveType;
use App\Models\TimeLeave;
use App\Models\User;
use App\Repositories\LeaveRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LeaveRequestController extends Controller
{
    public function __construct(protected LeaveRepository $leaveRepo)
    {}
    public function index(Request $request)
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

            $leaveType  = $request->input('leave_type');
            $startDate = $request->input('start_date')
                ? Carbon::createFromFormat('Y-d-m', $request->input('start_date'))->startOfDay()
                : null;

            $endDate = $request->input('end_date')
                ? Carbon::createFromFormat('Y-d-m', $request->input('end_date'))->endOfDay()
                : null;

            if ($leaveType === 'short_leave') {
                $timeLeaves = TimeLeave::with([
                                'employee',
                                'leaveRequestApproval'
                            ])
                            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                                return $query->whereBetween('created_at', [$startDate, $endDate]);
                            })
                            ->where('company_id', $companyId)
                            ->orderBy('id', 'desc')
                            ->get();

                return response()->json([
                    'message' => $timeLeaves->isNotEmpty() ? __('success') : __('record_not_found'),
                    'data'    => TimeLeaveFetchReasource::collection($timeLeaves),
                ], 200);
            }
            else {
                $leaveRequests = LeaveRequestMaster::with([
                                    'leaveType',
                                    'branch:id,name',
                                    'department:id,dept_name',
                                    'employee:id,name',
                                    'leaveRequestApproval'
                                ])
                                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                                    $query->whereBetween('created_at', [$startDate, $endDate]);
                                })
                                ->where('company_id', $companyId)
                                ->orderBy('id', 'desc')
                                ->get();

                return response()->json([
                    'message' => $leaveRequests->isNotEmpty() ? __('success') : __('record_not_found'),
                    'data'    => LeaveRequestResource::collection($leaveRequests),
                ], 200);
            }

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function fetchEmployees(Request $request)
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

            $employees = User::select('id', 'name')
                            ->where('company_id', $companyId)
                            ->where('branch_id', $request->branch_id)
                            ->where('department_id', $request->department_id)
                            ->where('is_active', 1)
                            ->get();

            if ($employees->isNotEmpty()) {
                return response()->json([
                    'message' => __('success'),
                    'data'    =>    $employees
                ], 200);
            }

            return response()->json([
                'message' => __('record_not_found'),
                'data'    => []
            ], 404);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function leaveTypes()
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

            $leaveTypes = LeaveType::select('id', 'name')->where('company_id', $companyId)->get();

            if ($leaveTypes->isNotEmpty()) {
                return response()->json([
                    'message' => __('success'),
                    'data'    =>    $leaveTypes
                ], 200);
            }

            return response()->json([
                'message' => __('record_not_found'),
                'data'    => []
            ], 404);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function store(LeaveRequestApisRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        if (!$user) {
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

        try {
            $validatedData = $request->validated();
            $validatedData['company_id'] = $companyId;
            // **DOCUMENT UPLOAD**
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $filename = 'leave_' . time() . '.' . $file->getClientOriginalExtension();
                $path = public_path('uploads/documents/leave_requests');
            
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
            
                $file->move($path, $filename);
            
                $validatedData['document'] = $filename;
            }

            $attendance = Attendance::where('user_id', $request->requested_by)
                            ->whereBetween('attendance_date', [$request->leave_from, $request->leave_to])
                            ->first();

            if(isset($attendance))
            {
                return response()->json([
                    'message' => __('attendance_already_marked'),
                ], 400);
            }

            DB::beginTransaction();

            $leaveAllocated = LeaveType::where('id', $validatedData['leave_type_id'])->value('leave_allocated');

            $totalLeaveDays = LeaveRequestMaster::where('leave_type_id', $validatedData['leave_type_id'])
                                ->where('requested_by', $validatedData['requested_by'])
                                ->where('status', 'approved')
                                ->get()
                                ->sum(function ($leave) {
                                    return Carbon::parse($leave->leave_from)
                                        ->diffInDays(Carbon::parse($leave->leave_to)) + 1;
                                });

            $newLeaveDays = Carbon::parse($validatedData['leave_from'])
                ->diffInDays(Carbon::parse($validatedData['leave_to'])) + 1;

            if (($totalLeaveDays + $newLeaveDays) > $leaveAllocated) {
                throw ValidationException::withMessages([
                    'leave_type_id' => __('not_enough_remaining_leaves'),
                ]);
            }

            if (empty($request->leave_request_id)) {
                $validatedData['leave_requested_date'] = Carbon::now()->format('Y-m-d h:i:s');

                $result = $this->leaveRepo->store($validatedData);
                $message = __('leave_request_created');
            }
            else {
                $leaveRequest = LeaveRequestMaster::find($request->leave_request_id);

                if (!$leaveRequest) {
                    return response()->json(['message' => __('leave_request_not_found')], 404);
                }

                $this->leaveRepo->update($leaveRequest, $validatedData);
                $result = $leaveRequest->fresh();
                $message = __('leave_request_updated');
            }

            DB::commit();

            if ($result) {
                $result->load([
                    'leaveType:id,name,leave_allocated',
                    'branch:id,name',
                    'department:id,dept_name',
                    'employee:id,name',
                ]);

                return response()->json([
                    'message' => $message,
                    'data'    => new LeaveRequestResource($result)
                ], 200);
            }

            return response()->json([
                'message' => __('something_went_wrong'),
                'data'    => []
            ], 400);

        } catch (\Throwable $ex) {
            DB::rollBack();
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

            $rec = LeaveRequestMaster::where('id', $id)->first();

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
