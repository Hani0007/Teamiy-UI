@php use App\Helpers\AppHelper; @endphp
@php use App\Helpers\AttendanceHelper; @endphp
@extends('layouts.master')

@section('title', __('index.attendance'))
<style>
    button.btn.btn-sm.btn-outline-primary.rounded-pill.addEmployeeAttendance:hover {
    background-color: #057db0;
    border-color: #057db0;
    color: #ffffff;
}
</style>
@section('main-content')
<div class="content-wrapper">
    
    {{-- Variable Initialization Block --}}
    <?php
    if ($isBsEnabled) {
        $filterData['min_year'] = '2076'; $filterData['max_year'] = '2089'; $filterData['month'] = 'np';
        $nepaliDate = AppHelper::getCurrentNepaliYearMonth();
        $filterData['current_year'] = $nepaliDate['year']; $filterData['current_month'] = $nepaliDate['month'];
    } else {
        $filterData['min_year'] = '2020'; $filterData['max_year'] = '2033'; $filterData['month'] = 'en';
        $filterData['current_year'] = now()->format('Y'); $filterData['current_month'] = now()->month;
    }
    ?>

    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ ucfirst($userDetail->name) }}</h2>
            @include('admin.attendance.common.breadcrumb')
        </div>
        <!--<a href="{{ route('admin.attendances.index') }}" class="btn btn-white border px-4 rounded-pill shadow-sm fw-bold">
            <i data-feather="arrow-left" class="icon-sm"></i> {{ __('index.back') }}
        </a>-->
        <a href="{{ route('admin.attendances.index') }}">
        <button class="btn btn-sm btn-primary">
            <i class="link-icon" data-feather="arrow-left"></i> {{ __('index.back') }}
        </button>
    </a>
    </div>

    {{-- Filter Panel (Premium Glass Style) --}}
<div class="glass-filter-panel mb-5 shadow-sm border-0"
     style="background: rgba(255,255,255,0.7); backdrop-filter: blur(10px); border-radius:20px; padding:25px;">

    <form action="{{ route('admin.attendances.show', $userDetail->id) }}" method="get" class="row g-3 align-items-end">

        {{-- Year --}}
        <div class="col-xl-3 col-lg-3 col-md-6">
            <label class="form-label fw-bold text-muted small" style="letter-spacing:0.5px;">
                SELECT YEAR
            </label>

            <input type="number"
                   min="{{ $filterData['min_year'] }}"
                   max="{{ $filterData['max_year'] }}"
                   step="1"
                   placeholder="{{ __('index.attendance_year_example', ['year' => $filterData['min_year']]) }}"
                   id="year"
                   name="year"
                   value="{{ $filterParameter['year'] }}"
                   class="form-control shadow-none"
                   style="height:48px; border-radius:12px; border:1px solid #e2e8f0;">
        </div>

        {{-- Month --}}
        <div class="col-xl-3 col-lg-3 col-md-6">
            <label class="form-label fw-bold text-muted small" style="letter-spacing:0.5px;">
                SELECT MONTH
            </label>

            <select class="form-select modern-select shadow-none"
                    name="month"
                    id="month"
                    style="height:48px; border-radius:12px; border:1px solid #e2e8f0;">

                <option value="" {{ !isset($filterParameter['month']) ? 'selected' : '' }}>
                    {{ __('index.all_month') }}
                </option>

                @foreach($months as $key => $value)

                    <option value="{{ $key }}"
                        {{ (isset($filterParameter['month']) && $key == $filterParameter['month']) ? 'selected' : '' }}>

                        {{ $value[$filterData['month']] }}

                    </option>

                @endforeach

            </select>
        </div>

        {{-- Filter Button --}}
        <div class="col-xl-2 col-lg-3 col-md-6">

            <button type="submit"
                    class="btn-theme-primary w-100 border-0"
                    style="background:#057db0; color:#fff; height:48px; border-radius:12px; font-weight:600;">

                {{ __('index.filter') }}

            </button>

        </div>

        {{-- CSV Export --}}
        @can('attendance_csv_export')

        <div class="col-xl-2 col-lg-3 col-md-6">

            <button type="button"
                    id="download-excel"
                    data-href="{{ route('admin.attendances.show', $userDetail->id) }}"
                    class="btn-theme-secondary w-100 border-0"
                    style="background:#057db0; color:#fff; height:48px; border-radius:12px; font-weight:600;">

                {{ __('index.csv_export') }}

            </button>

        </div>

        @endcan

        {{-- Reset --}}
        <div class="col-xl-2 col-lg-3 col-md-6">

            <a href="{{ route('admin.attendances.show', $userDetail->id) }}"
               class="btn-theme-outline w-100 text-decoration-none d-flex align-items-center justify-content-center"
               style="height:48px; border:1px solid #e2e8f0; border-radius:12px; color:#64748b; background:#fff; font-weight:600;">

                {{ __('index.reset') }}

            </a>

        </div>

    </form>

</div>

    {{-- Stats Row --}}
    <div class="mb-4">
        <h6 class="fw-bold mb-0 text-dark" style="font-size: 20px; color: #057db0 !important;">
            {{ __('index.attendance_of') . ' ' . ucfirst($userDetail->name) }}
        </h6>
    </div>
    <div class="row g-3 mb-4">
        @php
            $summaryItems = [
                ['label' => 'Total Days', 'val' => $attendanceSummary['totalDays'] ?? 0, 'color' => '#fb8233'],
                ['label' => 'Present', 'val' => $attendanceSummary['totalPresent'] ?? 0, 'color' => '#10b981'],
                ['label' => 'Absent', 'val' => $attendanceSummary['totalAbsent'] ?? 0, 'color' => '#f43f5e'],
                ['label' => 'Leave', 'val' => $attendanceSummary['totalLeave'] ?? 0, 'color' => '#f59e0b'],
            ];
        @endphp
        @foreach($summaryItems as $item)
        <div class="col-md-3">
            <div class="stat-pill new-wi">
                <div class="stat-label">{{ $item['label'] }}</div>
                <div class="stat-value" style="color: {{ $item['color'] }}">{{ $item['val'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Cards Grid --}}
    <div class="attendance-grid">
        @forelse($attendanceDetail as $dayIndex => $dayData)
            @if(isset($dayData['data']) && count($dayData['data']) > 0)
                @foreach($dayData['data'] as $att)
                <div class="clean-card">
                    @php 
                        $statusColor = ($att['attendance_status'] == 1) ? '#fb82331c' : '#fee2e2';
                        $textColor = ($att['attendance_status'] == 1) ? '#fb8233' : '#991b1b';
                    @endphp
                    <span class="status-badge" style="background: {{ $statusColor }}; color: {{ $textColor }}">
                        {{ ($att['attendance_status'] == 1) ? 'Present' : 'Rejected' }}
                    </span>

                    <div class="d-flex align-items-center gap-3">
                        <div class="date-circle">
                            @php 
                                $formattedDate = \App\Helpers\AttendanceHelper::formattedAttendanceDate($isBsEnabled, $dayData['attendance_date']);
                                $dateParts = explode(' ', $formattedDate); // Assuming format like "12 May"
                            @endphp
                            <span>{{ $dateParts[0] ?? '' }}</span>
                            <small style="font-size: 9px;">{{ $dateParts[1] ?? '' }}</small>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold text-dark">{{ $formattedDate }}</p>
                            <span class="badge bg-light text-muted border-0 p-0 text-uppercase" style="font-size: 10px;">{{ $att['shift'] }} Shift</span>
                        </div>
                    </div>

                    <div class="time-container">
                        <div class="time-box">
                            <small>In</small>
                            <span>{{ ($att['shift'] == \App\Enum\ShiftTypeEnum::night->value) ? \App\Helpers\AttendanceHelper::changeNightAttendanceFormat($appTimeSetting, $att['night_checkin']) : \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($appTimeSetting, $att['check_in_at']) }}</span>
                        </div>
                        <div class="time-box text-end">
                            <small>Out</small>
                            <span>{{ ($att['shift'] == \App\Enum\ShiftTypeEnum::night->value) ? \App\Helpers\AttendanceHelper::changeNightAttendanceFormat($appTimeSetting, $att['night_checkout']) : \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceAdminView($appTimeSetting, $att['check_out_at']) }}</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="worked-hour-tag">
                            <i data-feather="clock" style="width: 12px; margin-right: 4px;"></i>
                            {{ \App\Helpers\AttendanceHelper::getWorkedTimeInHourAndMinute($att['worked_hour']) }}
                        </span>
                        
                        <div class="actions-row border-0 p-0 m-0">
                            @can('attendance_update')
                            <button class="btn-icon-lite editAttendance" data-href="{{ route('admin.attendances.update', $att['id']) }}" data-name="{{ $userDetail->name }}">
                                <i data-feather="edit-2" style="width: 14px;"></i>
                            </button>
                            @endcan
                            @can('attendance_delete')
                            <a href="{{ route('admin.attendance.delete', $att['id']) }}" class="btn-icon-lite btn-delete-lite">
                                <i data-feather="trash-2" style="width: 14px;"></i>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                {{-- Absent / Holiday Card --}}
                <div class="clean-card" style="opacity: 0.7; background: #fdfdfd; border-style: dashed;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="date-circle">
                            <span>{{ explode(' ', \App\Helpers\AttendanceHelper::formattedAttendanceDate($isBsEnabled, $dayData['attendance_date']))[0] }}</span>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold">{{ \App\Helpers\AttendanceHelper::formattedAttendanceDate($isBsEnabled, $dayData['attendance_date']) }}</p>
                            @php $reason = \App\Helpers\AttendanceHelper::getHolidayOrLeaveDetail($dayData['attendance_date'], $userDetail->id); @endphp
                            <span class="text-danger small fw-bold">{{ $reason ?: 'Absent' }}</span>
                        </div>
                    </div>
                    @if($reason == 'Absent')
                    <div class="actions-row justify-content-end mt-3">
                         <button class="btn btn-sm btn-outline-primary rounded-pill addEmployeeAttendance" data-date="{{ $dayData['attendance_date'] }}" style="font-size: 11px;">
                            + Manual Entry
                         </button>
                    </div>
                    @endif
                </div>
            @endif
        @empty
            <div class="col-12 text-center py-5">
                <img src="{{ asset('assets/images/no-data.png') }}" alt="" style="width: 100px; opacity: 0.5;">
                <p class="text-muted mt-3">No attendance records found for this month.</p>
            </div>
        @endforelse
    </div>

    @include('admin.attendance.common.edit-attendance-form')
    @include('admin.attendance.common.create-attendance-form')
    @include('admin.attendance.common.edit-night-attendance-form')
</div>
@endsection

@section('scripts')
    @include('admin.attendance.common.scripts')
    <script>
        $(document).ready(function() {
            feather.replace();
        });
    </script>
@endsection