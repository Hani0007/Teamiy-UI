<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceMachineListingResource;
use App\Jobs\SyncEmployeeToMachine;
use App\Models\Attendance;
use App\Models\AttendanceMachine;
use App\Models\Company;
use App\Models\OfficeTime;
use App\Models\User;
use App\Services\AttendanceMachine\AttendanceMachineService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceMachineController extends Controller
{
    private $attendanceMachineService;

    public function __construct(AttendanceMachineService $attendanceMachineService)
    {
        $this->attendanceMachineService = $attendanceMachineService;
    }

    public function assignMachine(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $validatedData = $request->validate([
                'branch_id' => 'required|integer|exists:branches,id',
                'device_sn' => 'required|string|unique:attendance_machines,device_sn',
            ]);

            if ($user->hasRole('super-admin')) {
                $company = $user->company()->first();
            } else {
                $company = Company::where('admin_id', $user->parent_id)->first();
            }

            $validatedData['company_id'] = $company->id;

            DB::beginTransaction();

            if ($request->filled('assign_machine_id')) {
                $attendacneMachine = AttendanceMachine::find($request->assign_machine_id);

                if (!$attendacneMachine) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                $attendacneMachine->update($validatedData);
                $message = __('updated_success');
            } else {
                $attendacneMachine = AttendanceMachine::create($validatedData);
                $message = __('created_success');
            }

            DB::commit();

            return response()->json([
                'message' => $message,
                'data'    => $attendacneMachine,
            ], 200);

        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function fetch(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            if ($user->hasRole('super-admin')) {
                $company = $user->company()->first();
            } else {
                $company = Company::where('admin_id', $user->parent_id)->first();
            }

            $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
            $endDate   = Carbon::parse($request->end_date)->format('Y-m-d');

            $attendanceMachine = AttendanceMachine::where('branch_id', $request->branch_id)->first();
            $record = $this->attendanceMachineService->fetchAttendanceRecord($attendanceMachine->device_sn, $startDate, $endDate);

            if($record['success'])
            {
                $records = collect($record['data']['data']['records'])
                            ->values()
                            ->toArray();

                if(count($records) > 0) {
                    $employees = User::where('company_id', $company->id)
                                    ->where('branch_id', $request->branch_id)
                                    ->where('is_active', 1)
                                    ->get();

                    $officeTime = OfficeTime::where('company_id', $company->id)
                                            ->where('branch_id', $request->branch_id)
                                            ->first();

                    $startTime = $officeTime->opening_time;
                    $endTime   = $officeTime->closing_time;

                    $cleaned = collect($records)->map(function ($row) {
                        $row['employeeId'] = trim((string) $row['employeeId']);
                        return $row;
                    });

                    $grouped = $cleaned->groupBy('employeeId');

                    $result = $grouped->map(function ($items) use ($employees, $company, $startTime, $endTime) {

                        $sorted = collect($items)->sortBy(function($i){
                            return Carbon::parse($i['createTime']);
                        })->values();

                        $deviceSn     = $sorted->first()['deviceSn'];
                        $employeeId   = $sorted->first()['employeeId'];
                        $employeeName = $sorted->first()['employeeName'];

                        if ($sorted->count() === 1) {
                            $checkIn = Carbon::parse($sorted->first()['createTime']);

                            return [
                                "deviceSn"        => $deviceSn,
                                "employeeId"      => $employeeId,
                                "employeeName"    => $employeeName,
                                "user_id"         => optional($employees->where('employee_code', $employeeId)->first())->id,
                                "company_id"      => $company->id,
                                "attendance_date" => $checkIn->format('Y-m-d'),
                                "check_in_at"     => $checkIn->format('H:i:s'),
                                "check_out_at"    => "",
                                "check_in_type"   => 'machine',
                                "check_out_type"  => 'machine',
                                "worked_hour"     => 0,
                                "overtime"        => 0,
                                "undertime"       => 0,
                            ];
                        }

                        $checkIn  = Carbon::parse($sorted->first()['createTime']);
                        $checkOut = Carbon::parse($sorted->last()['createTime']);

                        $officeStart  = Carbon::parse($startTime);
                        $officeEnd    = Carbon::parse($endTime);
                        $officeHours  = $officeEnd->floatDiffInHours($officeStart);

                        $workedHours = $checkOut->floatDiffInHours($checkIn);

                        if ($workedHours > $officeHours) {
                            $overtime  = $workedHours - $officeHours;
                            $undertime = 0;
                        } elseif ($workedHours < $officeHours) {
                            $undertime = $officeHours - $workedHours;
                            $overtime  = 0;
                        } else {
                            $overtime = 0;
                            $undertime = 0;
                        }

                        return [
                            "deviceSn"        => $deviceSn,
                            "employeeId"      => $employeeId,
                            "employeeName"    => $employeeName,
                            "user_id"         => optional($employees->where('employee_code', $employeeId)->first())->id,
                            "company_id"      => $company->id,
                            "attendance_date" => $checkIn->format('Y-m-d'),
                            "check_in_at"     => $checkIn->format('H:i:s'),
                            "check_out_at"    => $checkOut->format('H:i:s'),
                            "check_in_type"   => 'machine',
                            "check_out_type"  => 'machine',
                            "worked_hour"     => round($workedHours),
                            "overtime"        => round($overtime),
                            "undertime"       => round($undertime),
                        ];
                    });

                    foreach($result as $res)
                    {
                        if(!empty($res['user_id']))
                        {
                            $att = Attendance::create($res);
                        }
                    }

                    if(isset($att)) {
                        return response()->json([
                            'message' => __('success')
                        ], 200);
                    }
                }
            }

            return response()->json(['message' => __('record_not_found')], 404);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function syncEmployees(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            if ($user->hasRole('super-admin')) {
                $company = $user->company()->first();
            } else {
                $company = Company::where('admin_id', $user->parent_id)->first();
            }

            $employee = User::where('company_id', $company->id)
                ->where('id', $request->employee_id)
                ->where('is_active', 1)
                ->first();

            // if ($employees->isEmpty()) {
            //     return response()->json(['message' => 'No employees found'], 404);
            // }

            $attendanceMachine = AttendanceMachine::where('branch_id', $request->branch_id)->first();
            if (!$attendanceMachine) {
                return response()->json(['message' => 'Attendance machine not found'], 404);
            }

            $result = $this->attendanceMachineService->addEmployee(
                $attendanceMachine->device_sn,
                $employee
            );


            if ($result['synced']) {
                $employee->update(['record_sync' => 'true']);
            }

            // foreach ($employees as $employee) {
                //SyncEmployeeToMachine::dispatch($employee, $attendanceMachine->device_sn);
            // }

            return response()->json([
                'message' => __('success')
            ], 200);


        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function listing()
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            if ($user->hasRole('super-admin')) {
                $company = $user->company()->first();
            } else {
                $company = Company::where('admin_id', $user->parent_id)->first();
            }

            $machines = AttendanceMachine::where('company_id', $company->id)->orderBy('id', 'desc')->get();

            $machines->load('company', 'branch');

            return response()->json([
                'message' => __('success'),
                'data'  => AttendanceMachineListingResource::collection($machines)
            ], 200);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function machineToggle(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $result = AttendanceMachine::where('id', $request->attendance_machine_id)
                        ->update(['is_active' => $request->is_active]);

            if($result === 1)
            {
                return response()->json([
                    'message' => __('updated_success')
                ], 200);
            }

            return response()->json([
                'message' => __('something_went_wrong')
            ], 200);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function checkStatus(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $status = $this->attendanceMachineService->checkMachineStatus($request->device_sn);

            if($status)
            {
                return response()->json([
                    // 'message' => 'ON-LINE'
                    'message' =>  __('machine_online')
                ], 200);
            }

            return response()->json([
                // 'message' => 'OFF-LINE'
                'message' => __('machine_offline')
            ], 200);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

}
