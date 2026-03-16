@php use App\Models\User; @endphp
@extends('layouts.master')
@section('styles')
<style>
    .swal2-deny {
    border-color: transparent !important;
   }
</style>
@endsection
@section('title', __('index.employees_title'))

@section('main-content')

<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
    @include('admin.section.flash_message')
    @include('admin.employees.common.breadcrumb')

    {{-- 1. Modern Breadcrumbs & Top Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.employees') }}</h2>
            <div class="d-flex align-items-center gap-3 mt-1">
                <!--<nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="background: transparent; padding: 0; margin: 0;">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: #94a3b8; text-decoration: none; font-size: 12px;">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: #057db0; font-weight: 600; font-size: 12px;">Employees</li>
                    </ol>
                </nav>-->
            </div>
            <div class="d-flex gap-3 mt-2">
                <small class="fw-bold" style="color: #718096; font-size: 12px;">
                    <span style="color: #05cd99; font-size: 14px;">●</span> Active {{ $users->where('is_active', 1)->count() }}
                </small>
                <small class="fw-bold" style="color: #718096; font-size: 12px;">
                    <span style="color: #cbd5e0; font-size: 14px;">●</span> Inactive {{ $users->where('is_active', 0)->count() }}
                </small>
            </div>
        </div>

        @can('create_employee')
            <a href="{{ route('admin.employees.create') }}" class="btn-teamiy-add-new text-decoration-none shadow-sm" style="background: #057db0; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i data-feather="plus" style="width: 18px;"></i> Add Employee
            </a>
        @endcan
    </div>

    {{-- 2. Glass-morphism Filter Panel --}}
    <div class="glass-filter-panel mb-5 shadow-sm border-0" style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-radius: 20px; padding: 25px; border: 1px solid #ffffff;">
        <form action="{{ route('admin.employees.index') }}" id="employeeFilterForm" method="get" class="row g-3 align-items-end">
            
            @if(!isset(auth()->user()->branch_id))
                <div class="col-xxl-2 col-xl-2 col-md-6">
                    <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px;">BRANCH</label>
                    <select class="form-select shadow-none modern-select" name="branch_id" id="branch" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                        <option selected disabled>{{ __('index.select_branch') }}</option>
                        @foreach($branches as $branch)
                            <option {{ ($filterParameters['branch_id'] == $branch->id) ? 'selected' : '' }} value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="col-xxl-2 col-xl-2 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px;">DEPARTMENT</label>
                <select class="form-select shadow-none modern-select" id="department" name="department_id" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                    <option selected disabled>{{ __('index.select_department') }}</option>
                </select>
            </div>

            <div class="col-xxl-2 col-xl-2 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px;">NAME / EMAIL</label>
                <div style="position: relative;">
                    <i data-feather="user" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                    <input type="text" name="employee_name" value="{{ $filterParameters['employee_name'] }}" class="form-control shadow-none" placeholder="Search..." style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; padding-left: 45px; font-size: 14px;">
                </div>
            </div>

            <div class="col-xxl-2 col-xl-2 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px;">PHONE</label>
                <input type="number" name="phone" value="{{ $filterParameters['phone'] }}" class="form-control shadow-none" placeholder="Phone number" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
            </div>

            <div class="col-xxl-4 col-xl-4 col-md-12 d-flex gap-2">
                <button type="submit" class="btn w-100" style="background: #057db0; color: white; height: 48px; border-radius: 12px; font-weight: 600;">
                    {{ __('index.filter') }}
                </button>
                
                @can('create_employee')
                <button type="button" id="export_employee" data-href="{{ route('admin.employees.index') }}" class="btn btn-light w-100" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-weight: 600; color: #64748b; background: white;">
                    <i data-feather="download" style="width: 16px; margin-right: 5px;"></i> Export
                </button>
                @endcan

                <a href="{{route('admin.posts.index')}}" class="btn-theme-outline w-100 text-decoration-none d-flex align-items-center justify-content-center" 
                   style="height: 48px; border: 1px solid #e2e8f0; border-radius: 12px; color: #64748b; background: #fff; font-weight: 600;">
                    {{ __('index.reset') }}
                </a>
            </div>
        </form>
    </div>

    {{-- Stats Section --}}
    <div class="stats-container-teamiy mb-4">
        <div class="stat-card-new">
            <div class="stat-header">
                <i data-feather="users" style="width: 16px; color: #057db0;"></i> Total Employees
            </div>
            <div class="stat-main-row">
                <span class="stat-number">{{ $employeeStats['total_employees'] ?? '0' }}</span>
                <span class="stat-badge">{{ $employeeStats['growth_percentage'] ?? '0' }}%</span>
            </div>
            <span class="stat-subtext">There are {{ $employeeStats['new_employees_this_year'] ?? '0' }} new employees this year</span>
            {{-- ... prog rows ... --}}
            <div class="prog-row">
                <div class="prog-labels"><span style="color:#057db0;">Full-Time</span><span>{{ $employeeStats['full_time_count'] ?? '0' }}</span></div>
                <div class="prog-bar-bg"><div class="prog-fill" style="width: {{ $employeeStats['full_time_percentage'] ?? '0' }}%; background: #057db0;"></div></div>
            </div>
            <div class="prog-row">
                <div class="prog-labels"><span style="color:#3b82f6;">Part-Time</span><span>{{ $employeeStats['part_time_count'] ?? '0' }}</span></div>
                <div class="prog-bar-bg"><div class="prog-fill" style="width: {{ $employeeStats['part_time_percentage'] ?? '0' }}%; background: #3b82f6;"></div></div>
            </div>
        </div>

        <div class="stat-card-new">
            <div class="stat-header">
                <i data-feather="pie-chart" style="width: 16px; color: #fb8233;"></i> Employee Gender Ratio
            </div>
            <div class="gender-grid-new">
                <div class="gender-item"><span class="gender-label">Male</span><span class="gender-val">{{ $employeeStats['male_percentage'] ?? '0' }}%</span></div>
                <div class="gender-item"><span class="gender-label">Female</span><span class="gender-val">{{ $employeeStats['female_percentage'] ?? '0' }}%</span></div>
            </div>
            <div class="gender-counts-flex">
                <div class="gender-pill-new" style="background: #fff1f0; color: #f87171;">{{ $employeeStats['male_count'] ?? '0' }} Male</div>
                <div class="gender-pill-new" style="background: #f0f7ff; color: #057db0;">{{ $employeeStats['female_count'] ?? '0' }} Female</div>
            </div>
        </div>
    </div>

    {{-- Employee Grid --}}
    <div class="row g-4">
        @php $changeColor = [0 => 'success', 1 => 'primary']; @endphp
        @forelse($users as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-md-6 col-sm-12">
                <div class="card-teamiy-main shadow-sm">
                    {{-- Status & Options --}}
                    <div class="p-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <label class="switch-mini">
                                <input class="toggleStatus" href="{{ route('admin.employees.toggle-status', $value->id) }}" type="checkbox" {{ $value->is_active == 1 ? 'checked' : '' }}>
                                <span class="slider-mini round"></span>
                            </label>
                            <span class="status-label-small">{{ $value->is_active == 1 ? 'Active' : 'Inactive' }}</span>
                        </div>

                        <div class="dropdown">
                            <i data-feather="more-horizontal" class="cursor-pointer" style="color: #fb8233; width: 22px;" data-bs-toggle="dropdown"></i>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2" style="border-radius: 12px; min-width: 180px;">
                                @can('edit_employee')
                                    <li><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('admin.employees.edit', $value->id) }}">
                                        <i data-feather="edit-2" style="width: 14px; color: #fb8233;"></i> Edit Profile</a>
                                    </li>
                                @endcan
                                @can('change_password')
                                    <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 changePassword" data-href="{{ route('admin.employees.change-password', $value->id) }}">
                                        <i data-feather="lock" style="width: 14px; color: #fb8233;"></i> Change Password</a>
                                    </li>
                                @endcan
                                @can('delete_employee')
                                    @if($value->id != auth()->id() && $value->id != 1)
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger deleteEmployee" data-href="{{ route('admin.employees.delete', $value->id) }}">
                                            <i data-feather="trash-2" style="width: 14px; color: #fb8233;"></i> Delete User</a>
                                        </li>
                                    @endif
                                @endcan
                            </ul>
                        </div>
                    </div>

                    {{-- Profile Info --}}
                    <div class="text-center pb-2 px-3">
                        <div class="avatar-circle-teamiy mx-auto">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($value->name) }}&background=057db0&color=fff" alt="">
                        </div>
                        <h5 class="fw-bolder mt-3 mb-1" style="font-size: 18px; color: #111827;">{{ ucfirst($value->name) }}</h5>
                        <p class="text-muted mb-2" style="font-size: 13px; font-weight: 500;">{{ $value->post ? $value->post->post_name : 'N/A' }}</p>
                        
                        {{-- Holiday Attendance --}}
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                            <span style="font-size: 10px; font-weight: 800; color: #94a3b8;">HOLIDAY ATT.</span>
                            <label class="switch-mini">
                                <input class="toggleHolidayCheckIn" href="{{ route('admin.employees.toggle-holiday-checkin', $value->id) }}" type="checkbox" {{ $value->allow_holiday_check_in == 1 ? 'checked' : '' }}>
                                <span class="slider-mini round"></span>
                            </label>
                        </div>
                    </div>

                    {{-- Inner Card Details --}}
                    <div class="mx-3 mb-3 p-3 rounded-4" style="border: 1.5px solid #f1f5f9; background: #ffffff;">
                        <div class="fw-bold mb-3 d-flex justify-content-between align-items-center">
                            <span style="font-size: 12px; color: #111827;"># TP-00{{ $value->id }}</span>
                            <button class="changeWorkPlace btn btn-{{ $changeColor[$value->workspace_type] ?? 'success' }} btn-xs" 
                                    data-href="{{ route('admin.employees.change-workspace', $value->id) }}" 
                                    style="font-size: 10px; padding: 2px 8px; border-radius: 5px;">
                                {{ $value->workspace_type == User::FIELD ? 'Field' : 'Office' }}
                            </button>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-1 text-muted" style="font-size: 11px; font-weight: 700;">
                                <i data-feather="users" style="width: 13px;"></i> {{ $value->department ? Str::limit($value->department->dept_name, 12) : 'N/A' }}
                            </div>
                            <div class="d-flex align-items-center gap-1 text-muted" style="font-size: 11px; font-weight: 700;">
                                <i data-feather="clock" style="width: 13px;"></i> {{ $value->officeTime ? $value->officeTime->shift : 'N/A' }}
                            </div>
                        </div>

                        <div class="contact-pill-teamiy mb-2">
                            <i data-feather="mail" style="width: 12px;"></i> {{ Str::limit($value->email, 22) }}
                        </div>
                        <div class="contact-pill-teamiy">
                            <i data-feather="map-pin" style="width: 12px;"></i> {{ Str::limit($value->address, 22) ?: 'No Address' }}
                        </div>
                    </div>

                    {{-- Card Footer --}}
                    <div class="px-3 py-3 border-top d-flex justify-content-between align-items-center mt-auto" style="background: #ffffff;">
                        <span class="text-muted fw-bold" style="font-size: 11px;">Joined {{ $value->created_at->format('M Y') }}</span>
                        @can('show_detail_employee')
                        <a href="{{ route('admin.employees.show', $value->id) }}" class="text-decoration-none fw-bold view-details-link">
                            View details <i data-feather="chevron-right" style="width: 14px;"></i>
                        </a>
                        @endcan
                    </div>

                    <div style="height: 5px; background: #fb8233; width: 100%;"></div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No records found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $users->appends($_GET)->links() }}
    </div>
</section>

@include('admin.employees.common.password')

@endsection

@section('scripts')
    @include('admin.employees.common.scripts')
    <script>
    $(document).ready(function() { feather.replace(); });
    </script>
@endsection