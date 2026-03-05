<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\AppHelper;
use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\HolidayApiRequest;
use App\Http\Resources\HolidayApiResource;
use App\Models\Company;
use App\Models\Holiday;
use App\Models\User;
use App\Services\Holiday\HolidayService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    private $holidayService;

    public function __construct(HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    public function index()
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => 'Unauthorized access denied'], 401);
            }

            $roleName = $user->getRoleNames()->first();

            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
                $companyId = $company?->id;
            } else {
                $user->load('company');
                $companyId = $user->company->id;
            }

            $holidays = Holiday::where('company_id', $companyId)
                        ->orderBy('id', 'desc')
                        ->get();

            if ($holidays->isNotEmpty()) {
                return response()->json([
                    // 'message' => 'success',
                    'message' => __('success'),
                    'data'    => HolidayApiResource::collection($holidays)
                ], 200);
            }

            return response()->json([
                // 'message' => 'No records found',
                'message' => __('no_records_found'),
                'data'    => []
            ], 404);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function store(HolidayApiRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $validatedData = $request->validated();

            $roleName = $user->getRoleNames()->first();

            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
                $companyId = $company?->id;
            } else {
                $user->load('company');
                $companyId = $user->company->id;
            }

            $validatedData['company_id'] = $companyId;

            DB::beginTransaction();

            if ($request->filled('holiday_id')) {

                $id = $request->holiday_id;
                $holiday = $this->holidayService->update($validatedData, $id);

                $message = __('updated_success');
            }
            else {
                $holiday = $this->holidayService->store($validatedData);
                $message = __('created_success');
            }

            $users = User::where('company_id', $companyId)->where('is_active', 1)->get();

            $names = $users->pluck('username')->toArray();
            SMPushHelper::sendPushNotification($holiday->event, '', $holiday->note ?? '', 'Holiday', $names, '');

            DB::commit();

            return response()->json([
                'message' => $message,
                'data'    => new HolidayApiResource($holiday)
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

            $rec = Holiday::where('id', $id)->first();

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
