<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RouterApiRequest;
use App\Http\Resources\RouterApiResource;
use App\Models\Router;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RouterApiController extends Controller
{
    public function index()
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $companyId = AppHelper::getAdminCompanyId();

            $routers = Router::with('branch', 'company')->where('company_id', $companyId)
                        ->orderBy('id', 'desc')
                        ->get();

            if ($routers->isNotEmpty()) {
                return response()->json([
                    'message' => __('success'),
                    'data'    => RouterApiResource::collection($routers)
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

    public function store(RouterApiRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $validatedData = $request->validated();

            DB::beginTransaction();

            if ($request->filled('router_id')) {
                $router = Router::findOrFail($request->router_id);
                $router->update($validatedData);
                $message = __('updated_success');
            } else {
                $validatedData['company_id'] = AppHelper::getAdminCompanyId();
                $router = Router::create($validatedData);
                $message = __('created_success');
            }

            DB::commit();

            return response()->json([
                'message' => $message,
                'data'    => new RouterApiResource($router)
            ], 200);

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

            $rec = Router::where('id', $id)->first();

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
