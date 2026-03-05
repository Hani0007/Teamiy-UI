<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveApprovalRequest;
use App\Http\Resources\LeaveApprovalResource;
use App\Models\Admin;
use App\Models\LeaveApproval;
use App\Models\LeaveRequestApproval;
use App\Models\LeaveRequestMaster;
use App\Models\TimeLeave;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveApprovalController extends Controller
{
    public function index()
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $timeLeaves = LeaveApproval::with('branch', 'leaveType')
                ->orderBy('id', 'desc')
                ->get();

            if ($timeLeaves->isNotEmpty()) {
                return response()->json([
                    'message' => __('success'),
                    'data'    => LeaveApprovalResource::collection($timeLeaves)
                ], 200);
            }

            return response()->json([
                'message' => __('record_not_found'),
                'data'    => []
            ], 404);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function store(LeaveApprovalRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        if(!$user)
        {
            return response()->json(['message' => __('unauthorized_access')], 401);
        }

        $validatedData = $request->validated();
        $validatedData['department_id'] = (array) $validatedData['department_id'];

        try {
            DB::beginTransaction();

            if ($request->filled('leave_approval_id')) {
                $timeLeave = LeaveApproval::where('id', $request->leave_approval_id)->first();
                $timeLeave->update($validatedData);
                $timeLeave->refresh();
            } else {
                $timeLeave = LeaveApproval::create($validatedData);
            }

            $timeLeave->load('branch', 'leaveType');

            DB::commit();

            return response()->json([
                'message' => __('success'),
                'data'    => new LeaveApprovalResource($timeLeave)
            ], 200);

        } catch(Exception $ex) {
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

            $rec = LeaveApproval::where('id', $id)->first();

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

    public function statusUpdate(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $statuses = [
                0 => 'pending',
                1 => 'accepted',
                2 => 'rejected'
            ];

            $approver = Admin::where('id', $request->approved_by)->first();
            if (!$approver || !$approver->hasAnyRole(['super-admin', 'admin-user'])) {
                return response()->json(['message' => __('not_allow_to_approve')], 403);
            }

            if($request->type == 'short_leave')
            {
                $leave = TimeLeave::where('id', $request->leave_request_id)->first();
            }
            else {
                $leave = LeaveRequestMaster::where('id', $request->leave_request_id)->first();
            }

            if ($request->filled('leave_request_approval_id')) {
                $leaveRequestApproval = LeaveRequestApproval::find($request->leave_request_approval_id);

                if (!$leaveRequestApproval) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                $leaveRequestApproval->update([
                    'leave_request_id'  => $request->leave_request_id,
                    'status'      => $request->status,
                    'approved_by' => $request->approved_by,
                    'reason'      => $request->reason,
                    'type'        => $request->type
                ]);

                $leave->update([
                    'status' => $statuses[$request->status]
                ]);
            } else {
                $leaveRequestApproval = LeaveRequestApproval::create([
                    'leave_request_id'  => $request->leave_request_id,
                    'status'      => $request->status,
                    'approved_by' => $request->approved_by,
                    'reason'      => $request->reason,
                    'type'        => $request->type
                ]);

                $leave->update([
                    'status' => $statuses[$request->status]
                ]);
            }

            if($statuses[$request->status] === 'accepted')
            {
                $user = User::where('id', $request->leave_request_id)->where('is_active', 1)->first();

                if (!$user) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                $name = [$user->username];

                SMPushHelper::sendPushNotification('Leave Status', '','Your leave request has been approved', 'Leave', $name, '');
            }

            if($statuses[$request->status] === 'rejected')
            {
                $user = User::where('id', $request->leave_request_id)->where('is_active', 1)->first();

                if (!$user) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                $name = [$user->username];

                SMPushHelper::sendPushNotification('Leave Status', '','Your leave request has been rejected', 'Leave', $name, '');
            }

            return response()->json([
                'message' => __('success'),
                'data'    => $leaveRequestApproval->fresh()
            ], 200);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
}

}
