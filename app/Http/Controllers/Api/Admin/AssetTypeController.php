<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiAssetTypeRequest;
use App\Http\Resources\AssetTypeResource;
use App\Models\AssetType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetTypeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $assetTypes = AssetType::with('admin', 'branch')
                                ->where('branch_id', $request->branch_id)
                                ->get();

            return response()->json([
                // 'message' => 'success',
                'message' => __('success'),
                'data'    => AssetTypeResource::collection($assetTypes)
            ]);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function store(ApiAssetTypeRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $validatedData = $request->validated();
            $validatedData['created_by'] = $user->id;

             DB::beginTransaction();

            if ($request->filled('asset_type_id')) {
                $assetType = AssetType::find($request->asset_type_id);

                if (!$assetType) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                $assetType->update($validatedData);
                $message = __('updated_success');
            } else {
                $assetType = AssetType::create($validatedData);
                $message = __('created_success');
            }

            DB::commit();
            $assetType->load(['admin', 'branch']);

            return response()->json([
                'message' => $message,
                'data'    => new AssetTypeResource($assetType),
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

            $rec = AssetType::where('id', $id)->first();

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
