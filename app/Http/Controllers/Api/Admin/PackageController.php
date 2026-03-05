<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PackageWithModulesResource;
use App\Models\Package;
use Exception;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            // if ($request->cycle === 'monthly') {
                $plans = Package::with(['packageModules.module'])
                            ->get();
            // } else {
            //     $plans = Package::select('id', 'name', 'price_per_year as price')
            //                 ->with(['packageModules.module'])
            //                 ->get();
            // }

            if($plans->isNotEmpty())
            {
                return response()->json([
                    'message' => __('success'),
                    'data'    => PackageWithModulesResource::collection($plans)
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
}
