<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exports\AttendanceReportExport;
use App\Helpers\AppHelper;
use App\Helpers\AttendanceHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceMarkedResource;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\AttendanceSectionResource;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\LeaveRequestMaster;
use App\Models\User;
use App\Repositories\RouterRepository;
use App\Repositories\UserRepository;
use App\Services\Attendance\AttendanceService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;

class AttendanceSectionController extends Controller
{
    public function __construct(protected AttendanceService $attendanceService, protected UserRepository $userRepository, protected RouterRepository  $routerRepo)
    {}

    public function index(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $roleName = $user->getRoleNames()->first();

            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
                $companyId = $company?->id;
            } else {
                $user->load('company');
                $companyId = $user->company->id;
            }

            $filterParameter = [
                'attendance_date' => $request->attendance_date ?? AppHelper::getCurrentDateInYmdFormat(),
                'company_id' => $companyId,
                'branch_id' => $request->branch_id ?? null,
                'department_id' => $request->department_id ?? null,
                'date_in_bs' => false,
            ];

            $attendanceDetail = $this->attendanceService->getAllCompanyEmployeeAttendanceDetailOfTheDay($filterParameter);

            if ($attendanceDetail->isNotEmpty()) {
                return response()->json([
                    // 'message' => 'success',
                    'message' => __('success'),
                    'data'    => AttendanceSectionResource::collection($attendanceDetail)
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

    // public function checkInEmployee(Request $request)
    // {
    //     $user = auth()->guard('admin-api')->user();

    //     $employee = User::where('id', $request->userId)->first();

    //     try {
    //         if (!$user) {
    //             return response()->json(['message' => 'Unauthorized access denied'], 401);
    //         }

    //         $checkInAttendance = $this->checkIn($request->userId, $employee->company_id);

    //         if ($checkInAttendance) {
    //             return response()->json([
    //                 'message' => 'successfully checkin',
    //                 'data'    => []
    //             ], 200);
    //         }

    //         return response()->json([
    //             'message' => 'Something went wrong',
    //             'data'    => []
    //         ], 400);

    //     } catch (Exception $ex) {
    //         return response()->json(['message' => $ex->getMessage()], 400);
    //     }
    // }

    public function checkAttendance(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        $employee = User::where('id', $request->userId)->first();

        if(!$employee)
        {
            return response()->json(['message' => __('employee_not_exist')], 400);
        }

        $checkIn = $request->check_in_at;
        $checkOut = $request->check_out_at;
        $attendanceDate = $request->attendance_date;
        $checkInNote = $request->check_in_note ?? null;
        $checkOutNote = $request->check_out_note ?? null;

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $roleName = $user->getRoleNames()->first();

            if ($roleName !== 'super-admin') {
                $company = Company::where('admin_id', $user->parent_id)->first();
                $companyId = $company?->id;
            } else {
                $user->load('company');
                $companyId = $user->company->id;
            }

            $todayLeave = LeaveRequestMaster::where('requested_by', $employee->id)
                                ->whereRaw('? BETWEEN DATE(leave_from) AND DATE(leave_to)', [$attendanceDate])
                                ->first();

            if(isset($todayLeave) && $todayLeave->status == 'accepted')
            {
                return response()->json([
                    'message' => __('person_on_leave')
                ], 400);
            }

            if(isset($todayLeave) && $todayLeave->status == 'pending')
            {
                return response()->json([
                    'message' => __('request_leave_approve_reject')
                ], 400);
            }

            $isExist = Attendance::where('user_id', $employee->id)
                        ->where('company_id', $companyId)
                        ->where('attendance_date', $attendanceDate)
                        ->first();

            $markAttendance = null;

            if(!empty($checkIn) && empty($isExist->check_in_at))
            {
                $markAttendance = $this->checkIn($request->userId, $employee->company_id, $checkIn, $checkOut, $attendanceDate, $checkInNote);
            }

            if(!empty($checkOut) && empty($isExist->check_out_at))
            {
                $markAttendance = $this->checkOut($request->userId, $employee->company_id, $checkIn, $checkOut, $attendanceDate, $checkOutNote);
            }

            if ($markAttendance instanceof JsonResponse) {
                $original = $markAttendance->getData(true);
                return response()->json([
                    'message' => $original['message'] ?? __('something_went_wrong'),
                    'data'    => []
                ], $markAttendance->getStatusCode());
            }

            if (empty($markAttendance)) {
                return response()->json([
                    'message' => __('attendance_already_marked_admin'),
                    'data'    => []
                ], 400);
            }

            $markAttendance->load('employee:id,name');

            if ($markAttendance) {
                return response()->json([
                    'message' => __('attendance_marked'),
                    'data'    => new AttendanceMarkedResource($markAttendance)
                ], 200);
            }

            return response()->json([
                'message' => __('something_went_wrong'),
                'data'    => []
            ], 400);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    private function checkIn($userId, $companyId, $checkIn, $checkOut, $attendanceDate, $checkInNote)
    {
        try {
            $userDetail = $this->userRepository->findUserDetailById($userId);
            if(!$userDetail){
                return response()->json(['message' => __('record_not_found')], 404);
            }

            $validatedData = $this->prepareDataForAttendance($companyId, $userDetail,'checkIn');

            if(!$validatedData)
            {
                return response()->json(['message' => __('router_not_found')], 400);
            }

            $nightShift = AppHelper::isOnNightShift($userId);
            $validatedData['night_shift'] = $nightShift;
            $validatedData['office_time_id'] = $userDetail['office_time_id'];
            $validatedData['allow_holiday_check_in'] = $userDetail['allow_holiday_check_in'];
            $validatedData['user_id'] = $userId;
            $validatedData['check_in_at'] = $checkIn;
            $validatedData['check_out_at'] = $checkOut;
            $validatedData['attendance_date'] = $attendanceDate;
            $validatedData['check_in_note'] = $checkInNote;

            DB::beginTransaction();
                $checkInAttendance =  $this->attendanceService->newCheckIn($validatedData);
            $this->userRepository->updateUserOnlineStatus($userDetail,1);

            DB::commit();

            return $checkInAttendance;
        } catch (Exception $ex) {
             DB::rollBack();
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    private function prepareDataForAttendance($companyId, $userDetail, $checkStatus)
    {
        $with = ['branch:id,branch_location_latitude,branch_location_longitude'];
        $select = ['routers.*'];
        $userBranchId = $userDetail->branch_id;

        $routerDetail = $this->routerRepo->findRouterDetailByBranchId($userBranchId,$with,$select);

        if (!$routerDetail) {
            return false;
        }

        if($checkStatus == 'checkIn'){
            $validatedData['check_in_latitude'] = $routerDetail->branch->branch_location_latitude;
            $validatedData['check_in_longitude'] = $routerDetail->branch->branch_location_longitude;

        }else{
            $validatedData['check_out_latitude'] = $routerDetail->branch->branch_location_latitude;
            $validatedData['check_out_longitude'] = $routerDetail->branch->branch_location_longitude;
        }
        $validatedData['user_id'] = $userDetail->id;
        $validatedData['company_id'] = $companyId;
        $validatedData['router_bssid'] = $routerDetail->router_ssid;
        return $validatedData;
    }

    // public function checkOutEmployee(Request $request)
    // {
    //     $user = auth()->guard('admin-api')->user();

    //     $employee = User::where('id', $request->userId)->first();

    //     try {
    //         if (!$user) {
    //             return response()->json(['message' => 'Unauthorized access denied'], 401);
    //         }

    //         $checkInAttendance = $this->checkOut($request->userId, $employee->company_id);

    //         if ($checkInAttendance) {
    //             return response()->json([
    //                 'message' => 'successfully checkout',
    //                 'data'    => []
    //             ], 200);
    //         }

    //         return response()->json([
    //             'message' => 'Something went wrong',
    //             'data'    => []
    //         ], 400);

    //     } catch (Exception $ex) {
    //         return response()->json(['message' => $ex->getMessage()], 400);
    //     }
    // }

    private function checkOut($userId, $companyId, $checkIn, $checkOut, $attendanceDate, $checkOutNote)
    {
        try{
            $nightShift = AppHelper::isOnNightShift($userId);
            // $select = ['name'];
            // $permissionKeyForNotification = 'employee_check_out';
            $userDetail = $this->userRepository->findUserDetailById($userId);
            $validatedData = $this->prepareDataForAttendance($companyId, $userDetail,'checkout');
            // if($dashboardAttendance){
            //     $validatedData['latitude'] = $locationData['lat'];
            //     $validatedData['longitude'] = $locationData['long'];
            // }

            if($nightShift){
                $attendanceData = $this->attendanceService->findEmployeeAttendanceDetailForNightShift($userId);
            }else{
                $attendanceData = $this->attendanceService->findEmployeeTodayAttendanceDetail($userId, $attendanceDate);
            }


            if(!$attendanceData){
                return response()->json(['message' => __('you_cannot_checkout')], 400);
            }

            if($nightShift && isset($attendanceData->night_checkout)){
                return response()->json(['message' => __('employee_already_checkout')], 400);
            }

            $validatedData['night_shift'] = $nightShift;
            $validatedData['user_id'] = $userId;
            $validatedData['office_time_id'] = $userDetail['office_time_id'];
            $validatedData['check_in'] = $checkIn;
            $validatedData['check_out'] = $checkOut;
            $validatedData['attendance_date'] = $attendanceDate;
            $validatedData['check_in_at'] = $checkIn;
            $validatedData['check_out_at'] = $checkOut;
            $validatedData['attendance_date'] = $attendanceDate;
            $validatedData['check_out_note'] = $checkOutNote;

            DB::beginTransaction();
                $attendanceCheckOut = $this->attendanceService->newCheckOut($attendanceData,$validatedData);

                $this->userRepository->updateUserOnlineStatus($userDetail,0);
            DB::commit();
            // AppHelper::sendNotificationToAuthorizedUser(
            //     __('message.checkout_notification'),
            //     __('message.employee_checkout', [
            //         'name' => ucfirst($userDetail->name),
            //         'time'=> AttendanceHelper::changeTimeFormatForAttendanceView($attendanceCheckOut->check_out_at)
            //     ]),
            //     $permissionKeyForNotification
            // );
            return $attendanceCheckOut;
        } catch (Exception $ex) {
             DB::rollBack();
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function exportAttendance(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $request->validate([
                'attendance_date' => 'required',
                'employee_id'     => 'required',
            ]);

            $attendance_date = $request['attendance_date'];
            $dates = explode(' - ', $attendance_date);

            if (count($dates) !== 2) {
                return response()->json([
                    'message' => __('invalid_date'),
                    'data'    => []
                ], 422);
            }

            $startDate = \DateTime::createFromFormat('Y-m-d', trim($dates[0]));
            $endDate   = \DateTime::createFromFormat('Y-m-d', trim($dates[1]));

            $filterData   = [];
            $isBsEnabled  = AppHelper::ifDateInBsEnabled();

            if ($request->all()) {
                $employee = User::where('id', $request->employee_id)->first();

                $filterData['branch_id']     = $employee->branch_id;
                $filterData['employee_id']   = $request['employee_id'];
                $filterData['department_id'] = $employee->department_id;

                $firstDay = $startDate->format('Y-m-d');
                $lastDay  = $endDate->format('Y-m-d');

                $attendanceData = $this->attendanceService
                    ->getAttendanceExportData($firstDay, $lastDay, $filterData);

                if (count($attendanceData) > 0) {
                    // Create a unique hash for filters + date range
                    $hash = md5(json_encode($filterData) . $firstDay . $lastDay . ($isBsEnabled ? 1 : 0));

                    $fileName = "attendance-report-{$hash}.xlsx";
                    $filePath = 'exports/' . $fileName;

                    // Check if already exists in storage/app/public/exports/
                    if (!\Storage::disk('public')->exists($filePath)) {
                        \Maatwebsite\Excel\Facades\Excel::store(
                            new AttendanceReportExport($attendanceData, $isBsEnabled),
                            $filePath,
                            'public'
                        );
                    }

                    $downloadUrl = asset('storage/' . $filePath);

                    return response()->json([
                        'message'      => __('attendance_report_generated'),
                        'download_url' => $downloadUrl
                    ], 200);
                }

                return response()->json([
                    'message' => __('record_not_found'),
                    'data'    => []
                ], 404);
            }

            return response()->json([
                'message' => __('invalid_request'),
                'data'    => []
            ], 400);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function employeeAttendanceDetail(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $request->validate([
                'attendance_date' => 'required',
                'employee_id'     => 'required',
            ]);

            $attendance_date = $request['attendance_date'];
            $dates = explode(' - ', $attendance_date);

            if (count($dates) !== 2) {
                return response()->json([
                    'message' => __('invalid_date_format'),
                    'data'    => []
                ], 422);
            }

            $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($dates[0]));
            $endDate   = \Carbon\Carbon::createFromFormat('Y-m-d', trim($dates[1]));

            $filterData = [];

            if ($request->all()) {
                $employee = User::where('id', $request->employee_id)->first();

                if (!$employee) {
                    return response()->json([
                        'message' => __('record_not_found'),
                        'data'    => []
                    ], 404);
                }

                // $joiningDate = $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date) : null;
                // $today       = now()->startOfDay();

                // if ($joiningDate && $startDate->lt($joiningDate)) {
                //     $startDate = $joiningDate;
                // }

                // if ($endDate->gt($today)) {
                //     $endDate = $today;
                // }

                if ($startDate->gt($endDate)) {
                    return response()->json([
                        'message' => __('no_attendance_record_in_give_range'),
                        'data'    => []
                    ], 200);
                }

                $filterData['branch_id']     = $employee->branch_id;
                $filterData['employee_id']   = $request['employee_id'];
                $filterData['department_id'] = $employee->department_id;

                $firstDay = $startDate->format('Y-m-d');
                $lastDay  = $endDate->format('Y-m-d');

                $attendanceData = $this->attendanceService
                    ->getAttendanceExportData($firstDay, $lastDay, $filterData);
            }

            $allDates = collect();
            $period = \Carbon\CarbonPeriod::create($startDate, $endDate);

            $flatRecords = collect($attendanceData ?? [])
                ->flatMap(function ($dates) {
                    return collect($dates)->flatMap(function ($day) {
                        return $day['data'];
                    });
                });

            foreach ($period as $date) {
                $dateString = $date->format('Y-m-d');

                $record = $flatRecords->firstWhere('attendance_date', $dateString);

                if ($record) {
                    $allDates->push((new AttendanceResource($record))->toArray(request()));
                } else {
                    $allDates->push([
                        'date'         => $date->format('d M Y (l)'),
                        'check_in_at'  => null,
                        'check_out_at' => null,
                        'worked_hour'  => null,
                        'status'       => $this->isWeekend($date) ? 'Weekend' : 'Absent',
                        'shift'        => "",
                    ]);
                }
            }

            return response()->json([
                'message' =>__('success'),
                'data'    => $allDates
            ], 200);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    private function isWeekend($date)
    {
        return in_array($date->format('l'), ['Saturday', 'Sunday']);
    }
}
