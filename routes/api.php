<?php

use App\Http\Controllers\Api\Admin\AppSettingsApiController;
use App\Http\Controllers\Api\Admin\AssetController;
use App\Http\Controllers\Api\Admin\AssetTypeController;
use App\Http\Controllers\Api\Admin\AttendanceMachineController;
use App\Http\Controllers\Api\Admin\AttendanceSectionController;
use App\Http\Controllers\Api\Admin\Auth\AuthController;
use App\Http\Controllers\Api\Admin\BranchController;
use App\Http\Controllers\Api\Admin\CompanyProfileController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\DepartmentController;
use App\Http\Controllers\Api\Admin\DesignationController;
use App\Http\Controllers\Api\Admin\EmployeeManagementController;
use App\Http\Controllers\Api\Admin\HolidayController;
use App\Http\Controllers\Api\Admin\LeaveApprovalController;
use App\Http\Controllers\Api\Admin\LeaveRequestController;
use App\Http\Controllers\Api\Admin\LeaveTypesController;
use App\Http\Controllers\Api\Admin\NoticeBoardController;
use App\Http\Controllers\Api\Admin\PackageController;
use App\Http\Controllers\Api\Admin\PayrollManagementController;
use App\Http\Controllers\Api\Admin\PostController;
use App\Http\Controllers\Api\Admin\ProjectDashboard;
use App\Http\Controllers\Api\Admin\ProjectDashboardController;
use App\Http\Controllers\Api\Admin\ProjectManagementController;
use App\Http\Controllers\Api\Admin\ResignationController;
use App\Http\Controllers\Api\Admin\RouterApiController;
use App\Http\Controllers\Api\Admin\ShiftManagementController;
use App\Http\Controllers\Api\Admin\StripeController;
use App\Http\Controllers\Api\Admin\TadaController;
use App\Http\Controllers\Api\Admin\TaskManagementController;
use App\Http\Controllers\Api\Admin\TeamMeetingController;
use App\Http\Controllers\Api\Admin\TerminationController;
use App\Http\Controllers\Api\Admin\TimeLeaveController;
use App\Http\Controllers\Api\Admin\UserManagementController;
use App\Http\Controllers\Api\AdvanceSalaryApiController;
use App\Http\Controllers\Api\AssetAssignmentApiController;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\AwardApiController;
use App\Http\Controllers\Api\ComplaintApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\EmployeePayrollApiController;
use App\Http\Controllers\Api\EventApiController;
use App\Http\Controllers\Api\HolidayApiController;
use App\Http\Controllers\Api\LeaveApiController;
use App\Http\Controllers\Api\LeaveTypeApiController;
use App\Http\Controllers\Api\NfcApiController;
use App\Http\Controllers\Api\NoticeApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\ProjectApiController;
use App\Http\Controllers\Api\ProjectManagementDashboardApiController;
use App\Http\Controllers\Api\PushNotificationController;
use App\Http\Controllers\Api\ResignationApiController;
use App\Http\Controllers\Api\StaticPageContentApiController;
use App\Http\Controllers\Api\SupportApiController;
use App\Http\Controllers\Api\TadaApiController;
use App\Http\Controllers\Api\TaskApiController;
use App\Http\Controllers\Api\TaskChecklistApiController;
use App\Http\Controllers\Api\TaskCommentApiController;
use App\Http\Controllers\Api\TeamMeetingApiController;
use App\Http\Controllers\Api\TrainingApiController;
use App\Http\Controllers\Api\UserProfileApiController;
use App\Http\Controllers\Api\WarningApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthApiController;
use App\Http\Controllers\Web\AttendanceController;
use App\Http\Controllers\Web\LeaveTypeController;
use App\Http\Resources\CurrenciesResource;
use App\Models\Country;
use App\Models\Currency;
use App\Models\IndustryType;
use App\Models\Setting;
use App\Services\AttendanceMachine\AttendanceMachineService;
use FontLib\Table\Type\post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Sabberworm\CSS\Settings;
use Spatie\Permission\Models\Role;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::group([
    'middleware' => ['api.locale']
], function () {

    /**   user login **/
    Route::post('login', [AuthApiController::class, 'login']);

    Route::post('/admin/login', [AuthController::class, 'login']);
    Route::post('/admin/register', [AuthController::class, 'register']);
    Route::post('/email_verification', [AuthController::class, 'verifyEmailOtp']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/resend-otp', [AuthController::class, 'resendEmailOtp'])->middleware('throttle:3,10');

    Route::group([
        'middleware' => ['auth:api']
    ], function () {

        /**   user logout **/
        Route::get('logout', [AuthApiController::class, 'logout'])->name('user.logout');

        /** Users Routes **/
        Route::get('users/profile', [UserProfileApiController::class, 'userProfileDetail'])->name('users.profile');
        Route::post('users/change-password', [UserProfileApiController::class, 'changePassword'])->name('users.change-password');
        Route::post('users/update-profile', [UserProfileApiController::class, 'updateUserProfile'])->name('users.update-profile');
        Route::get('users/profile-detail/{userId}', [UserProfileApiController::class, 'findEmployeeDetailById']);
        Route::get('users/company/team-sheet', [UserProfileApiController::class, 'getTeamSheetOfCompany'])->name('users.company.team-sheet');

        /** content management Routes **/
        Route::get('static-page-content/{contentType}', [StaticPageContentApiController::class, 'getStaticPageContentByContentType']);
        Route::get('company-rules', [StaticPageContentApiController::class, 'getCompanyRulesDetail']);
        Route::get('static-page-content/{contentType}/{titleSlug}', [StaticPageContentApiController::class, 'getStaticPageContentByContentTypeAndTitleSlug']);

        /** notifications Routes **/
        Route::get('notifications', [NotificationApiController::class, 'getAllRecentPublishedNotification']);

        /** notice Routes **/
        Route::get('notices', [NoticeApiController::class, 'getAllRecentlyReceivedNotice']);

        /** Dashboard Routes **/
        Route::get('dashboard', [DashboardApiController::class, 'userDashboardDetail']);

        /** Attendance Routes **/
        /**
         * @Deprecated Don't use this now
         */
        Route::post('employees/check-in', [AttendanceApiController::class, 'employeeCheckIn']);
        /**
         * @Deprecated Don't use this now
         */
        Route::post('employees/check-out', [AttendanceApiController::class, 'employeeCheckOut']);
        Route::get('employees/attendance-detail', [AttendanceApiController::class, 'getEmployeeAllAttendanceDetailOfTheMonth']);
        Route::post('employees/attendance', [AttendanceApiController::class, 'employeeAttendance']);

        /** Leave Request Routes **/
        Route::get('leave-types', [LeaveTypeApiController::class, 'getAllLeaveTypeWithEmployeeLeaveRecord']);
        Route::post('leave-requests/store', [LeaveApiController::class, 'saveLeaveRequestDetail']);
        Route::get('leave-requests/employee-leave-requests', [LeaveApiController::class, 'getAllLeaveRequestOfEmployee']);
        Route::get('leave-requests/employee-leave-calendar', [LeaveApiController::class, 'getLeaveCountDetailOfEmployeeOfTwoMonth']);
        /**
         * @Deprecated Don't use this now
         */
        Route::get('leave-requests/employee-leave-list', [LeaveApiController::class, 'getAllEmployeeLeaveDetailBySpecificDay']);

        Route::get('employee/office-calendar', [LeaveApiController::class, 'getCalendarDetailBySpecificDay']);
        Route::get('leave-requests/cancel/{leaveRequestId}', [LeaveApiController::class, 'cancelLeaveRequest']);
        /** Time Leave Route */
        Route::post('time-leave-requests/store', [LeaveApiController::class, 'saveTimeLeaveRequest']);
        Route::get('time-leave-requests/cancel/{timeLeaveRequestId}', [LeaveApiController::class, 'cancelTimeLeaveRequest']);


        /** Team Meeting Routes **/
        Route::get('team-meetings', [TeamMeetingApiController::class, 'getAllAssignedTeamMeetingDetail']);
        Route::get('team-meetings/{id}', [TeamMeetingApiController::class, 'findTeamMeetingDetail']);

        /** Holiday route */
        Route::get('holidays', [HolidayApiController::class, 'getAllActiveHoliday']);

        /** Project Management Dashboard route */
        Route::get('project-management-dashboard', [ProjectManagementDashboardApiController::class, 'getUserProjectManagementDashboardDetail']);

        /** Project route */
        Route::get('assigned-projects-list', [ProjectApiController::class, 'getUserAssignedAllProjects']);
        Route::get('assigned-projects-detail/{projectId}', [ProjectApiController::class, 'getProjectDetailById']);

        /** Tasks route */
        Route::get('assigned-task-list', [TaskApiController::class, 'getUserAssignedAllTasks']);
        Route::get('assigned-task-detail/{taskId}', [TaskApiController::class, 'getTaskDetailById']);
        Route::get('assigned-task-detail/change-status/{taskId}/{status}', [TaskApiController::class, 'changeTaskStatus']);
        Route::get('assigned-task-comments', [TaskApiController::class, 'getTaskComments']);

        /** Task checklist route */
        Route::get('assigned-task-checklist/toggle-status/{checklistId}', [TaskChecklistApiController::class, 'toggleCheckListIsCompletedStatus']);

        /** Task Comment route */
        Route::post('assigned-task/comments/store', [TaskCommentApiController::class, 'saveComment']);
        Route::get('assigned-task/comment/delete/{commentId}', [TaskCommentApiController::class, 'deleteComment']);
        Route::get('assigned-task/reply/delete/{replyId}', [TaskCommentApiController::class, 'deleteReply']);

        /** Support route */
        Route::post('support/query-store', [SupportApiController::class, 'store']);
        Route::get('support/department-lists', [SupportApiController::class, 'getAuthUserBranchDepartmentLists']);
        Route::get('support/get-user-query-lists', [SupportApiController::class, 'getAllAuthUserSupportQueryList']);

        /** Tada route */
        Route::get('employee/tada-lists', [TadaApiController::class, 'getEmployeesTadaLists']);
        Route::get('employee/tada-details/{tadaId}', [TadaApiController::class, 'getEmployeesTadaDetail']);
        Route::post('employee/tada/store', [TadaApiController::class, 'storeTadaDetail']);
        Route::post('employee/tada/update', [TadaApiController::class, 'updateTadaDetail']);
        Route::get('employee/tada/delete-attachment/{attachmentId}', [TadaApiController::class, 'deleteTadaAttachment']);

        /** Advance Salary */
        Route::get('employee/advance-salaries-lists', [AdvanceSalaryApiController::class, 'getEmployeesAdvanceSalaryDetailLists']);
        Route::post('employee/advance-salaries/store', [AdvanceSalaryApiController::class, 'store']);
        Route::get('employee/advance-salaries-detail/{id}', [AdvanceSalaryApiController::class, 'getEmployeeAdvanceSalaryDetailById']);
        Route::post('employee/advance-salaries-detail/update', [AdvanceSalaryApiController::class, 'updateDetail']);

        /** Nfc  */
        Route::post('nfc/store', [NfcApiController::class, 'save']);

        /** Push Notification */
        Route::post('employee/push', [PushNotificationController::class, 'sendPushNotification']);

        /** Payslip */
        Route::post('employee/payslip', [EmployeePayrollApiController::class, 'getPayrollList']);
        Route::get('employee/payslip/{id}', [EmployeePayrollApiController::class, 'getEmployeePayslipDetailById']);

        /** Award */
        Route::get('awards', [AwardApiController::class, 'getEmployeeAwards']);

        /** Event Routes **/
        Route::get('events', [EventApiController::class, 'getAllAssignedEvents']);
        Route::get('event/{id}', [EventApiController::class, 'findEventDetail']);

        /** Training Routes **/
        Route::get('training', [TrainingApiController::class, 'getAllTrainings']);
        Route::get('training/{id}', [TrainingApiController::class, 'findTrainingDetail']);

        /** Resignation */
        Route::post('resignation/store', [ResignationApiController::class, 'saveResignationDetail']);
        Route::get('resignation', [ResignationApiController::class, 'resignationDetail']);

        /** Warning */
        Route::post('warning/store/{warning_id}', [WarningApiController::class, 'saveWarningResponse']);
        Route::get('warning', [WarningApiController::class, 'getAllWarnings']);

        /** Complaint */
        Route::post('complaint/store/', [ComplaintApiController::class, 'saveComplaint']);
        Route::post('complaint/response/store/{complaint_id}', [ComplaintApiController::class, 'saveComplaintResponse']);
        Route::get('complaint', [ComplaintApiController::class, 'getAllComplaints']);
        Route::get('department-employees', [ComplaintApiController::class, 'getDepartmentEmployees']);

        /** Asset */
        Route::get('assets', [AssetAssignmentApiController::class, 'index']);
        Route::post('asset-return/{id}', [AssetAssignmentApiController::class, 'store']);
    });

    // });

    // Toggle Admin Registration Form
    Route::get('/toggle_registration_form', function () {
        //Setting::where('id', 1)->update(['show_registration_form' => $request->show_form]);
        $setting = Setting::first();

        return response()->json([
            'message' => __('success'),
            'data'    => $setting
        ], 200);
    });

    Route::get('/fetch_countries', function () {
        try {
            $conturies = Country::select('id', 'name', 'code')->get();

            return response()->json([
                'message' => __('success'),
                'data'    => $conturies
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    });

    Route::get('/fetch_currencies', function () {
        try {
            $currencies = Currency::all();

            return response()->json([
                'message' => __('success'),
                'data'    => CurrenciesResource::collection($currencies)
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    });

    Route::group([
        'middleware' => ['auth:admin-api'],
        'prefix' => 'admin'
    ], function () {

        //Company Management
        Route::get('/company_profile', [CompanyProfileController::class, 'companyProfile']);
        Route::post('/company_profile', [CompanyProfileController::class, 'updateCompanyProfile']);

        Route::get('/industry_type', function () {
            $industryType = IndustryType::all();

            return response()->json([
                'message' => __('success'),
                'data' => $industryType
            ], 200);
        });

        Route::get('/fetch_branches', [BranchController::class, 'fetchBranches']);
        Route::post('/store/branch', [BranchController::class, 'storeBranch']);
        Route::get('/delete_branch/{id}', [BranchController::class, 'deleteBranch']);

        Route::get('/fetch_departments', [DepartmentController::class, 'fetchDepartments']);
        Route::post('/store/department', [DepartmentController::class, 'storeDepartment']);
        Route::get('/delete_department/{id}', [DepartmentController::class, 'deleteDepartment']);

        Route::get('/fetch_designations', [DesignationController::class, 'fetchDesignations']);
        Route::post('/store/designation', [DesignationController::class, 'storeDesignation']);
        Route::get('/delete_designation/{id}', [DesignationController::class, 'deleteDesignation']);

        // User Management
        Route::get('/fetch_admin_users', [UserManagementController::class, 'fetchUsers']);
        Route::post('/store/admin_user', [UserManagementController::class, 'storeAdminUser']);
        Route::get('/delete_user/{id}', [UserManagementController::class, 'deleteUser']);
        Route::get('/admin_roles', [UserManagementController::class, 'roles']);
        Route::post('/update_language', [UserManagementController::class, 'updateLanguage']);

        //Employee Management
        Route::get('/fetch_employees', [EmployeeManagementController::class, 'fetchEmployees']);
        Route::get('/employee_detail/{id}', [EmployeeManagementController::class, 'employeeDetail']);
        Route::get('/employee_code', [EmployeeManagementController::class, 'employeeCode']);
        Route::post('/store/personal_detail', [EmployeeManagementController::class, 'personalDetail']);
        Route::post('/employe_detail_step2', [EmployeeManagementController::class, 'employeeDetailStep2']);
        Route::get('/delete_employee/{id}', [EmployeeManagementController::class, 'deleteEmployee']);
        Route::post('/update/status', [EmployeeManagementController::class, 'updateStatus']);
        Route::get('/filtered_employee/fetch', [EmployeeManagementController::class, 'filteredEmployeesList']);
        Route::post('/employee/update_language', [EmployeeManagementController::class, 'updateLanguage']);

        // Employee Roles
        Route::get('/roles', function () {

            $user = auth()->guard('admin-api')->user();

            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $roles = Role::where('guard_name', 'web')
                ->select('id', 'name')
                ->get();

            if ($roles->isEmpty()) {
                return response()->json(['message' => __('record_not_found')], 404);
            }

            return response()->json([
                'message' =>  __('success'),
                'data'    => $roles
            ], 200);
        });

        // Shift Management
        Route::get('/fetch_office_times', [ShiftManagementController::class, 'index']);
        Route::post('/store/office_time', [ShiftManagementController::class, 'store']);
        Route::get('/delete_office_time/{id}', [ShiftManagementController::class, 'delete']);

        // Leave Types
        Route::get('/leave_types/fetch', [LeaveTypesController::class, 'index']);
        Route::post('/leave_type/store', [LeaveTypesController::class, 'store']);
        Route::get('/leave_type/delete/{id}', [LeaveTypesController::class, 'delete']);

        // Leave Request
        Route::get('/leave_request/fetch', [LeaveRequestController::class, 'index']);
        Route::get('/leave_request/employees/fetch', [LeaveRequestController::class, 'fetchEmployees']);
        Route::get('/leave_request/leavetypes/fetch', [LeaveRequestController::class, 'leaveTypes']);
        Route::post('/leave_request/store', [LeaveRequestController::class, 'store']);
        Route::get('/leave_request/delete/{id}', [LeaveRequestController::class, 'delete']);

        // Time Leave
        Route::get('/time_leave/fetch', [TimeLeaveController::class, 'index']);
        Route::post('/time_leave/store', [TimeLeaveController::class, 'store']);
        Route::get('/time_leave/delete/{id}', [TimeLeaveController::class, 'delete']);

        // Leave Approval
        Route::get('/leave_approve/fetch', [LeaveApprovalController::class, 'index']);
        Route::post('/leave_approval/store', [LeaveApprovalController::class, 'store']);
        Route::get('/leave_approval/delete/{id}', [LeaveApprovalController::class, 'delete']);
        Route::post('/leave_approval/status/update', [LeaveApprovalController::class, 'statusUpdate']);

        // Attendance Section
        Route::get('/attendance/fetch', [AttendanceSectionController::class, 'index']);
        //Route::get('/attendance/checkIn', [AttendanceSectionController::class, 'checkInEmployee']);
        //Route::get('/attendance/checkOut', [AttendanceSectionController::class, 'checkOutEmployee']);
        Route::get('/attendance_report/fetch', [AttendanceSectionController::class, 'exportAttendance']);
        Route::get('/mark_attendance', [AttendanceSectionController::class, 'checkAttendance']);
        Route::get('/attendance/employee_detail', [AttendanceSectionController::class, 'employeeAttendanceDetail']);

        // Tada Section
        Route::get('/tada/fetch', [TadaController::class, 'index']);
        Route::post('/tada/store', [TadaController::class, 'store']);
        Route::get('/tada/delete/{id}', [TadaController::class, 'delete']);
        Route::get('/tada/update_status', [TadaController::class, 'updateStatus']);
        Route::get('/tada/is_paid', [TadaController::class, 'isPaid']);

        // Dashboard
        Route::get('/dashboard/fetch', [DashboardController::class, 'index']);

        // Holiday
        Route::get('/holiday/fetch', [HolidayController::class, 'index']);
        Route::post('/holiday/store', [HolidayController::class, 'store']);
        Route::get('/holiday/delete/{id}', [HolidayController::class, 'delete']);

        // Router
        Route::get('/router/fetch', [RouterApiController::class, 'index']);
        Route::post('/router/store', [RouterApiController::class, 'store']);
        Route::get('/router/delete/{id}', [RouterApiController::class, 'delete']);

        // App Setting
        Route::get('/app_settings/fetch', [AppSettingsApiController::class, 'index']);
        Route::get('/app_settings/change_status', [AppSettingsApiController::class, 'updateStatus']);

        // Delete Admin
        Route::get('/delete_admin', function (Request $request) {
            $user = auth()->guard('admin-api')->user();

            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $user->delete();

            $request->user()->token()->revoke();

            return response()->json([
                'message' => __('account_deleted')
            ], 200);
        });

        // Packages
        Route::get('/packages/fetch', [PackageController::class, 'index']);

        // Subscription
        Route::post('/stripe/create-ephemeral-key', [StripeController::class, 'createEphemeralKey']);
        Route::post('/stripe/create-subscription', [StripeController::class, 'createSubscription']);
        Route::post('/stripe/payment-success', [StripeController::class, 'paymentSuccess']);
        Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook']);
        Route::post('/stripe/increase_employees', [StripeController::class, 'updateEmployeeCount']);

        // Payroll Management
        Route::get('/payroll/setup/fetch', [PayrollManagementController::class, 'index']);
        Route::post('/payroll/setup/store', [PayrollManagementController::class, 'store']);
        Route::post('/payroll/generate', [PayrollManagementController::class, 'generate']);
        Route::get('/payroll/generate/fetch', [PayrollManagementController::class, 'fetchPayrolls']);
        Route::get('/payroll/pay_now', [PayrollManagementController::class, 'payNow']);
        Route::get('/payroll/invoice', [PayrollManagementController::class, 'generateInvoice']);

        // Project Management
        Route::get('/project_management/fetch', [ProjectManagementController::class, 'index']);
        Route::post('/project_management/store', [ProjectManagementController::class, 'store']);
        Route::get('/project_management/detail/{id}', [ProjectManagementController::class, 'detail']);
        Route::get('/project_management/delete/{id}', [ProjectManagementController::class, 'delete']);
        Route::get('/project_management/filtered_employees', [ProjectManagementController::class, 'fetchMembers']);
        Route::get('/project_management/status/update', [ProjectManagementController::class, 'updateStatus']);
        Route::get('/project_management/projects', [ProjectManagementController::class, 'projects']);

        // Task Management
        Route::get('/task_management/fetch', [TaskManagementController::class, 'fetch']);
        Route::get('/task_management/assigned_members/{id}', [TaskManagementController::class, 'projectAssignedMembers']);
        Route::post('/task_management/store', [TaskManagementController::class, 'store']);
        Route::get('/task_management/status/update', [TaskManagementController::class, 'updateStatus']);
        Route::get('/task_management/delete/{id}', [TaskManagementController::class, 'delete']);
        Route::get('/task_management/project_wise_analysis', [TaskManagementController::class, 'projectWiseAnalysis']);
        Route::get('/task_management/analysis_report/{id}', [TaskManagementController::class, 'analysisReport']);

        // Project Dashboard
        Route::get('/project_dashboard/fetch', [ProjectDashboardController::class, 'index']);
        Route::get('/project_management/overall_report', [ProjectDashboardController::class, 'overallReport']);

        // HR Management Termination
        Route::get('/hr_management/termination/fetch', [TerminationController::class, 'index']);
        Route::post('/hr_management/termination/store', [TerminationController::class, 'store']);
        Route::get('/hr_management/delete/{id}', [TerminationController::class, 'delete']);

        // HR Management Notice Board
        Route::get('/notice_board/fetch', [NoticeBoardController::class, 'index']);
        Route::post('/notice_board/store', [NoticeBoardController::class, 'store']);
        Route::get('/notice_board/delete/{id}', [NoticeBoardController::class, 'delete']);

        // HR Management Resignation
        Route::get('/resignation/fetch', [ResignationController::class, 'index']);
        Route::post('/resignation/store', [ResignationController::class, 'store']);
        Route::get('/resignation/delete/{id}', [ResignationController::class, 'delete']);

        // HR Management Team Meeting
        Route::get('/team_meeting/fetch', [TeamMeetingController::class, 'index']);
        Route::post('/team_meeting/store', [TeamMeetingController::class, 'store']);
        Route::get('/team_meeting/delete/{id}', [TeamMeetingController::class, 'delete']);

        // Asset Management Type
        Route::get('/asset_type/fetch', [AssetTypeController::class, 'index']);
        Route::post('/asset_type/store', [AssetTypeController::class, 'store']);
        Route::get('/asset_type/delete/{id}', [AssetTypeController::class, 'delete']);

        // Asset
        Route::get('/asset/fetch', [AssetController::class, 'index']);
        Route::post('/asset/store', [AssetController::class, 'store']);
        Route::get('/asset/delete/{id}', [AssetController::class, 'delete']);
        Route::post('/asset/assign_return_asset', [AssetController::class, 'assignReturnAsset']);

        // Attendance Machine
        Route::get('/attendance_machine/fetch_record', [AttendanceMachineController::class, 'fetch']);
        Route::post('/attendance_machine/assign_machine', [AttendanceMachineController::class, 'assignMachine']);
        Route::post('/attendance_machine/sync_employee', [AttendanceMachineController::class, 'syncEmployees']);
        Route::get('/attendance_machine/listing', [AttendanceMachineController::class, 'listing']);
        Route::get('/attendance_machine/toggle', [AttendanceMachineController::class, 'machineToggle']);
        Route::get('/attendance_machine/status', [AttendanceMachineController::class, 'checkStatus']);
    });
});
