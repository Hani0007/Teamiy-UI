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
                    'percentage' => '+12%', 
                    'description' => 'Employee count includes all staff',
                    'icon' => 'fas fa-users'
                ],
                [
                    'label' => 'Branches', 
                    'value' => $employeeStats['total_branches'] ?? 0, 
                    'percentage' => '+05%', 
                    'description' => 'Total branches in company',
                    'icon' => 'fas fa-building'
                ],
                [
                    'label' => 'Today Presents', 
                    'value' => $employeeStats['today_presents'] ?? 0, 
                    'percentage' => '+54%', 
                    'description' => 'Total employees presents today',
                    'icon' => 'fas fa-user-check'
                ],
                [
                    'label' => 'Today Absents', 
                    'value' => $employeeStats['today_absents'] ?? 0, 
                    'percentage' => '+11%', 
                    'description' => 'Total employees absent today',
                    'icon' => 'fas fa-user-times'
                ],
                [
                    'label' => 'Today Lates', 
                    'value' => $employeeStats['today_lates'] ?? 0, 
                    'percentage' => '-04%', 
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
            </div>
        </div>
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
                                    <div class="fw-bold">{{ $project->name ?? 'Website Redesign' }}</div>
                                    <small class="text-muted">{{ $project->client_name ?? 'techverdi.com' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="status-pill {{ $project->status == 'completed' ? 'sp-approved bg-soft-success text-success' : ($project->status == 'in_progress' ? 'sp-pending bg-soft-primary text-primary' : 'sp-pending bg-soft-secondary text-muted') }}">
                                {{ $project->status == 'completed' ? 'Done' : ($project->status == 'in_progress' ? 'In Progress' : 'Not Started') }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $project->name ?? 'Home Page Redesign' }}</div>
                            <small class="text-muted">{{ Str::limit($project->description ?? 'Redesign website homepage in wordpress...', 50) }}...</small>
                        </td>
                        <td>
                            <div class="member-group d-flex">
                                <div class="member-count">+{{ $project->members ?? 2 }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="pg-bar">
                                    <div class="pg-fill {{ $project->status == 'completed' ? 'bg-green' : ($project->status == 'in_progress' ? 'bg-green' : 'bg-gray') }}" style="width: {{ $project->progress ?? 0 }}%"></div>
                                </div>
                                <span class="fw-bold">{{ $project->progress ?? 0 }}%</span>
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
        <div class="text-center py-3 border-top"><a href="#" class="text-muted small fw-bold text-decoration-none">See All Projects</a></div>
    </div>

    <div class="section-card">
        <div class="section-header border-0 pb-0">
            <div class="d-flex align-items-center gap-2">
                <div class="stat-icon-box"><i class="fas fa-crosshairs text-dark"></i></div>
                <h5 class="fw-bold mb-0">Recent Activities</h5>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <select class="form-select form-select-sm border-0 bg-light fw-bold" style="width:125px;"><option>Last 7 Days</option></select>
                <div class="nav nav-pills nav-pills-custom" id="activity-tabs" role="tablist">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-leave">Leave Requests</button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-attendance">Attendance</button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-payroll">Payroll</button>
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
                        @php
                            $leaves = [
                                ['id' => 'FCD-154', 'n' => 'Pietro La Torre', 'd' => 'Inbound Sales', 't' => 'Full Day', 'r' => 'Doctor Appointment...', 'dt' => '02 Feb, 2026', 's' => 'Not Approved'],
                                ['id' => 'FCD-155', 'n' => 'Benjamin', 'd' => 'PHP (Laravel)', 't' => 'Full Day', 'r' => 'Accident Emergency...', 'dt' => '24 Feb, 2026', 's' => 'Pending'],
                                ['id' => 'FCD-156', 'n' => 'Jone Snow', 'd' => 'Flutter (Dart)', 't' => 'Short Leave', 'r' => 'Stuck in Traffic...', 'dt' => '9:30 AM to 12:00 PM', 's' => 'Approved']
                            ];
                        @endphp
                        @foreach($leaves as $lv)
                        <tr>
                            <td>{{ $lv['id'] }}</td>
                            <td><strong>{{ $lv['n'] }}</strong></td>
                            <td>{{ $lv['d'] }}</td>
                            <td>{{ $lv['t'] }}</td>
                            <td>{{ $lv['r'] }}</td>
                            <td>{{ $lv['dt'] }}</td>
                            <td><span class="status-pill {{ $lv['s']=='Approved' ? 'sp-approved' : ($lv['s']=='Pending' ? 'sp-pending' : 'sp-rejected') }}">{{ $lv['s'] }}</span></td>
                            <td><i class="fas fa-ellipsis-v text-muted"></i></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="tab-attendance">
                <table class="table mb-0 mt-3">
                    <thead>
                        <tr><th>Employee name</th><th>Total Worked Hours</th><th>Attendance Status</th><th>Shift</th></tr>
                    </thead>
                    <tbody>
                        @if(isset($recentAttendance) && $recentAttendance->count() > 0)
                            @foreach($recentAttendance as $attendance)
                            <tr>
                                <td><strong>{{ $attendance->employee->name ?? 'N/A' }}</strong></td>
                                <td>{{ number_format($attendance->worked_hour ?? 0, 1) }} hrs</td>
                                <td>
                                    <span class="status-pill 
                                        @if($attendance->attendance_status == 'present') sp-approved bg-soft-success text-success
                                        @elseif($attendance->attendance_status == 'late') sp-pending bg-soft-warning text-warning
                                        @else sp-rejected bg-soft-danger text-danger
                                        @endif">
                                        {{ ucfirst($attendance->attendance_status ?? 'Unknown') }}
                                    </span>
                                </td>
                                <td>{{ $attendance->officeTime->shift ?? 'General Shift' }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    <p class="mb-0">No recent attendance records found.</p>
                                </td>
                            </tr>
                        @endif
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
                            <tr>
                                <td><strong>{{ $meeting->title ?? 'N/A' }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($meeting->meeting_start_time)->format('h:i A') }}</td>
                                <td>{{ $meeting->teamMeetingParticipator->count() }} participators</td>
                                <td>
                                    <span class="status-pill sp-pending bg-soft-secondary text-muted">
                                        {{ \Carbon\Carbon::parse($meeting->meeting_date)->isPast() ? 'Completed' : 'Scheduled' }}
                                    </span>
                                </td>
                                <td><button class="btn btn-sm btn-outline-primary">View Details</button></td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    <p class="mb-0">No recent team meetings found.</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="text-center py-3 border-top"><a href="#" class="text-muted small fw-bold text-decoration-none">See All Activites</a></div>
    </div>
</div>
@endsection