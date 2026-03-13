<?php

namespace App\Http\Controllers\Web;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Repositories\DashboardRepository;
use App\Services\Client\ClientService;
use App\Services\Project\ProjectService;
use App\Services\Task\TaskService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Attendance;
use App\Models\TeamMeeting;

class DashboardController extends Controller
{
    private DashboardRepository $dashboardRepo;
    private ClientService $clientService;
    private TaskService $taskService;
    private ProjectService $projectService;

    public function __construct(DashboardRepository $dashboardRepo,
                                ClientService       $clientService,
                                TaskService         $taskService,
                                ProjectService      $projectService
    )
    {
        $this->dashboardRepo = $dashboardRepo;
        $this->clientService = $clientService;
        $this->projectService = $projectService;
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        try {
            $dashboardDetail = null;
            $topClients = collect();
            $taskPieChartData = [];
            $projectCardDetail = [];
            $recentProjects = collect();
            $recentAttendance = collect();
            $recentTeamMeetings = collect();
            $multipleAttendance = false;
            $employeeStats = [];
            $projectStats = [];

            $appTimeSetting = AppHelper::check24HoursTimeAppSetting();
            $companyId = AppHelper::getWebAdminCompanyId();

            if ($companyId) {
                $date = AppHelper::yearDetailToFilterData();

                $dashboardDetail = $this->dashboardRepo->getCompanyDashboardDetail($companyId, $date);
                $topClients = $this->clientService->getTopClientsOfCompany();
                $taskPieChartData = $this->taskService->getTaskDataForPieChart();
                $projectCardDetail = $this->projectService->getProjectCardData();
                
                // Get employee statistics
                $employeeStats = $this->dashboardRepo->getEmployeeStats($companyId);
                
                // Get project statistics
                $projectStats = $this->dashboardRepo->getProjectStats($companyId);

                $projectSelect = ['id', 'name', 'start_date', 'deadline', 'status', 'priority'];
                $withProject = [
                    'projectLeaders.user:id,name,avatar',
                    'tasks:id,project_id',
                    'completedTask:id,project_id'
                ];

                $recentProjects = $this->projectService
                    ->getRecentProjectListsForDashboard($projectSelect, $withProject);

                $multipleAttendance = AppHelper::getAttendanceLimit();
            }

            return view('admin.dashboard', compact(
                'dashboardDetail',
                'topClients',
                'taskPieChartData',
                'projectCardDetail',
                'recentProjects',
                'appTimeSetting',
                'multipleAttendance',
                'employeeStats',
                'projectStats'
            ));

        } catch (\Throwable $exception) {
            return redirect()
                ->back()
                ->with('danger', $exception->getMessage());
        }
    }


    public function showQR()
    {
        $url = $this->generateUrl();

        return view('admin.print_qr',compact('url'));
    }

    private function generateUrl(): string
    {
        $url = url('/');
        $parsedUrl = parse_url($url);
        $scheme = $parsedUrl['scheme'] ?? 'https';
        $host = $parsedUrl['host'] ?? '';

        // Remove 'www.' if present
        $host = preg_replace('/^www\./', '', $host);

        return base64_encode("{$scheme}://{$host}/") ;
    }


}
