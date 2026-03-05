<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiAssetRequest;
use App\Http\Resources\ApiAssetResource;
use App\Models\Asset;
use App\Models\AssetAssignment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $assets = Asset::with('branch', 'type', 'latestAssignment')
                                ->where('branch_id', $request->branch_id)
                                ->get();

            return response()->json([
                // 'message' => 'success',
                'message' => __('success'),
                'data'    => ApiAssetResource::collection($assets)
            ]);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function store(ApiAssetRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $validatedData = $request->validated();
            $validatedData['created_by'] = $user->id;

             DB::beginTransaction();

            if ($request->filled('asset_id')) {
                $assetType = Asset::find($request->asset_id);

                if (!$assetType) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                $assetType->update($validatedData);
                $message = __('updated_success');
            } else {
                $assetType = Asset::create($validatedData);
                $message = __('created_success');
            }

            DB::commit();
            $assetType->load(['branch', 'type', 'latestAssignment']);

            return response()->json([
                'message' => $message,
                'data'    => new ApiAssetResource($assetType),
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

            $rec = Asset::where('id', $id)->first();

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

    public function assignReturnAsset(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $validatedData = $request->validate([
                'asset_id'        => 'required|integer|exists:assets,id',
                'user_id'         => 'required|integer|exists:users,id',
                'status'          => 'required|in:assigned,returned',
                'assigned_date'   => 'required|date',
                'returned_date'   => 'nullable|date|after_or_equal:assigned_date',
                'return_condition'=> 'nullable|string|max:255',
                'notes'           => 'nullable|string|max:500',
                'department_id'   => 'required|integer|exists:departments,id',
            ],[
                'asset_id.required'         => __('asset_id_required'),
                'asset_id.integer'          => __('asset_id_integer'),
                'asset_id.exists'           => __('asset_id_exists'),
            
                'user_id.required'          => __('user_id_required'),
                'user_id.integer'           => __('user_id_integer'),
                'user_id.exists'            => __('user_id_exists'),
            
                'status.required'           => __('asset_status_required'),
                'status.in'                 => __('asset_status_invalid'),
            
                'assigned_date.required'    => __('assigned_date_required'),
                'assigned_date.date'        => __('assigned_date_invalid'),
            
                'returned_date.date'        => __('returned_date_invalid'),
                'returned_date.after_or_equal' => __('returned_date_after_assigned'),
            
                'return_condition.string'   => __('return_condition_string'),
                'return_condition.max'      => __('return_condition_max'),
            
                'notes.string'              => __('notes_string'),
                'notes.max'                 => __('notes_max'),
            
                'department_id.required'    => __('department_id_required'),
                'department_id.integer'     => __('department_id_integer'),
                'department_id.exists'      => __('department_id_exists'),
            ]);

            DB::beginTransaction();

            if ($request->filled('asset_assigned_id')) {
                $assetAssigned = AssetAssignment::find($request->asset_assigned_id);

                if (!$assetAssigned) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                $assetAssigned->update($validatedData);
                $message = __('updated_success');
            } else {
                $assetAssigned = AssetAssignment::create($validatedData);
                $message = __('created_success');
            }

            if ($assetAssigned) {
                DB::commit();
                return response()->json(['message' => $message]);
            }

            DB::rollBack();
            return response()->json(['message' => __('something_went_wrong')]);

        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}
