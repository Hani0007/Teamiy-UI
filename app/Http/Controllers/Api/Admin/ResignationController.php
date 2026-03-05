<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiResignationRequest;
use App\Http\Resources\ResignationResource;
use App\Models\Resignation;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ImageService;

class ResignationController extends Controller
{
    use ImageService;

    public function index(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $resignations = Resignation::with('employee', 'admin', 'branch', 'department')
                                ->where('branch_id', $request->branch_id)
                                ->orWhere('department_id', $request->department_id)
                                ->get();

            return response()->json([
                // 'message' => 'success',
                'message' => __('success'),
                'data'    => ResignationResource::collection($resignations)
            ]);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function store(ApiResignationRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $validatedData = $request->validated();
            $validatedData['created_by'] = $user->id;

            if (isset($validatedData['document'])) {
                if ($validatedData['document']) {
                    $this->removeImage(Resignation::UPLOAD_PATH, $validatedData['document']);
                }
                $validatedData['document'] = $this->storeImage($validatedData['document'], Resignation::UPLOAD_PATH, 500, 250);
            }

             DB::beginTransaction();

            if ($request->filled('resignation_id')) {
                $resignation = Resignation::find($request->resignation_id);

                if (!$resignation) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                $resignation->update($validatedData);
                $message = __('updated_success');
            } else {
                $resignation = Resignation::create($validatedData);
                $message = __('created_success');
            } 

            if($validatedData['status'] === 'approved')
            {
                $user = User::where('id', $validatedData['employee_id'])->where('is_active', 1)->first();

                if (!$user) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                $name = [$user->username];

                SMPushHelper::sendPushNotification('Resignation Acceptance', '','Your resignation has been accepted', 'Resignation', $name, '');
            }

            if($validatedData['status'] === 'rejected')
            {
                $user = User::where('id', $validatedData['employee_id'])->where('is_active', 1)->first();

                if (!$user) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                $name = [$user->username];

                SMPushHelper::sendPushNotification('Resignation Acceptance', '','Your resignation has been rejected', 'Resignation', $name, '');
            }

            DB::commit();
            $resignation->load(['employee', 'admin', 'branch', 'department']);

            return response()->json([
                'message' => $message,
                'data'    => new ResignationResource($resignation),
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

            $rec = Resignation::where('id', $id)->first();

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
