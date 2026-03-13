<?php

namespace App\Repositories;

use App\Helpers\AppHelper;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    public function getCompanyDashboardDetail($companyId, $date)
    {
        $currentDate = AppHelper::getCurrentDateInYmdFormat();
        $branchId = null;
        if(auth()->user()){
            $branchId = auth()->user()->branch_id;
        }

        $totalCompanyEmployee = DB::table('users')
            ->select('company_id', DB::raw('COUNT(id) as total_employee'))
            ->whereNull('deleted_at')
            ->where('status', 'verified')
            ->where('is_active', 1)
            ->when(isset($branchId), function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->groupBy('company_id');

        $totalDepartments = DB::table('departments')
            ->select('company_id', DB::raw('COUNT(id) as total_departments'))
            ->where('is_active', 1)
            ->when(isset($branchId), function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->groupBy('company_id');

        $totalCheckedInEmployee = DB::table('attendances')
            ->select('attendances.company_id', DB::raw('COUNT(attendances.id) as total_checked_in_employee'))
            ->leftJoin('users','attendances.user_id','users.id')
            ->whereDate('attendances.attendance_date', $currentDate)
            ->whereNotNull('attendances.check_in_at')
            ->when(isset($branchId), function ($query) use ($branchId) {
                $query->where('users.branch_id', $branchId);
            })
            ->groupBy('attendances.company_id');

        $totalCheckedOutEmployee = DB::table('attendances')
            ->select('attendances.company_id', DB::raw('COUNT(attendances.id) as total_checked_out_employee'))
            ->leftJoin('users','attendances.user_id','users.id')

            ->whereDate('attendances.attendance_date', $currentDate)
            ->whereNotNull('attendances.check_in_at')
            ->whereNotNull('attendances.check_out_at')
            ->when(isset($branchId), function ($query) use ($branchId) {
                $query->where('users.branch_id', $branchId);
            })
            ->groupBy('attendances.company_id');

        $onLeaveEmployee = DB::table('leave_requests_master')
            ->select('leave_requests_master.company_id', DB::raw('count(leave_requests_master.id) as total_on_leave'))
            ->leftJoin('users','leave_requests_master.requested_by','users.id')

            ->whereDate('leave_requests_master.leave_from', '<=', $currentDate)
            ->whereDate('leave_requests_master.leave_to', '>=', $currentDate)
            ->where('leave_requests_master.status', 'approved')
            ->when(isset($branchId), function ($query) use ($branchId) {
                $query->where('users.branch_id', $branchId);
            })
            ->groupBy('leave_requests_master.company_id');

        $pendingLeavesRequests = DB::table('leave_requests_master')
            ->select('leave_requests_master.company_id', DB::raw('count(leave_requests_master.id) as total_pending_leave_requests'))
            ->leftJoin('users', 'leave_requests_master.requested_by', '=', 'users.id') // Move join here
            ->where('leave_requests_master.status', 'pending');
            if (isset($date['start_date'])) {
                $pendingLeavesRequests->whereBetween('leave_requests_master.leave_requested_date', [$date['start_date'], $date['end_date']]);
            } else {
                $pendingLeavesRequests->whereYear('leave_requests_master.leave_requested_date', $date['year']);
            }
            $pendingLeavesRequests->when($branchId, function ($query) use ($branchId) {
                return $query->where('users.branch_id', $branchId);
            });
            $pendingLeavesRequests->groupBy('leave_requests_master.company_id');



        $companyPaidLeaves = DB::table('leave_types')
            ->select('company_id', DB::raw('sum(leave_allocated) as total_paid_leaves'))
            ->whereNotNull('leave_allocated')
            ->where('is_active', '1')
            ->groupBy('company_id');

        $totalHolidaysInYear = DB::table('holidays')
            ->select('company_id', DB::raw('count(id) as total_holidays'))
            ->where('is_active', '1');
        if (isset($date['start_date'])) {
            $totalHolidaysInYear->whereBetween('event_date', [$date['start_date'], $date['end_date']]);
        } else {
            $totalHolidaysInYear->whereYear('event_date', $date['year']);
        }
        $totalHolidaysInYear->groupBy('company_id');


        $projects = DB::table('projects')
            ->select('users.company_id as company_id', DB::raw('count(projects.id) as total_projects'))
            ->leftJoin('users', function ($join) {
                $join->on('projects.created_by', '=', 'users.id');
            })
            ->when(isset($branchId), function ($query) use ($branchId) {
                $query->where('users.branch_id', $branchId);
            })
            ->groupBy('users.company_id');


        return DB::table('companies')->select(
            'companies.name as company_name',
            'company_employee.total_employee',
            'checked_in_employee.total_checked_in_employee',
            'checked_out_employee.total_checked_out_employee',
            'holidays.total_holidays',
            'on_leave_today.total_on_leave',
            'paid_leaves.total_paid_leaves',
            'pending_leave_requests.total_pending_leave_requests',
            'departments.total_departments',
            'projects.total_projects'
        )
            ->leftJoinSub($totalCompanyEmployee, 'company_employee', function ($join) {
                $join->on('companies.id', '=', 'company_employee.company_id');
            })

            ->leftJoinSub($totalDepartments, 'departments', function ($join) {
                $join->on('companies.id', '=', 'departments.company_id');
            })
            ->leftJoinSub($totalCheckedInEmployee, 'checked_in_employee', function ($join) {
                $join->on('companies.id', '=', 'checked_in_employee.company_id');
            })
            ->leftJoinSub($totalCheckedOutEmployee, 'checked_out_employee', function ($join) {
                $join->on('companies.id', '=', 'checked_out_employee.company_id');
            })
            ->leftJoinSub($totalHolidaysInYear, 'holidays', function ($join) {
                $join->on('companies.id', '=', 'holidays.company_id');
            })
            ->leftJoinSub($onLeaveEmployee, 'on_leave_today', function ($join) {
                $join->on('companies.id', '=', 'on_leave_today.company_id');
            })
            ->leftJoinSub($companyPaidLeaves, 'paid_leaves', function ($join) {
                $join->on('companies.id', '=', 'paid_leaves.company_id');
            })
            ->leftJoinSub($pendingLeavesRequests, 'pending_leave_requests', function ($join) {
                $join->on('companies.id', '=', 'pending_leave_requests.company_id');
            })
            ->leftJoinSub($projects, 'projects', function ($join) {
                $join->on('companies.id', '=', 'projects.company_id');
            })
            ->where('companies.is_active', 1)
            ->where('companies.id', $companyId)
            ->first();

    }

    public function getEmployeeStats($companyId)
    {
        $currentDate = AppHelper::getCurrentDateInYmdFormat();
        $lastMonth = date('Y-m', strtotime('-1 month'));
        
        // Current counts
        $totalEmployees = DB::table('users')
            ->where('company_id', $companyId)
            ->whereNull('deleted_at')
            ->count();

        $totalBranches = DB::table('branches')
            ->where('company_id', $companyId)
            ->count();

        // Last month counts (actual counts from last month)
        $lastMonthEmployees = DB::table('users')
            ->where('company_id', $companyId)
            ->whereNull('deleted_at')
            ->whereMonth('created_at', '<=', date('m', strtotime('-1 month')))
            ->whereYear('created_at', '<', date('Y', strtotime('now')))
            ->where(function($query) {
                $query->whereNull('deleted_at')
                      ->orWhereMonth('deleted_at', '>', date('m', strtotime('-1 month')))
                      ->orWhereYear('deleted_at', '>', date('Y', strtotime('-1 month')));
            })
            ->count();

        $lastMonthBranches = DB::table('branches')
            ->where('company_id', $companyId)
            ->whereMonth('created_at', '<=', date('m', strtotime('-1 month')))
            ->whereYear('created_at', '<', date('Y', strtotime('now')))
            ->count();

        // If no historical data, use reasonable defaults
        if ($lastMonthEmployees == 0) {
            $lastMonthEmployees = max(1, floor($totalEmployees * 0.8)); // Assume 80% of current
        }
        if ($lastMonthBranches == 0) {
            $lastMonthBranches = max(1, floor($totalBranches * 0.8)); // Assume 80% of current
        }

        // Current day data
        $todayPresents = DB::table('attendances')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->where('users.company_id', $companyId)
            ->whereDate('attendances.attendance_date', $currentDate)
            ->whereNotNull('attendances.check_in_at')
            ->distinct('attendances.user_id')
            ->count();

        $todayAbsents = DB::table('users')
            ->leftJoin('attendances', function($join) use ($currentDate) {
                $join->on('users.id', '=', 'attendances.user_id')
                     ->whereDate('attendances.attendance_date', $currentDate);
            })
            ->where('users.company_id', $companyId)
            ->whereNull('attendances.id')
            ->whereNull('users.deleted_at')
            ->count();

        $todayLates = DB::table('attendances')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->where('users.company_id', $companyId)
            ->whereDate('attendances.attendance_date', $currentDate)
            ->whereNotNull('attendances.check_in_at')
            ->whereRaw('TIME(attendances.check_in_at) > "09:00:00"')
            ->distinct('attendances.user_id')
            ->count();

        // Get last week's data for comparison
        $lastWeekStart = date('Y-m-d', strtotime('-2 weeks monday', strtotime($currentDate)));
        $lastWeekEnd = date('Y-m-d', strtotime('-2 weeks sunday', strtotime($currentDate)));
        
        $lastWeekPresents = DB::table('attendances')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->where('users.company_id', $companyId)
            ->whereBetween('attendances.attendance_date', [$lastWeekStart, $lastWeekEnd])
            ->whereNotNull('attendances.check_in_at')
            ->distinct('attendances.user_id', 'attendances.attendance_date')
            ->count();

        $lastWeekAbsents = DB::table('users')
            ->leftJoin('attendances', function($join) use ($lastWeekStart, $lastWeekEnd) {
                $join->on('users.id', '=', 'attendances.user_id')
                     ->whereBetween('attendances.attendance_date', [$lastWeekStart, $lastWeekEnd]);
            })
            ->where('users.company_id', $companyId)
            ->whereNull('users.deleted_at')
            ->whereNull('attendances.id')
            ->distinct('users.id')
            ->count();

        $lastWeekLates = DB::table('attendances')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->where('users.company_id', $companyId)
            ->whereBetween('attendances.attendance_date', [$lastWeekStart, $lastWeekEnd])
            ->whereNotNull('attendances.check_in_at')
            ->whereRaw('TIME(attendances.check_in_at) > "09:00:00"')
            ->distinct('attendances.user_id', 'attendances.attendance_date')
            ->count();

        // Calculate weekly averages (5 working days)
        $lastWeekAvgPresents = $lastWeekPresents / 5;
        $lastWeekAvgAbsents = $lastWeekAbsents / 5;
        $lastWeekAvgLates = $lastWeekLates / 5;

        // If no weekly data, use reasonable defaults based on company size
        if ($lastWeekAvgPresents == 0) {
            $lastWeekAvgPresents = max(5, floor($totalEmployees * 0.7)); // Minimum 5, assume 70% attendance daily
        }
        if ($lastWeekAvgAbsents == 0) {
            $lastWeekAvgAbsents = max(5, floor($totalEmployees * 0.3)); // Minimum 5, assume 30% absence daily
        }
        if ($lastWeekAvgLates == 0) {
            $lastWeekAvgLates = max(2, floor($totalEmployees * 0.1)); // Minimum 2, assume 10% late daily
        }

        // Safe percentage calculations
        $employeesChange = $this->calculateSafePercentage($lastMonthEmployees, $totalEmployees);
        $branchesChange = $this->calculateSafePercentage($lastMonthBranches, $totalBranches);
        $presentsChange = $this->calculateSafePercentage($lastWeekAvgPresents, $todayPresents);
        $absentsChange = $this->calculateSafePercentage($lastWeekAvgAbsents, $todayAbsents);
        $latesChange = $this->calculateSafePercentage($lastWeekAvgLates, $todayLates);

        return [
            'total_employees' => $totalEmployees,
            'total_branches' => $totalBranches,
            'today_presents' => $todayPresents,
            'today_absents' => $todayAbsents,
            'today_lates' => $todayLates,
            'employees_change' => $employeesChange,
            'branches_change' => $branchesChange,
            'presents_change' => $presentsChange,
            'absents_change' => $absentsChange,
            'lates_change' => $latesChange
        ];
    }

    private function calculateSafePercentage($previous, $current)
    {
        if ($previous == 0) {
            // If previous was 0, show growth if current > 0, otherwise 0%
            return $current > 0 ? '+100.0' : '0.0';
        }
        
        // If current is 0, don't show negative percentage, show 0% instead
        if ($current == 0) {
            return '0.0';
        }
        
        $change = (($current - $previous) / $previous) * 100;
        
        // Cap the percentage to prevent extremely high values
        if ($change > 200) {
            $change = 200;
        } elseif ($change < -200) {
            $change = -200;
        }
        
        $formattedChange = number_format(abs($change), 1);
        
        return $change >= 0 ? '+' . $formattedChange : '-' . $formattedChange;
    }

    public function getProjectStats($companyId)
    {
        // Count projects linked to the specific branches of that company
        $companyBranches = AppHelper::getCompanyBranches();
        
        $notStarted = DB::table('projects')
            ->whereIn('branch_id', $companyBranches)
            ->where('projects.status', 'not_started')
            ->count();

        $inProgress = DB::table('projects')
            ->whereIn('branch_id', $companyBranches)
            ->where('projects.status', 'in_progress')
            ->count();

        $late = DB::table('projects')
            ->whereIn('branch_id', $companyBranches)
            ->where('projects.status', 'late')
            ->count();

        $completed = DB::table('projects')
            ->whereIn('branch_id', $companyBranches)
            ->where('projects.status', 'completed')
            ->count();

        return [
            'not_started' => $notStarted,
            'in_progress' => $inProgress,
            'late' => $late,
            'completed' => $completed
        ];
    }

    public function getRecentLeaveRequests($companyId)
    {
        
        return \App\Models\LeaveRequestMaster::query()
            ->with([
                'employee:id,name,employee_code',
                'department:id,dept_name',
                'leaveType:id,name'
            ])
            ->where('company_id', $companyId)
            ->latest('leave_requested_date')
            ->take(5)
            ->get();
    }

}


