@extends('layouts.master')

@section('title', 'Dashboard')

@section('main-content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h3 class="fw-bold mb-1">Dashboard</h3>
            <p class="text-muted small">Key HR metrics, employee and Project activity insights.</p>
        </div>
        <div class="d-flex align-items-center bg-white border rounded-3 px-3 py-2 shadow-sm">
            <i class="far fa-calendar-alt text-muted me-2"></i>
            <span class="small fw-bold">January 1 - January 7, 2026</span>
            <i class="fas fa-chevron-left ms-3 me-2 small"></i>
            <i class="fas fa-chevron-right small"></i>
        </div>
    </div>

    <div class="row g-3">
        @php
            $topStats = [
                [
                    'label' => 'Total Employees', 
                    'value' => $employeeStats['total_employees'] ?? 0, 
                    'percentage' => ($employeeStats['employees_percentage'] >= 0 ? '+' : '') . number_format($employeeStats['employees_percentage'] ?? 0, 1) . '%', 
                    'description' => 'Employee count includes all staff',
                    'icon' => 'fas fa-users'
                ],
                [
                    'label' => 'Branches', 
                    'value' => $employeeStats['total_branches'] ?? 0, 
                    'percentage' => ($employeeStats['branches_percentage'] >= 0 ? '+' : '') . number_format($employeeStats['branches_percentage'] ?? 0, 1) . '%', 
                    'description' => 'Total branches in company',
                    'icon' => 'fas fa-building'
                ],
                [
                    'label' => 'Today Presents', 
                    'value' => $employeeStats['today_presents'] ?? 0, 
                    'percentage' => ($employeeStats['presents_percentage'] >= 0 ? '+' : '') . number_format($employeeStats['presents_percentage'] ?? 0, 1) . '%', 
                    'description' => 'Total employees presents today',
                    'icon' => 'fas fa-user-check'
                ],
                [
                    'label' => 'Today Absents', 
                    'value' => $employeeStats['today_absents'] ?? 0, 
                    'percentage' => ($employeeStats['absents_percentage'] >= 0 ? '+' : '') . number_format($employeeStats['absents_percentage'] ?? 0, 1) . '%', 
                    'description' => 'Total employees absent today',
                    'icon' => 'fas fa-user-times'
                ],
                [
                    'label' => 'Today Lates', 
                    'value' => $employeeStats['today_lates'] ?? 0, 
                    'percentage' => ($employeeStats['lates_percentage'] >= 0 ? '+' : '') . number_format($employeeStats['lates_percentage'] ?? 0, 1) . '%', 
                    'description' => 'Total employees late today',
                    'icon' => 'fas fa-clock'
                ]
            ];
        @endphp
        @foreach($topStats as $ts)
        <div class="col">
            <div class="stat-card h-100">
                <div class="stat-header">
                    <div class="stat-icon-box"><i class="{{ $ts['icon'] }} text-dark small"></i></div>
                    <button class="btn-details-orange">Details ></button>
                </div>
                <div class="stat-label">{{ $ts['label'] }}</div>
                <span class="stat-subtext">{{ $ts['description'] }}</span>
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-value">{{ $ts['value'] }}</div>
                    <span class="stat-badge {{ str_contains($ts['percentage'], '-') ? 'badge-red' : 'badge-orange' }}">{{ $ts['percentage'] }}</span>
                    <small class="text-muted" style="font-size: 9px;">vs Last Month</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="section-card">
        <div class="section-header">
            <h5 class="fw-bold mb-0">Projects</h5>
            <div class="d-flex gap-3 small fw-bold">
                <span><span class="badge bg-secondary rounded-circle me-1">{{ $projectStats['not_started'] ?? 0 }}</span> Not Started</span>
                <span><span class="badge bg-warning rounded-circle me-1 text-dark">{{ $projectStats['in_progress'] ?? 0 }}</span> In Progress</span>
                <span><span class="badge bg-danger rounded-circle me-1">{{ $projectStats['late'] ?? 0 }}</span> Late</span>
                <span><span class="badge bg-success rounded-circle me-1">{{ $projectStats['completed'] ?? 0 }}</span> Completed</span>
                <span class="ms-3"><strong>Total: {{ $projectStats['total_projects'] ?? 0 }}</strong></span>
            </div>
        </div>
        
        @if(isset($projectStats['projects_by_branch']) && $projectStats['projects_by_branch']->count() > 0)
        <div class="mb-3">
            <h6 class="small text-muted mb-2">Projects by Branch</h6>
            <div class="d-flex flex-wrap gap-2">
                @foreach($projectStats['projects_by_branch'] as $branch)
                <span class="badge bg-light text-dark border">
                    <strong>{{ $branch->branch_name }}:</strong> {{ $branch->project_count }}
                </span>
                @endforeach
            </div>
        </div>
        @endif
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr><th>Name</th><th>Status</th><th>About</th><th>Members</th><th>Progress</th><th></th></tr>
                </thead>
                <tbody>
                    @if($recentProjects->count() > 0)
                    @foreach($recentProjects as $project)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="project-avatar me-3"></div>
                                <div>
                                    <div class="fw-bold">{{ $project->name }}</div>
                                    <small class="text-muted">{{ $project->client->name ?? 'Internal Project' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="status-pill {{ $project->status == 'completed' ? 'sp-approved bg-soft-success text-success' : ($project->status == 'in_progress' ? 'sp-pending bg-soft-primary text-primary' : ($project->status == 'cancelled' ? 'sp-rejected bg-soft-danger text-danger' : 'sp-pending bg-soft-secondary text-muted')) }}">
                                {{ ucfirst(str_replace('_', ' ', $project->status ?? 'not_started')) }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $project->name }}</div>
                            <small class="text-muted">
                                @if($project->start_date)Started: {{ \Carbon\Carbon::parse($project->start_date)->format('M d, Y') }}@endif
                                @if($project->deadline) • Deadline: {{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}@endif
                            </small>
                        </td>
                        <td>
                            <div class="member-group d-flex">
                                <div class="member-count">{{ $project->projectLeaders->count() + $project->assignedMembers->count() }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="pg-bar">
                                    <div class="pg-fill {{ $project->status == 'completed' ? 'bg-green' : ($project->status == 'in_progress' ? 'bg-green' : 'bg-gray') }}" style="width: {{ $project->getProjectProgressInPercentage() ?? 0 }}%"></div>
                                </div>
                                <span class="fw-bold">{{ $project->getProjectProgressInPercentage() ?? 0 }}%</span>
                            </div>
                        </td>
                        <td><i class="fas fa-ellipsis-v text-muted"></i></td>
                    </tr>
                    @endforeach
                    @else
                    @for($i=1; $i<=3; $i++)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="project-avatar me-3"></div>
                                <div><div class="fw-bold">Website Redesign</div><small class="text-muted">techverdi.com</small></div>
                            </div>
                        </td>
                        <td><span class="status-pill {{ $i==1 ? 'sp-approved bg-soft-success text-success' : ($i==2 ? 'sp-pending bg-soft-primary text-primary' : 'sp-pending bg-soft-secondary text-muted') }}">
                            {{ $i==1 ? 'Done' : ($i==2 ? 'In Progress' : 'Not Started') }}</span>
                        </td>
                        <td><div class="fw-bold">Home Page Redesign</div><small class="text-muted">Redesign website homepage in wordpress...</small></td>
                        <td>
                            <div class="member-group d-flex">
                                <div class="member-count">+{{ $i * 2 }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="pg-bar"><div class="pg-fill {{ $i==1 ? 'bg-green' : ($i==2 ? 'bg-green' : 'bg-gray') }}" style="width: {{ $i==1 ? '100%' : ($i==2 ? '69%' : '0%') }}"></div></div>
                                <span class="fw-bold">{{ $i==1 ? '100%' : ($i==2 ? '69%' : '00%') }}</span>
                            </div>
                        </td>
                        <td><i class="fas fa-ellipsis-v text-muted"></i></td>
                    </tr>
                    @endfor
                    @endif
                </tbody>
            </table>
        </div>
        <div class="text-center py-3 border-top"><a href="{{ route('admin.projects.index') }}" class="text-muted small fw-bold text-decoration-none">See All Projects</a></div>
    </div>

    <div class="section-card">
        <div class="section-header border-0 pb-0">
            <div class="d-flex align-items-center gap-2">
                <div class="stat-icon-box"><i class="fas fa-crosshairs text-dark"></i></div>
                <h5 class="fw-bold mb-0">Recent Activities</h5>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <select class="form-select form-select-sm border-0 bg-light fw-bold" style="width:125px;" id="activity-filter">
                    <option value="today" selected>Today</option>
                    <option value="last_2_days">Last 2 Days</option>
                    <option value="last_7_days">Last 7 Days</option>
                </select>
                <div class="nav nav-pills nav-pills-custom" id="activity-tabs" role="tablist">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-leave">Leave Requests</button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-attendance">Attendance</button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-meetings">Team Meetings</button>
                </div>
            </div>
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-leave">
                <table class="table mb-0 mt-3">
                    <thead>
                        <tr><th>ID Employee</th><th>Name</th><th>Department</th><th>Leave Type</th><th>Reason</th><th>Date/Time</th><th>Status</th><th></th></tr>
                    </thead>
                    <tbody>
                        @if(isset($recentLeaveRequests) && $recentLeaveRequests->count() > 0)
                            @foreach($recentLeaveRequests as $leave)
                            <tr data-real-data>
                                <td>{{ $leave->employee->employee_code ?? 'N/A' }}</td>
                                <td><strong>{{ $leave->employee->name ?? 'N/A' }}</strong></td>
                                <td>{{ $leave->department->dept_name ?? 'N/A' }}</td>
                                <td>{{ $leave->leaveType->name ?? 'N/A' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($leave->reason ?? 'No reason provided', 30) }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->leave_requested_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="status-pill 
                                        @if($leave->status == 'approved') sp-approved bg-soft-success text-success
                                        @elseif($leave->status == 'pending') sp-pending bg-soft-warning text-warning
                                        @else sp-rejected bg-soft-danger text-danger
                                        @endif">
                                        {{ ucfirst($leave->status ?? 'Unknown') }}
                                    </span>
                                </td>
                                <td><i class="fas fa-ellipsis-v text-muted"></i></td>
                            </tr>
                            @endforeach
                        @else
                            <tr data-real-data>
                                <td colspan="7" class="text-center text-muted py-3">
                                    <p class="mb-0">No recent leave requests found.</p>
                                </td>
                            </tr>
                        @endif
                        <tr data-no-data style="display: none;">
                            <td colspan="7" class="text-center text-muted py-3">
                                <p class="mb-0">No data found.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="tab-attendance">
                <table class="table mb-0 mt-3">
                    <thead>
                        <tr><th>Employee name</th><th>Check-in</th><th>Check-out</th><th>Attendance Status</th><th>Shift</th></tr>
                    </thead>
                    <tbody>
                        @if(isset($recentAttendance) && $recentAttendance->count() > 0)
                            @foreach($recentAttendance as $attendance)
                            <tr data-real-data>
                                <td><strong>{{ $attendance->employee->name ?? 'N/A' }}</strong></td>
                                <td>{{ $attendance->check_in_at ? \Carbon\Carbon::parse($attendance->check_in_at)->format('h:i A') : 'N/A' }}</td>
                                <td>{{ $attendance->check_out_at ? \Carbon\Carbon::parse($attendance->check_out_at)->format('h:i A') : 'N/A' }}</td>
                                <td>
                                    <span class="status-pill 
                                        @if($attendance->attendance_status == 'present' || $attendance->attendance_status == '1') sp-approved bg-soft-success text-success
                                        @elseif($attendance->attendance_status == 'late') sp-pending bg-soft-warning text-warning
                                        @else sp-rejected bg-soft-danger text-danger
                                        @endif">
                                        {{ $attendance->attendance_status == '1' ? 'Present' : ($attendance->attendance_status == '0' ? 'Absent' : ucfirst($attendance->attendance_status ?? 'Unknown')) }}
                                    </span>
                                </td>
                                <td>{{ $attendance->officeTime->shift ?? 'General Shift' }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr data-real-data>
                                <td colspan="5" class="text-center text-muted py-3">
                                    <p class="mb-0">No recent attendance records found.</p>
                                </td>
                            </tr>
                        @endif
                        <tr data-no-data style="display: none;">
                            <td colspan="5" class="text-center text-muted py-3">
                                <p class="mb-0">No data found.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="tab-meetings">
                <table class="table mb-0 mt-3">
                    <thead>
                        <tr><th>Title</th><th>Meeting Date</th><th>Start Time</th><th>Participators</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @if(isset($recentTeamMeetings) && $recentTeamMeetings->count() > 0)
                            @foreach($recentTeamMeetings as $meeting)
                            <tr data-real-data>
                                <td><strong>{{ $meeting->title ?? 'N/A' }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($meeting->meeting_start_time)->format('h:i A') }}</td>
                                <td>{{ $meeting->teamMeetingParticipator->count() }} participators</td>
                                <td>
                                    <span class="status-pill sp-pending bg-soft-secondary text-muted">
                                        {{ \Carbon\Carbon::parse($meeting->meeting_date)->isPast() ? 'Completed' : 'Scheduled' }}
                                    </span>
                                </td>
                                <td><a href="{{ route('admin.team-meetings.index') }}" class="btn btn-sm btn-outline-primary">View Details</a></td>
                            </tr>
                            @endforeach
                        @else
                            <tr data-real-data>
                                <td colspan="6" class="text-center text-muted py-3">
                                    <p class="mb-0">No recent team meetings found.</p>
                                </td>
                            </tr>
                        @endif
                        <tr data-no-data>
                            <td colspan="6" class="text-center text-muted py-3">
                                <p class="mb-0">No data found.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize filter state
    handleFilterChange('today');
    
    $('#activity-filter').on('change', function() {
        var filter = $(this).val();
        handleFilterChange(filter);
    });
    
    // Handle tab switching to maintain filter state
    $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function() {
        var filter = $('#activity-filter').val();
        handleFilterChange(filter);
    });
    
    function handleFilterChange(filter) {
        console.log('Filter changed to:', filter);
        
        // Only show data for 'today' filter, show empty for others
        if (filter === 'today') {
            // Show the actual data
            showActualData();
        } else {
            // Show "No data found" for other filters
            showNoDataFound();
        }
    }
    
    function showActualData() {
        // Show the real data rows
        $('.tab-pane tbody tr[data-real-data]').show();
        $('.tab-pane tbody tr[data-no-data]').hide();
        console.log('Showing actual data');
    }
    
    function showNoDataFound() {
        // Hide real data and show "No data found" rows
        $('.tab-pane tbody tr[data-real-data]').hide();
        $('.tab-pane tbody tr[data-no-data]').show();
        console.log('Showing no data found');
    }
});
</script>
@endpush