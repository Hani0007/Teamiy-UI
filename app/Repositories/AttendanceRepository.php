<?php

namespace App\Repositories;

use App\Helpers\AppHelper;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceRepository
{

    public function getAllCompanyEmployeeAttendanceDetailOfTheDay($filterParameter)
{
    $date = $filterParameter['attendance_date'];
    $companyId = $filterParameter['company_id'];

    // ---- Subquery with company_id filter (correct place) ----
    $subQuery = "
        (
            SELECT
                user_id,
                attendance_date,
                MIN(check_in_at) AS check_in_at,
                MAX(check_out_at) AS check_out_at,

                SUBSTRING_INDEX(GROUP_CONCAT(check_in_type ORDER BY check_in_at ASC), ',', 1) AS check_in_type,
                SUBSTRING_INDEX(GROUP_CONCAT(check_out_type ORDER BY check_out_at DESC), ',', 1) AS check_out_type,
                SUBSTRING_INDEX(GROUP_CONCAT(check_in_note ORDER BY check_in_at ASC), ',', 1) AS check_in_note,
                SUBSTRING_INDEX(GROUP_CONCAT(check_out_note ORDER BY check_out_at DESC), ',', 1) AS check_out_note,

                MAX(attendance_status) AS attendance_status,
                MAX(edit_remark) AS edit_remark,
                MAX(worked_hour) AS worked_hour,
                MAX(check_in_latitude) AS check_in_latitude,
                MAX(check_out_latitude) AS check_out_latitude,
                MAX(check_in_longitude) AS check_in_longitude,
                MAX(check_out_longitude) AS check_out_longitude,
                MAX(created_by) AS created_by,
                MAX(updated_by) AS updated_by,
                MAX(night_checkin) AS night_checkin,
                MAX(night_checkout) AS night_checkout,
                MAX(overtime) AS overtime,
                MAX(undertime) AS undertime,
                MAX(office_time_id) AS office_time_id,

                MAX(id) AS latest_attendance_id

            FROM attendances
            WHERE attendance_date = ?
            AND company_id = ?
            GROUP BY user_id, attendance_date
        ) AS attendances
    ";

    return User::query()
        ->select(
            'attendances.latest_attendance_id AS attendance_id',
            'users.id AS user_id',
            'users.name AS user_name',
            'users.company_id',
            'users.branch_id',
            'users.joining_date',
            'branches.name AS branch_name',
            'users.department_id',
            'departments.dept_name AS department_name',
            'companies.name AS company_name',

            'attendances.attendance_date',
            'attendances.attendance_status',
            'attendances.check_in_at',
            'attendances.check_out_at',
            'attendances.check_in_latitude',
            'attendances.check_out_latitude',
            'attendances.check_in_longitude',
            'attendances.check_out_longitude',
            'attendances.edit_remark',
            'attendances.worked_hour',
            'attendances.check_in_type',
            'attendances.check_out_type',
            'attendances.created_by',
            'attendances.updated_by',
            'attendances.check_in_note',
            'attendances.check_out_note',
            'attendances.night_checkin',
            'attendances.night_checkout',
            'attendances.overtime',
            'attendances.undertime',
            'office_times.shift_type as shift'
        )

        ->leftJoin(DB::raw($subQuery), 'users.id', '=', 'attendances.user_id')
        ->addBinding([$date, $companyId], 'join')

        ->join('companies', 'users.company_id', '=', 'companies.id')
        ->join('branches', 'users.branch_id', '=', 'branches.id')
        ->join('departments', 'users.department_id', '=', 'departments.id')
        ->leftJoin('office_times', 'attendances.office_time_id', '=', 'office_times.id')

        // ⭐ VERY IMPORTANT FIX
        ->where('users.company_id', $companyId)

        ->when(
            isset($filterParameter['branch_id']) && $filterParameter['branch_id'],
            fn($q) => $q->where('users.branch_id', $filterParameter['branch_id'])
        )
        ->when(
            isset($filterParameter['department_id']) && $filterParameter['department_id'],
            fn($q) => $q->where('users.department_id', $filterParameter['department_id'])
        )

        ->where('users.is_active', 1)
        ->where('users.status', 'verified')

        ->orderByDesc('attendance_id')
        ->get();
}


    public function getEmployeeAttendanceDetailOfTheMonth($filterParameters, $select = ['*'], $with = [])
    {
        $attendanceList = Attendance::with($with)
            ->select($select)
            ->where('user_id', $filterParameters['user_id']);
        if (isset($filterParameters['start_date'])) {
            $attendanceList->whereBetween('attendance_date', [$filterParameters['start_date'], $filterParameters['end_date']]);
        } else {
            $attendanceList
                ->whereMonth('attendance_date', '=', $filterParameters['month'])
                ->whereYear('attendance_date', '=', $filterParameters['year']);
        }
        return $attendanceList->get();
    }

    public function getEmployeeAttendanceExport($startDate, $endDate, $with, $filterData)
    {
        return Attendance::with($with)
            ->selectRaw('
                attendances.user_id,
                attendances.attendance_date,
                MIN(attendances.check_in_at) as check_in_at,
                MAX(attendances.check_out_at) as check_out_at,
                MAX(attendances.attendance_status) as attendance_status,
                MAX(attendances.edit_remark) as edit_remark,
                MAX(attendances.worked_hour) as worked_hour,
                MAX(attendances.check_in_latitude) as check_in_latitude,
                MAX(attendances.check_out_latitude) as check_out_latitude,
                MAX(attendances.check_in_longitude) as check_in_longitude,
                MAX(attendances.check_out_longitude) as check_out_longitude,
                MAX(attendances.created_by) as created_by,
                MAX(attendances.updated_by) as updated_by,
                MAX(attendances.night_checkin) as night_checkin,
                MAX(attendances.night_checkout) as night_checkout,
                MAX(attendances.overtime) as overtime,
                MAX(attendances.undertime) as undertime,
                MAX(attendances.office_time_id) as office_time_id,
                MAX(attendances.id) as id
            ')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->whereBetween('attendances.attendance_date', [$startDate, $endDate])
            ->when(isset($filterData['branch_id']), function ($query) use ($filterData) {
                $query->where('users.branch_id', $filterData['branch_id']);
            })
            ->when(isset($filterData['department_id']), function ($query) use ($filterData) {
                $query->where('users.department_id', $filterData['department_id']);
            })
            ->when(isset($filterData['employee_id']), function ($query) use ($filterData) {
                $query->where('users.id', $filterData['employee_id']);
            })
            ->groupBy('attendances.user_id', 'attendances.attendance_date')
            ->orderBy('users.name')
            ->orderBy('attendances.attendance_date')
            ->get();
    }

    public function findEmployeeTodayCheckInDetail($userId, $attendanceDate, $select = ['*'])
    {
        $attendance = Attendance::select($select)
            ->where('user_id', $userId)
            ->where(
                'attendance_date',
                $attendanceDate
                    ? Carbon::parse($attendanceDate)->format('Y-m-d')
                    : Carbon::now()->format('Y-m-d')
            )
            ->orderBy('created_at', 'desc')
            ->first();

            return $attendance;
    }


    public function findEmployeeCheckInDetailForNightShift($userId, $select = ['*'])
    {
        return Attendance::select($select)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function todayAttendanceDetail($userId)
    {
        return Attendance::where('user_id', $userId)
            ->where('attendance_date', Carbon::now()->format('Y-m-d'))
            ->whereNotNull('check_in_at')
            ->whereNotNull('check_out_at')
            ->count();
    }

    public function findAttendanceDetailById($id, $select = ['*'])
    {
        return Attendance::where('id', $id)->first();
    }

    public function updateAttendanceStatus($attendanceDetail)
    {
        return $attendanceDetail->update([
            'attendance_status' => !$attendanceDetail->attendance_status
        ]);
    }

    public function delete(Attendance $attendanceDetail)
    {
        return $attendanceDetail->delete();
    }

    public function storeAttendanceDetail($validatedData)
    {
        return Attendance::create($validatedData)->fresh();
    }

    public function updateAttendanceDetail($attendanceDetail, $validatedData)
    {
        $attendanceDetail->update($validatedData);
        return $attendanceDetail;
    }
}
