<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiTerminationRequest;
use App\Http\Resources\TerminationResource;
use App\Models\Termination;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TerminationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $terminations = Termination::with('employee', 'admin', 'branch', 'department')
                                ->where('branch_id', $request->branch_id)
                                ->orWhere('department_id', $request->department_id)
                                ->get();

            return response()->json([
                // 'message' => 'success',
                'message' => __('success'),
                'data'    => TerminationResource::collection($terminations),
            ]);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function store(ApiTerminationRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $validatedData = $request->validated();
            $validatedData['created_by'] = $user->id;

             DB::beginTransaction();

            if ($request->filled('termination_id')) {
                $termination = Termination::find($request->termination_id);

                if (!isset($termination)) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                $termination->update($validatedData);

                $message = __('updated_success');
            } else {
                $termination = Termination::create($validatedData);

                $message = __('created_success');
            }

            $user = User::where('id', $validatedData['employee_id'])->where('is_active', 1)->first();

            if (!isset($user)) {
                return response()->json(['message' => __('user_disabled')], 400);
            }

            if($validatedData['status'] === 'approved')
            {
                $name = [$user->username];

                $description = 'You have been marked as terminated effective '. $validatedData['termination_date'];
                SMPushHelper::sendPushNotification('Employee Terminated', '',  $description, 'Termination', $name, '');
                User::where('id', $validatedData['employee_id'])->update(['is_active' => 0]);
            }
            else{
                $user->update(['is_active' => 1]);
            }

            DB::commit();
            $termination->load(['employee', 'admin', 'branch', 'department']);

            return response()->json([
                'message' => $message,
                'data'    => new TerminationResource($termination),
            ]);

        } catch (Exception $ex) {
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

            $rec = Termination::where('id', $id)->first();

            if($rec)
            {
                $rec->delete();

                $user = User::where('id', $rec->employee_id)->first();

                if (!isset($user)) {
                    return response()->json(['message' => __('user_disabled')], 400);
                }

                $user->update(['is_active' => 1]);

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
