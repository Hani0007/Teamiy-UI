<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TimeLeaveRequest;
use App\Http\Resources\TimeLeaveFetchReasource;
use App\Http\Resources\TimeLeaveResource;
use App\Models\Company;
use App\Models\TimeLeave;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimeLeaveController extends Controller
{
    public function index()
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $timeLeaves = TimeLeave::with('employee', 'branch')
                ->orderBy('id', 'desc')
                ->get();

            if ($timeLeaves->isNotEmpty()) {
                return response()->json([
                    'message' => __('success'),
                    'data'    => TimeLeaveFetchReasource::collection($timeLeaves)
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

    public function store(TimeLeaveRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        $validatedData = $request->validated();

        try{
            $roleName = $user->getRoleNames()->first();
            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
                $companyId = $company?->id;
            } else {
                $user->load('company');
                $companyId = $user->company->id;
            }

            $data = [
                'issue_date' => $validatedData['issue_date'],
                'start_time' => $validatedData['leave_from'],
                'end_time'  => $validatedData['leave_to'],
                'reasons'   => $validatedData['reasons'],
                'requested_by'  => $validatedData['requested_by'],
                'referred_by'   => $user->id,
                'branch_id' => $validatedData['branch_id'],
                'department_id' => $validatedData['department_id']
            ];

            DB::beginTransaction();

            if ($request->filled('time_leave_id')) {
                $timeLeave = TimeLeave::findOrFail($request->time_leave_id);
                $timeLeave->update($data);
                $timeLeave->refresh();
            } else {
                $data['company_id'] = $companyId;
                $timeLeave = TimeLeave::create($data);
            }

            $timeLeave->load('employee', 'branch', 'department', 'referredBy');

            DB::commit();

            return response()->json([
                'message' => __('success'),
                'data'    => new TimeLeaveResource($timeLeave)
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

            $rec = TimeLeave::where('id', $id)->first();

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
