<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppSettingsApiResource;
use App\Models\AppSetting;
use Exception;
use Illuminate\Http\Request;

class AppSettingsApiController extends Controller
{
    public function index()
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $appSettings = AppSetting::all();

            if ($appSettings->isNotEmpty()) {
                return response()->json([
                    'message' => __('success'),
                    'data'    => AppSettingsApiResource::collection($appSettings)
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

    public function updateStatus(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $rec = AppSetting::where('id', $request->id)->first();

            if($rec)
            {
                $rec->update(['status' => $request->status]);

                return response()->json([
                    'message' => __('status_updated'),
                ], 200);
            }

            return response()->json(['message' => __('record_not_found')], 404);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}
