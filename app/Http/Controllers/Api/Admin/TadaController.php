<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TadaApiRequest;
use App\Http\Resources\TadaApiResource;
use App\Http\Resources\TadaStoreApiResource;
use App\Models\Tada;
use App\Repositories\CompanyRepository;
use App\Services\Tada\TadaService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TadaController extends Controller
{
    public function __construct(
        protected TadaService $tadaService,
        protected CompanyRepository $companyRepository
    ) {}

    public function index(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $filterParameters = [
                'branch_id' => $request->branch_id ?? null,
                'department_id' => $request->department_id ?? null,
                'employee_id' => $request->employee_id ?? null,
                'status' => $request->status ?? null
            ];
            $select = ['*'];
            $with = ['employeeDetail:id,name', 'attachments:id,tada_id,attachment'];
            $tadaLists = $this->tadaService->getAllTadaDetailPaginated($filterParameters, $select, $with);

            if ($tadaLists->isNotEmpty()) {
                return response()->json([
                    'message' => __('success'),
                    'data'    => TadaApiResource::collection($tadaLists)
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

    public function store(TadaApiRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        $validatedData = $request->validated();

        try {
            if (!$user) {
                return response()->json(['message' => 'Unauthorized access denied'], 401);
            }

            DB::beginTransaction();

            if ($request->filled('tada_id')) {
                $with = ['attachments'];
                $tada = $this->tadaService->findTadaDetailById($request->tada_id, $with);

                $this->tadaService->update($tada, $validatedData);
                $tada->refresh();
            } else {
                $tada = $this->tadaService->store($validatedData);
            }

            $tada->load('branch:id,name', 'department:id,dept_name', 'attachments', 'employeeDetail:id,name');

            DB::commit();

            return response()->json([
                'message' => __('success'),
                'data'    => new TadaStoreApiResource($tada)
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

            $rec = Tada::where('id', $id)->first();

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

    public function updateStatus(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try{
            if(!$user)
            {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $rec = Tada::where('id', $request->id)->first();

            if($rec)
            {
                $rec->update(['status' => $request->status]);

                return response()->json([
                    'message' => __('status_updated'),
                ], 200);
            }

            return response()->json(['message' => __('record_not_found')], 404);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function isPaid(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try{
            if(!$user)
            {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $rec = Tada::where('id', $request->id)->first();

            if($rec)
            {
                $rec->update(['is_settled' => $request->is_paid]);

                return response()->json([
                    'message' => __('success'),
                ], 200);
            }

            return response()->json(['message' => __('record_not_found')], 404);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}
