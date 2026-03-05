<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardAPIResource;
use App\Mail\TrialExpiringMail;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Company;
use App\Models\LeaveRequestMaster;
use App\Models\TimeLeave;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $today = Carbon::today();
            $roleName = $user->getRoleNames()->first();

            if ($roleName !== 'super-admin') {
                $companyId   = Company::where('admin_id', $user->parent_id)->value('id');
            } else {
                $user->loadMissing('company:id,admin_id');
                $companyId   = $user->company->id ?? null;
                $trialExpiry = $user->trial_expiry;
                $planId      = $user->plan_id;

                if ($planId == 2 && $trialExpiry) {
                    $trialExpiryDate = Carbon::parse($trialExpiry);
                    $daysLeft = $today->diffInDays($trialExpiryDate, false);

                    if ($today->greaterThan($trialExpiryDate)) {
                        return response()->json([
                            // 'message' => 'Your trial plan has expired. Please upgrade your plan.'
                            'message' => __('trial_plan_expired')
                        ], 400);
                    }

                    if ($daysLeft <= 2 && $daysLeft >= 0) {
                        //Log::info("Trial plan for Admin ID {$user->id} will expire in {$daysLeft} day(s).");

                        try {
                            Mail::to($user->email)->send(new TrialExpiringMail($user, $daysLeft));
                        } catch (\Exception $e) {
                            Log::error("Failed to send trial expiring email to {$user->email}: " . $e->getMessage());
                        }
                    }
                }
            }

            $totalBranches   = Branch::where('company_id', $companyId)->count();
            $totalEmployees  = User::where('company_id', $companyId)->count();
            $fullDayLeaves   = LeaveRequestMaster::where('status', 'pending')
                ->where('company_id', $companyId)
                ->count();
            $shortLeaves     = TimeLeave::where('status', 'pending')
                ->where('company_id', $companyId)
                ->count();
            $totalPendingLeaves = $fullDayLeaves + $shortLeaves;

            $totalCheckInToday = Attendance::whereNotNull('check_in_at')
                ->whereDate('attendance_date', $today)
                ->where('company_id', $companyId)
                ->distinct('user_id')
                ->count('user_id');

            $employeesOnLeaveToday = LeaveRequestMaster::whereDate('leave_from', '<=', $today)
                ->whereDate('leave_to', '>=', $today)
                ->where('company_id', $companyId)
                ->where('status', 'approved')->count();

            $dashboardData = [
                'branches'                 => $totalBranches,
                'employees'                => $totalEmployees,
                'pending_leaves'           => $totalPendingLeaves,
                'checkins_today'           => $totalCheckInToday,
                'employees_on_leave_today' => $employeesOnLeaveToday
            ];

            return response()->json([
                'message' => __('dashboard_data_fetched'),
                'data'    => new DashboardAPIResource($dashboardData),
            ], 200);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}
