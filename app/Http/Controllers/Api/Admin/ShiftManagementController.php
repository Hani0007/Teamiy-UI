<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enum\ShiftTypeEnum;
use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShiftManagementRequest;
use App\Models\Company;
use App\Models\OfficeTime;
use App\Repositories\OfficeTimeRepository;
use App\Requests\OfficeTime\OfficeTimeRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\CustomAuthorizesRequests;
use Illuminate\Support\Facades\Log;

class ShiftManagementController extends Controller
{
    use CustomAuthorizesRequests;

    public function __construct(protected OfficeTimeRepository $officeTimeRepo)
    {}

    public function index(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try{
            if(!$user)
            {
                return response()->json(['message' => __('unauthorized_access')], 400);
            }

            $rec = OfficeTime::with('branch:id,name')->where('branch_id', $request->branch_id)->get();
            unset($rec->branch_id);

            return response()->json([
                'message' =>  __('success'),
                'data'    => $rec
            ], 200);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function store(ShiftManagementRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        $validatedData = $request->validated();
        $roleName = $user->getRoleNames()->first();

        if ($roleName !== 'super-admin') {
            $company = Company::where('admin_id', $user->parent_id)->first();
            $companyId = $company?->id;
        } else {
            $user->load('company');
            $companyId = $user->company->id;
        }

        $validatedData = array_merge([
            'is_early_check_in'  => 0,
            'is_early_check_out' => 0,
            'is_late_check_in'   => 0,
            'is_late_check_out'  => 0,
        ], $validatedData);

        $validatedData['checkin_before']  = $validatedData['is_early_check_in']  ? $validatedData['checkin_before']  : null;
        $validatedData['checkout_before'] = $validatedData['is_early_check_out'] ? $validatedData['checkout_before'] : null;
        $validatedData['checkin_after']   = $validatedData['is_late_check_in']   ? $validatedData['checkin_after']   : null;
        $validatedData['checkout_after']  = $validatedData['is_late_check_out']  ? $validatedData['checkout_after']  : null;

        $validatedData['company_id'] = $companyId;

        $message = $validatedData['shift_type'] === ShiftTypeEnum::night->value
            ? __('message.office_time_added_night_shift')
            : __('message.office_time_added');

        DB::beginTransaction();

        try {
            if (empty($request->office_time_id)) {
                $shiftData = $this->officeTimeRepo->store($validatedData);
            } else {
                $officeTime = OfficeTime::findOrFail($request->office_time_id);
                $this->officeTimeRepo->update($officeTime, $validatedData);
                $shiftData = $officeTime->fresh();
                $message = __('office_time_updated');
            }

            $shiftData->load('branch:id,name');
            $shiftData->makeHidden(['branch_id']);

            DB::commit();

            return response()->json([
                'message' => $message,
                'data'    => $shiftData
            ], 200);

        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function delete($id)
    {
        $user = auth()->guard('admin-api')->user();

        DB::beginTransaction();

        try {
            if(!$user)
            {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $officeTime = OfficeTime::where('id', $id)
                ->first();

            if (!$officeTime) {
                return response()->json([
                    'message' => __('office_time_not_found')
                ], 404);
            }

            $officeTime->delete();

            DB::commit();

            return response()->json([
                'message' => __('office_time_deleted')
            ], 200);

        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return response()->json([
                'message' => $ex->getMessage()
            ], 400);
        }
    }

}
