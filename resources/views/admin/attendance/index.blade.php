@extends('layouts.master')
@section('title', __('index.attendance'))
<style>
    a.btn-action-primary:hover {
    background-color: #fb8233;
    color: #ffffff;
}
</style>
@section('main-content')
<div class="content-wrapper">
    @php
        $currentDate = $isBsEnabled ? \App\Helpers\AppHelper::getCurrentDateInBS() : \App\Helpers\AppHelper::getCurrentDateInYmdFormat();
    @endphp
{{-- Breadcrumbs & Top Header --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.attendance') }}</h2>
            @include('admin.attendance.common.breadcrumb')
            </nav>
        </div>
    </div>

    {{-- Filter Panel (Premium Glass Style) --}}
<div class="glass-filter-panel mb-5 shadow-sm border-0"
     style="background: rgba(255,255,255,0.7); backdrop-filter: blur(10px); border-radius:20px; padding:25px;">

<form action="{{ route('admin.attendances.index') }}" method="get" class="row g-3 align-items-end">

    {{-- Attendance Date --}}
    <div class="col-xl-3 col-lg-3 col-md-6">

        <label class="form-label fw-bold text-muted small" style="letter-spacing:0.5px;">
            {{ __('index.date') }}
        </label>

        @if($isBsEnabled)

            <input id="attendance_date"
                   name="attendance_date"
                   value="{{ $filterParameter['attendance_date'] }}"
                   class="form-control dayAttendance shadow-none"
                   type="text"
                   placeholder="{{ __('index.date_placeholder_bs') }}"
                   style="height:48px;border-radius:12px;border:1px solid #e2e8f0;" />

        @else

            <input id="attendance_date"
                   name="attendance_date"
                   value="{{ $filterParameter['attendance_date'] }}"
                   class="form-control shadow-none"
                   type="date"
                   style="height:48px;border-radius:12px;border:1px solid #e2e8f0;" />

        @endif

    </div>

    {{-- Branch --}}
    @if(!isset(auth()->user()->branch_id))

    <div class="col-xl-3 col-lg-3 col-md-6">

        <label class="form-label fw-bold text-muted small" style="letter-spacing:0.5px;">
            {{ __('index.branch') }}
        </label>

        <select class="form-select modern-select shadow-none"
                name="branch_id"
                id="branch_id"
                style="height:48px;border-radius:12px;border:1px solid #e2e8f0;">

            <option value="" {{ !isset($filterParameter['branch_id']) ? 'selected' : '' }}>
                {{ __('index.select_branch') }}
            </option>

            @foreach($branch as $value)

                <option value="{{ $value->id }}"
                    {{ (isset($filterParameter['branch_id']) && $value->id == $filterParameter['branch_id']) ? 'selected' : '' }}>

                    {{ ucfirst($value->name) }}

                </option>

            @endforeach

        </select>

    </div>

    @endif


    {{-- Department --}}
    <div class="col-xl-3 col-lg-3 col-md-6">

        <label class="form-label fw-bold text-muted small" style="letter-spacing:0.5px;">
            {{ __('index.department') }}
        </label>

        <select class="form-select modern-select shadow-none"
                name="department_id"
                id="department_id"
                style="height:48px;border-radius:12px;border:1px solid #e2e8f0;">

            <option selected disabled>
                {{ __('index.select_department') }}
            </option>

        </select>

    </div>


    {{-- Buttons --}}
    <div class="col-xl-3 col-lg-3 col-md-6 d-flex gap-2">

        <button type="submit"
                class="btn-theme-primary w-100 border-0"
                style="background:#057db0;color:#fff;height:48px;border-radius:12px;font-weight:600;transition:all .3s ease;">

            {{ __('index.filter') }}

        </button>


        @can('attendance_csv_export')

        <button type="button"
                id="download-daywise-attendance-excel"
                data-href="{{ route('admin.attendances.index') }}"
                class="btn-theme-secondary w-100 border-0"
                style="background:#057db0;color:#fff;height:48px;border-radius:12px;font-weight:600;">

            {{ __('index.csv_export') }}

        </button>

        @endcan


        <a href="{{ route('admin.attendances.index') }}"
           class="btn-theme-outline w-100 text-decoration-none d-flex align-items-center justify-content-center"
           style="height:48px;border:1px solid #e2e8f0;border-radius:12px;color:#64748b;background:#fff;font-weight:600;">

            {{ __('index.reset') }}

        </a>

    </div>

</form>

</div>

    <div class="attendance-grid">
        @forelse($attendanceDetail->groupBy('user_id') as $userId => $userAttendances)
            @php
                $firstAtt = $userAttendances->first();
                $totalMin = $userAttendances->sum('worked_hour');
                $h = floor($totalMin / 60); $m = $totalMin % 60;
                $isNight = \App\Helpers\AppHelper::isOnNightShift($userId);
                $updateUrl = $firstAtt->attendance_id ? route($isNight ? 'admin.night_attendances.update' : 'admin.attendances.update', $firstAtt->attendance_id) : '#';
                $deleteUrl = $firstAtt->attendance_id ? route('admin.attendance.delete', $firstAtt->attendance_id) : '#';
            @endphp

            <div class="clean-card">
                <span class="status-dot status-{{ $firstAtt->attendance_status }}">
                    {{ $firstAtt->attendance_status == 1 ? 'Approved' : 'Pending' }}
                </span>

                <div class="card-top">
                    <div class="avatar-lite">{{ substr($firstAtt->user_name, 0, 1) }}</div>
                    <div class="user-info">
                        <p class="user-name">{{ ucfirst($firstAtt->user_name) }}</p>
                        <span class="user-id">#{{ $userId }} • {{ strtoupper($firstAtt->shift ?? 'Day') }}</span>
                    </div>
                </div>

                <div class="time-row">
                    <div class="time-item">
                        <small>Check In</small>
                        <span class="time-val text-success">{{ $firstAtt->check_in_at ? \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($appTimeSetting, $firstAtt->check_in_at) : '--:--' }}</span>
                    </div>
                    <div class="time-item text-end">
                        <small>Check Out</small>
                        <span class="time-val text-danger">{{ $firstAtt->check_out_at ? \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($appTimeSetting, $firstAtt->check_out_at) : '--:--' }}</span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="worked-pill">Worked: {{ $h }}h {{ $m }}m</span>
                </div>

                <div class="actions-lite">
                    @if($filterParameter['attendance_date'] == $currentDate)
                        @if(!$firstAtt->check_in_at)
                            <a href="{{ route('admin.employees.check-in', [$firstAtt->company_id, $userId]) }}" class="btn-action-primary" >Check In</a>
                        @elseif(!$firstAtt->check_out_at)
                            <a href="{{ route('admin.employees.check-out', [$firstAtt->company_id, $userId]) }}" class="btn btn-outline-danger btn-sm rounded-3 fw-bold px-3">Check Out</a>
                        @endif
                    @endif

                    @can('attendance_show')
                        <a href="{{ route('admin.attendances.show', $userId) }}" class="btn-icon-lite" title="View"><i data-feather="eye" style="width: 16px;"></i></a>
                    @endcan

                    @if($firstAtt->attendance_id)
                        @can('attendance_update')
                            <button class="btn-icon-lite {{ $isNight ? 'editNightAttendance' : 'editAttendance' }}" data-href="{{ $updateUrl }}" data-name="{{ $firstAtt->user_name }}">
                                <i data-feather="edit-2" style="width: 16px;"></i>
                            </button>
                        @endcan
                        @can('attendance_delete')
                            <a href="{{ $deleteUrl }}" class="btn-icon-lite btn-delete-lite deleteAttendance">
                                <i data-feather="trash-2" style="width: 16px;"></i>
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted fw-medium">No records found.</p>
            </div>
        @endforelse
    </div>

    @if(!($attendanceDetail instanceof \Illuminate\Database\Eloquent\Collection))
        <div class="mt-4">{{ $attendanceDetail->links() }}</div>
    @endif

    @include('admin.attendance.common.edit-attendance-form')
    @include('admin.attendance.common.edit-night-attendance-form')
</div>
@endsection

@section('scripts')
    @include('admin.attendance.common.scripts')
    <script>
        $(document).ready(function () {
            feather.replace();
            const bId = {{ auth('admin')->check() ? "$('#branch_id option:selected').val()" : (auth()->user()->branch_id ?? 'null') }};
            if (bId) {
                $.ajax({ type: 'GET', url: "{{ url('admin/departments/get-All-Departments') }}/" + bId })
                .done(res => {
                    $('#department_id').empty().append('<option disabled selected>Department</option>');
                    res.data.forEach(d => $('#department_id').append(`<option value="${d.id}">${d.dept_name}</option>`));
                });
            }
        });
    </script>
@endsection