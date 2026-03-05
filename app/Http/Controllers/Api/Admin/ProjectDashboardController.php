<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Project;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ProjectDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $query = Project::query()
                ->where('branch_id', $request->branch_id)
                ->when($request->filled('department_ids'), function ($q) use ($request) {
                    $q->where(function ($subQuery) use ($request) {
                        foreach ($request->department_ids as $departmentId) {
                            $subQuery->orWhereJsonContains('department_ids', (string) $departmentId);
                        }
                    });
                })->withoutGlobalScope('branch');

            $activeProjectsCount = (clone $query)
                ->where('status', 'in_progress')
                ->count();

            $pendingProjectsCount = (clone $query)
                ->where('status', 'not_started')
                ->count();

            $upcomingDeadlinesCount = (clone $query)
                ->whereDate('deadline', '>=', Carbon::today())
                ->whereDate('deadline', '<=', Carbon::today()->addDays(7))
                ->count();

            $overdueProjectsCount = (clone $query)
                ->whereDate('deadline', '<', Carbon::today())
                ->where('status', 'in_progress')
                ->count();

            $completed = (clone $query)
                ->where('status', 'completed')
                ->count();

            return response()->json([
                'message' => __('success'),
                'data' => [
                    'active' => $activeProjectsCount,
                    'pending' => $pendingProjectsCount,
                    'upcoming_deadlines' => $upcomingDeadlinesCount,
                    'overdue' => $overdueProjectsCount,
                    'completed' => $completed
                ],
            ]);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function overallReport()
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

            if (!$company) {
                return response()->json(['message' => __('company_not_found')], 404);
            }

            $branchIds = Branch::where('company_id', $company->id)->pluck('id');
            $query = Project::withoutGlobalScope('branch')->whereIn('branch_id', $branchIds);

            $counts = [
                'total'     => (clone $query)->count(),
                'pending'   => (clone $query)->where('status', 'not_started')->count(),
                'active'    => (clone $query)->where('status', 'in_progress')->count(),
                'completed' => (clone $query)->where('status', 'completed')->count(),
                'overdue'   => (clone $query)
                    ->whereNotNull('deadline')
                    ->where('status', '!=', 'completed')
                    ->whereDate('deadline', '<', now())
                    ->count(),
            ];

            return response()->json([
                // 'message' => 'success',
                'message' => __('success'),
                'data'    => $counts,
            ]);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

}
