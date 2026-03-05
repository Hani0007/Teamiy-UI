<?php

namespace App\Helpers;

class RolePermissionHelper
{
    public static function permissionModuleTypeArray(): array
    {
        return [

            [ //1
                "name" => "Web",
                "slug" => "web",
            ],

            [ //2
                "name" => "API",
                "slug" => "api",
            ],

        ];
    }

    public static function permissionModuleArray(): array
    {
        return [

            [ //1
                "name" => "Company",
                'group_type_id' => 1
            ],
            [ //2
                "name" => "Branch",
                'group_type_id' => 1
            ],
            [ //3
                "name" => "Department",
                'group_type_id' => 1
            ],
            [ //4
                "name" => "Post",
                'group_type_id' => 1
            ],
            [ //5
                "name" => "Employee",
                'group_type_id' => 1
            ],
            [ //6
                "name" => "Setting",
                'group_type_id' => 1
            ],
            [ //7
                "name" => "Attendance",
                'group_type_id' => 1
            ],
            [ //8
                "name" => "Leave",
                'group_type_id' => 1
            ],
            [ //9
                "name" => "Holiday",
                'group_type_id' => 1
            ],
            [ //10
                "name" => "Notice",
                'group_type_id' => 1
            ],
            [ //11
                "name" => "Team Meeting",
                'group_type_id' => 1
            ],
            [ //12
                "name" => "Content Management",
                'group_type_id' => 1
            ],
            [ //13
                "name" => "Shift Management",
                'group_type_id' => 1
            ],

            [ //14
                "name" => "Support",
                'group_type_id' => 1
            ],
            [ //15
                "name" => "Tada",
                'group_type_id' => 1
            ],
            [ //16
                "name" => "Client",
                'group_type_id' => 1
            ],
            [ //17
                "name" => "Project Management",
                'group_type_id' => 1
            ],
            [ //18
                "name" => "Task Management",
                'group_type_id' => 1
            ],
            [ //19
                'name' => 'Employee API',
                'group_type_id' => 2
            ],
            [ //20
                'name' => 'Attendance API',
                'group_type_id' => 2
            ],
            [ //21
                'name' => 'Leave API',
                'group_type_id' => 2
            ],
            [ //22
                'name' => 'Support API',
                'group_type_id' => 2
            ],
            [ //23
                'name' => 'Tada API',
                'group_type_id' => 2
            ],
            [ //24
                'name' => 'Task Management API',
                'group_type_id' => 2
            ],
            [ //25
                "name" => "Dashboard",
                'group_type_id' => 1
            ],

            [ //26
                "name" => "Asset Management",
                'group_type_id' => 1
            ],

            [ //27
                "name" => "Mobile Notification",
                'group_type_id' => 1
            ],
            [ //28
                "name" => "Attendance Method",
                'group_type_id' => 1
            ],
            [ //29
                "name" => "Attendance Method API",
                'group_type_id' => 2
            ],
            [ //30
                "name" => "Payroll Management",
                'group_type_id' => 1
            ],
            [ //31
                "name" => "Payroll Setting",
                'group_type_id' => 1
            ],
            [ //32
                "name" => "Advance Salary",
                'group_type_id' => 1
            ],
            [ //33
                "name" => "Employee Salary",
                'group_type_id' => 1
            ],
            [ //34
                "name" => "Payroll Management API",
                'group_type_id' => 2
            ],
            [ //35
                "name" => "Advance Salary API",
                'group_type_id' => 2
            ],


            [ //36
                "name" => "Time Leave",
                'group_type_id' => 1
            ],
            [ //37
                "name" => "Award Management",
                'group_type_id' => 1
            ],
            [ //38
                "name" => "Tax Report",
                'group_type_id' => 1
            ],
            [ //39
                "name" => "Event Management",
                'group_type_id' => 1
            ],

            [ //40
                "name" => "Training Management",
                'group_type_id' => 1
            ],
            [ //41
                "name" => "Leave Approval",
                'group_type_id' => 1
            ],
            [ //42
                "name" => "Resignation Management",
                'group_type_id' => 1
            ],
            [ //43
                "name" => "Termination Management",
                'group_type_id' => 1
            ],
            [ //44
                "name" => "Resignation Api",
                'group_type_id' => 2
            ],
            [ //45
                "name" => "Warning",
                'group_type_id' => 1
            ],
            [ //46
                "name" => "Warning Api",
                'group_type_id' => 2
            ],

            [ //47
                "name" => "Complaint",
                'group_type_id' => 1
            ],
            [ //48
                "name" => "Complaint Api",
                'group_type_id' => 2
            ],
            [ //49
                "name" => "Promotion",
                'group_type_id' => 1
            ],
            [ //50
                "name" => "Transfer",
                'group_type_id' => 1
            ],

        ];
    }

    public static function permissionArray(): array
    {
        return [

            // Employee Permissions
            ['name' => 'view_company', 'group' => 'Company', 'guard_name' => 'web'],
            ['name' => 'create_company', 'group' => 'Company', 'guard_name' => 'web'],
            ['name' => 'edit_company', 'group' => 'Company', 'guard_name' => 'web'],
            ['name' => 'list_branch', 'group' => 'Branch', 'guard_name' => 'web'],
            ['name' => 'create_branch', 'group' => 'Branch', 'guard_name' => 'web'],
            ['name' => 'edit_branch', 'group' => 'Branch', 'guard_name' => 'web'],
            ['name' => 'delete_branch', 'group' => 'Branch', 'guard_name' => 'web'],
            ['name' => 'list_department', 'group' => 'Department', 'guard_name' => 'web'],
            ['name' => 'create_department', 'group' => 'Department', 'guard_name' => 'web'],
            ['name' => 'edit_department', 'group' => 'Department', 'guard_name' => 'web'],
            ['name' => 'delete_department', 'group' => 'Department', 'guard_name' => 'web'],
            ['name' => 'list_post', 'group' => 'Post', 'guard_name' => 'web'],
            ['name' => 'create_post', 'group' => 'Post', 'guard_name' => 'web'],
            ['name' => 'edit_post', 'group' => 'Post', 'guard_name' => 'web'],
            ['name' => 'delete_post', 'group' => 'Post', 'guard_name' => 'web'],
            ['name' => 'list_employee', 'group' => 'Employee', 'guard_name' => 'web'],
            ['name' => 'create_employee', 'group' => 'Employee', 'guard_name' => 'web'],
            ['name' => 'show_detail_employee', 'group' => 'Employee', 'guard_name' => 'web'],
            ['name' => 'edit_employee', 'group' => 'Employee', 'guard_name' => 'web'],
            ['name' => 'delete_employee', 'group' => 'Employee', 'guard_name' => 'web'],
            ['name' => 'general_setting', 'group' => 'Setting', 'guard_name' => 'web'],
            ['name' => 'app_setting', 'group' => 'Setting', 'guard_name' => 'web'],
            ['name' => 'role_and_permission', 'group' => 'Setting', 'guard_name' => 'web'],
            ['name' => 'notification', 'group' => 'Setting', 'guard_name' => 'web'],
            ['name' => 'feature_control', 'group' => 'Setting', 'guard_name' => 'web'],
            ['name' => 'fiscal_year', 'group' => 'Setting', 'guard_name' => 'web'],
            ['name' => 'payment_currency', 'group' => 'Setting', 'guard_name' => 'web'],
            ['name' => 'app_qr', 'group' => 'Setting', 'guard_name' => 'web'],
            ['name' => 'theme_color_setting', 'group' => 'Setting', 'guard_name' => 'web'],
            ['name' => 'list_attendance', 'group' => 'Attendance', 'guard_name' => 'web'],
            ['name' => 'attendance_csv_xport', 'group' => 'Attendance', 'guard_name' => 'web'],
            ['name' => 'attendance_create', 'group' => 'Attendance', 'guard_name' => 'web'],
            ['name' => 'attendance_update', 'group' => 'Attendance', 'guard_name' => 'web'],
            ['name' => 'attendance_show', 'group' => 'Attendance', 'guard_name' => 'web'],
            ['name' => 'attendance_delete', 'group' => 'Attendance', 'guard_name' => 'web'],
            ['name' => 'list_leave_type', 'group' => 'Leave', 'guard_name' => 'web'],
            ['name' => 'leave_type_create', 'group' => 'Leave', 'guard_name' => 'web'],
            ['name' => 'leave_type_dit', 'group' => 'Leave', 'guard_name' => 'web'],
            ['name' => 'leave_type_delete', 'group' => 'Leave', 'guard_name' => 'web'],
            ['name' => 'list_leave_requests', 'group' => 'Leave', 'guard_name' => 'web'],
            ['name' => 'show_leave_request_detail', 'group' => 'Leave', 'guard_name' => 'web'],
            ['name' => 'update_leave_request', 'group' => 'Leave', 'guard_name' => 'web'],
            ['name' => 'list_holiday', 'group' => 'Holiday', 'guard_name' => 'web'],
            ['name' => 'holiday_create', 'group' => 'Holiday', 'guard_name' => 'web'],
            ['name' => 'show_detail', 'group' => 'Holiday', 'guard_name' => 'web'],
            ['name' => 'holiday_edit', 'group' => 'Holiday', 'guard_name' => 'web'],
            ['name' => 'holiday_delete', 'group' => 'Holiday', 'guard_name' => 'web'],
            ['name' => 'csv_import_holiday', 'group' => 'Holiday', 'guard_name' => 'web'],
            ['name' => 'list_notice', 'group' => 'Notice', 'guard_name' => 'web'],
            ['name' => 'notice_create', 'group' => 'Notice', 'guard_name' => 'web'],
            ['name' => 'show_notice_detail', 'group' => 'Notice', 'guard_name' => 'web'],
            ['name' => 'notice_edit', 'group' => 'Notice', 'guard_name' => 'web'],
            ['name' => 'notice_delete', 'group' => 'Notice', 'guard_name' => 'web'],
            ['name' => 'send_notice', 'group' => 'Notice', 'guard_name' => 'web'],
            ['name' => 'list_team_meeting', 'group' => 'Team Meeting', 'guard_name' => 'web'],
            ['name' => 'team_meeting_create', 'group' => 'Team Meeting', 'guard_name' => 'web'],
            ['name' => 'show_team_meeting_detail', 'group' => 'Team Meeting', 'guard_name' => 'web'],
            ['name' => 'team_teeting_edit', 'group' => 'Team Meeting', 'guard_name' => 'web'],
            ['name' => 'team_meeting_delete', 'group' => 'Team Meeting', 'guard_name' => 'web'],
            ['name' => 'list_content', 'group' => 'Content Management', 'guard_name' => 'web'],
            ['name' => 'content_create', 'group' => 'Content Management', 'guard_name' => 'web'],
            ['name' => 'show_content_detail', 'group' => 'Content Management', 'guard_name' => 'web'],
            ['name' => 'content_edit', 'group' => 'Content Management', 'guard_name' => 'web'],
            ['name' => 'content_delete', 'group' => 'Content Management', 'guard_name' => 'web'],
            ['name' => 'list_office_time', 'group' => 'Shift Management', 'guard_name' => 'web'],
            ['name' => 'office_time_create', 'group' => 'Shift Management', 'guard_name' => 'web'],
            ['name' => 'show_office_time_detail', 'group' => 'Shift Management', 'guard_name' => 'web'],
            ['name' => 'office_time_edit', 'group' => 'Shift Management', 'guard_name' => 'web'],
            ['name' => 'office_time_delete', 'group' => 'Shift Management', 'guard_name' => 'web'],
            ['name' => 'view_query_list', 'group' => 'Support', 'guard_name' => 'web'],
            ['name' => 'show_query_detail', 'group' => 'Support', 'guard_name' => 'web'],
            ['name' => 'update_status', 'group' => 'Support', 'guard_name' => 'web'],
            ['name' => 'delete_query', 'group' => 'Support', 'guard_name' => 'web'],
            ['name' => 'view_tada_list', 'group' => 'Tada', 'guard_name' => 'web'],
            ['name' => 'create_tada ', 'group' => 'Tada', 'guard_name' => 'web'],
            ['name' => 'show_tada_detail', 'group' => 'Tada', 'guard_name' => 'web'],
            ['name' => 'edit_tada', 'group' => 'Tada', 'guard_name' => 'web'],
            ['name' => 'delete_tada', 'group' => 'Tada', 'guard_name' => 'web'],
            ['name' => 'upload_attachment ', 'group' => 'Tada', 'guard_name' => 'web'],
            ['name' => 'delete_attachment ', 'group' => 'Tada', 'guard_name' => 'web'],
            ['name' => 'view_client_list', 'group' => 'Client', 'guard_name' => 'web'],
            ['name' => 'create_client ', 'group' => 'Client', 'guard_name' => 'web'],
            ['name' => 'show_client_detail', 'group' => 'Client', 'guard_name' => 'web'],
            ['name' => 'edit_client', 'group' => 'Client', 'guard_name' => 'web'],
            ['name' => 'delete_client', 'group' => 'Client', 'guard_name' => 'web'],
            ['name' => 'view_dashboard', 'group' => 'Dashboard', 'guard_name' => 'web'],
            ['name' => 'view_user_management', 'group' => 'User', 'guard_name' => 'web'],
            ['name' => 'view_employee_management', 'group' => 'Employee', 'guard_name' => 'web'],
            ['name' => 'view_project_management', 'group' => 'Project', 'guard_name' => 'web'],
            ['name' => 'view_notice', 'group' => 'Notice', 'guard_name' => 'web'],
            ['name' => 'view_payroll_management', 'group' => 'Payroll', 'guard_name' => 'web'],
            ['name' => 'view_event', 'group' => 'Event', 'guard_name' => 'web'],
            ['name' => 'view_shift_management', 'group' => 'Shift Management', 'guard_name' => 'web'],
            ['name' => 'view_training_management', 'group' => 'Training Management', 'guard_name' => 'web'],
            ['name' => 'view_hr_admin_setup', 'group' => 'HR Setup', 'guard_name' => 'web'],
            ['name' => 'view_asset_management', 'group' => 'Asset Management', 'guard_name' => 'web'],
            ['name' => 'view_content_management', 'group' => 'Content Management', 'guard_name' => 'web'],
            ['name' => 'view_support', 'group' => 'Support', 'guard_name' => 'web'],
            ['name' => 'view_attendance', 'group' => 'Attendance', 'guard_name' => 'web'],
            ['name' => 'view_settings', 'group' => 'Setting', 'guard_name' => 'web'],
            ['name' => 'view_leave', 'group' => 'Leave', 'guard_name' => 'web'],
            ['name' => 'view_team_meeting', 'group' => 'Team Meeting', 'guard_name' => 'web'],

            // Admin Permissions
            ['name' => 'view_company', 'group' => 'Company', 'guard_name' => 'admin'],
            ['name' => 'create_company', 'group' => 'Company', 'guard_name' => 'admin'],
            ['name' => 'edit_company', 'group' => 'Company', 'guard_name' => 'admin'],
            ['name' => 'list_branch', 'group' => 'Branch', 'guard_name' => 'admin'],
            ['name' => 'create_branch', 'group' => 'Branch', 'guard_name' => 'admin'],
            ['name' => 'edit_branch', 'group' => 'Branch', 'guard_name' => 'admin'],
            ['name' => 'delete_branch', 'group' => 'Branch', 'guard_name' => 'admin'],
            ['name' => 'list_department', 'group' => 'Department', 'guard_name' => 'admin'],
            ['name' => 'create_department', 'group' => 'Department', 'guard_name' => 'admin'],
            ['name' => 'edit_department', 'group' => 'Department', 'guard_name' => 'admin'],
            ['name' => 'delete_department', 'group' => 'Department', 'guard_name' => 'admin'],
            ['name' => 'list_post', 'group' => 'Post', 'guard_name' => 'admin'],
            ['name' => 'create_post', 'group' => 'Post', 'guard_name' => 'admin'],
            ['name' => 'edit_post', 'group' => 'Post', 'guard_name' => 'admin'],
            ['name' => 'delete_post', 'group' => 'Post', 'guard_name' => 'admin'],
            ['name' => 'list_employee', 'group' => 'Employee', 'guard_name' => 'admin'],
            ['name' => 'create_employee', 'group' => 'Employee', 'guard_name' => 'admin'],
            ['name' => 'show_detail_employee', 'group' => 'Employee', 'guard_name' => 'admin'],
            ['name' => 'edit_employee', 'group' => 'Employee', 'guard_name' => 'admin'],
            ['name' => 'delete_employee', 'group' => 'Employee', 'guard_name' => 'admin'],
            ['name' => 'general_setting', 'group' => 'Setting', 'guard_name' => 'admin'],
            ['name' => 'app_setting', 'group' => 'Setting', 'guard_name' => 'admin'],
            ['name' => 'role_and_permission', 'group' => 'Setting', 'guard_name' => 'admin'],
            ['name' => 'notification', 'group' => 'Setting', 'guard_name' => 'admin'],
            ['name' => 'feature_control', 'group' => 'Setting', 'guard_name' => 'admin'],
            ['name' => 'fiscal_year', 'group' => 'Setting', 'guard_name' => 'admin'],
            ['name' => 'payment_currency', 'group' => 'Setting', 'guard_name' => 'admin'],
            ['name' => 'app_qr', 'group' => 'Setting', 'guard_name' => 'admin'],
            ['name' => 'theme_color_setting', 'group' => 'Setting', 'guard_name' => 'admin'],
            ['name' => 'list_attendance', 'group' => 'Attendance', 'guard_name' => 'admin'],
            ['name' => 'attendance_csv_xport', 'group' => 'Attendance', 'guard_name' => 'admin'],
            ['name' => 'attendance_create', 'group' => 'Attendance', 'guard_name' => 'admin'],
            ['name' => 'attendance_update', 'group' => 'Attendance', 'guard_name' => 'admin'],
            ['name' => 'attendance_show', 'group' => 'Attendance', 'guard_name' => 'admin'],
            ['name' => 'attendance_delete', 'group' => 'Attendance', 'guard_name' => 'admin'],
            ['name' => 'list_leave_type', 'group' => 'Leave', 'guard_name' => 'admin'],
            ['name' => 'leave_type_create', 'group' => 'Leave', 'guard_name' => 'admin'],
            ['name' => 'leave_type_dit', 'group' => 'Leave', 'guard_name' => 'admin'],
            ['name' => 'leave_type_delete', 'group' => 'Leave', 'guard_name' => 'admin'],
            ['name' => 'list_leave_requests', 'group' => 'Leave', 'guard_name' => 'admin'],
            ['name' => 'show_leave_request_detail', 'group' => 'Leave', 'guard_name' => 'admin'],
            ['name' => 'update_leave_request', 'group' => 'Leave', 'guard_name' => 'admin'],
            ['name' => 'list_holiday', 'group' => 'Holiday', 'guard_name' => 'admin'],
            ['name' => 'holiday_create', 'group' => 'Holiday', 'guard_name' => 'admin'],
            ['name' => 'show_detail', 'group' => 'Holiday', 'guard_name' => 'admin'],
            ['name' => 'holiday_edit', 'group' => 'Holiday', 'guard_name' => 'admin'],
            ['name' => 'holiday_delete', 'group' => 'Holiday', 'guard_name' => 'admin'],
            ['name' => 'csv_import_holiday', 'group' => 'Holiday', 'guard_name' => 'admin'],
            ['name' => 'list_notice', 'group' => 'Notice', 'guard_name' => 'admin'],
            ['name' => 'notice_create', 'group' => 'Notice', 'guard_name' => 'admin'],
            ['name' => 'show_notice_detail', 'group' => 'Notice', 'guard_name' => 'admin'],
            ['name' => 'notice_edit', 'group' => 'Notice', 'guard_name' => 'admin'],
            ['name' => 'notice_delete', 'group' => 'Notice', 'guard_name' => 'admin'],
            ['name' => 'send_notice', 'group' => 'Notice', 'guard_name' => 'admin'],
            ['name' => 'list_team_meeting', 'group' => 'Team Meeting', 'guard_name' => 'admin'],
            ['name' => 'team_meeting_create', 'group' => 'Team Meeting', 'guard_name' => 'admin'],
            ['name' => 'show_team_meeting_detail', 'group' => 'Team Meeting', 'guard_name' => 'admin'],
            ['name' => 'team_teeting_edit', 'group' => 'Team Meeting', 'guard_name' => 'admin'],
            ['name' => 'team_meeting_delete', 'group' => 'Team Meeting', 'guard_name' => 'admin'],
            ['name' => 'list_content', 'group' => 'Content Management', 'guard_name' => 'admin'],
            ['name' => 'content_create', 'group' => 'Content Management', 'guard_name' => 'admin'],
            ['name' => 'show_content_detail', 'group' => 'Content Management', 'guard_name' => 'admin'],
            ['name' => 'content_edit', 'group' => 'Content Management', 'guard_name' => 'admin'],
            ['name' => 'content_delete', 'group' => 'Content Management', 'guard_name' => 'admin'],
            ['name' => 'list_office_time', 'group' => 'Shift Management', 'guard_name' => 'admin'],
            ['name' => 'office_time_create', 'group' => 'Shift Management', 'guard_name' => 'admin'],
            ['name' => 'show_office_time_detail', 'group' => 'Shift Management', 'guard_name' => 'admin'],
            ['name' => 'office_time_edit', 'group' => 'Shift Management', 'guard_name' => 'admin'],
            ['name' => 'office_time_delete', 'group' => 'Shift Management', 'guard_name' => 'admin'],
            ['name' => 'view_query_list', 'group' => 'Support', 'guard_name' => 'admin'],
            ['name' => 'show_query_detail', 'group' => 'Support', 'guard_name' => 'admin'],
            ['name' => 'update_status', 'group' => 'Support', 'guard_name' => 'admin'],
            ['name' => 'delete_query', 'group' => 'Support', 'guard_name' => 'admin'],
            ['name' => 'view_tada_list', 'group' => 'Tada', 'guard_name' => 'admin'],
            ['name' => 'create_tada ', 'group' => 'Tada', 'guard_name' => 'admin'],
            ['name' => 'show_tada_detail', 'group' => 'Tada', 'guard_name' => 'admin'],
            ['name' => 'edit_tada', 'group' => 'Tada', 'guard_name' => 'admin'],
            ['name' => 'delete_tada', 'group' => 'Tada', 'guard_name' => 'admin'],
            ['name' => 'upload_attachment ', 'group' => 'Tada', 'guard_name' => 'admin'],
            ['name' => 'delete_attachment ', 'group' => 'Tada', 'guard_name' => 'admin'],
            ['name' => 'view_client_list', 'group' => 'Client', 'guard_name' => 'admin'],
            ['name' => 'create_client ', 'group' => 'Client', 'guard_name' => 'admin'],
            ['name' => 'show_client_detail', 'group' => 'Client', 'guard_name' => 'admin'],
            ['name' => 'edit_client', 'group' => 'Client', 'guard_name' => 'admin'],
            ['name' => 'delete_client', 'group' => 'Client', 'guard_name' => 'admin'],
            ['name' => 'view_dashboard', 'group' => 'Dashboard', 'guard_name' => 'admin'],
            ['name' => 'view_user_management', 'group' => 'User', 'guard_name' => 'admin'],
            ['name' => 'view_employee_management', 'group' => 'Employee', 'guard_name' => 'admin'],
            ['name' => 'view_project_management', 'group' => 'Project', 'guard_name' => 'admin'],
            ['name' => 'view_notice', 'group' => 'Notice', 'guard_name' => 'admin'],
            ['name' => 'view_payroll_management', 'group' => 'Payroll', 'guard_name' => 'admin'],
            ['name' => 'view_event', 'group' => 'Event', 'guard_name' => 'admin'],
            ['name' => 'view_shift_management', 'group' => 'Shift Management', 'guard_name' => 'admin'],
            ['name' => 'view_training_management', 'group' => 'Training Management', 'guard_name' => 'admin'],
            ['name' => 'view_hr_admin_setup', 'group' => 'HR Setup', 'guard_name' => 'admin'],
            ['name' => 'view_asset_management', 'group' => 'Asset Management', 'guard_name' => 'admin'],
            ['name' => 'view_content_management', 'group' => 'Content Management', 'guard_name' => 'admin'],
            ['name' => 'view_support', 'group' => 'Support', 'guard_name' => 'admin'],
            ['name' => 'view_attendance', 'group' => 'Attendance', 'guard_name' => 'admin'],
            ['name' => 'view_settings', 'group' => 'Setting', 'guard_name' => 'admin'],
            ['name' => 'view_leave', 'group' => 'Leave', 'guard_name' => 'admin'],
            ['name' => 'view_team_meeting', 'group' => 'Team Meeting', 'guard_name' => 'admin'],
        ];
    }
}
