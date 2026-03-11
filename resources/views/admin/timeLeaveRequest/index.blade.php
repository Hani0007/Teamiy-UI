@php use App\Models\LeaveRequestMaster; @endphp
@php use App\Enum\LeaveStatusEnum; @endphp
@php use App\Helpers\AppHelper; @endphp
@extends('layouts.master')

@section('title', __('index.time_leave_request'))

@section('main-content')

{{-- PHP Logic to define filterData --}}
@php
    if (AppHelper::ifDateInBsEnabled()) {
        $filterData['min_year'] = '2076';
        $filterData['max_year'] = '2089';
        $filterData['month'] = 'np';
    } else {
        $filterData['min_year'] = '2020';
        $filterData['max_year'] = '2033';
        $filterData['month'] = 'en';
    }
@endphp

<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
    @include('admin.section.flash_message')

    {{-- 1. Modern Breadcrumbs & Top Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.time_leave_request') }}</h2>
            @include('admin.timeLeaveRequest.common.breadcrumb')
            <!--<nav aria-label="breadcrumb" class="mt-1">
                <ol class="breadcrumb" style="background: transparent; padding: 0; margin: 0;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: #94a3b8; text-decoration: none; font-size: 12px;">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #057db0; font-weight: 600; font-size: 12px;">{{__('index.lists')}}</li>
                </ol>
            </nav>-->
        </div>

        @can('create_time_leave_request')
            <a href="{{ route('admin.time-leave-request.create') }}" style="text-decoration: none;">
                <button class="btn btn-primary">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.create_time_leave_request') }}</span>
                </button>
            </a>
        @endcan
    </div>

    {{-- 2. Glass-morphism Filter Panel --}}
    <div class="glass-filter-panel mb-5 shadow-sm border-0" style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-radius: 20px; padding: 25px; border: 1px solid #ffffff;">
        <form class="forms-sample" action="{{route('admin.time-leave-request.index')}}" method="get">
            <div class="row g-3 align-items-end">

                @if(!isset(auth()->user()->branch_id))
                    <div class="col-xxl col-xl-3 col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">{{ __('index.branch') }}</label>
                        <select class="form-select shadow-none modern-select" id="branch_id" name="branch_id" style="height: 48px; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <option value="" selected disabled>{{ __('index.select_branch') }}</option>
                            @if(isset($companyDetail))
                                @foreach($companyDetail->branches()->get() as $key => $branch)
                                    <option {{ (isset($filterParameters['branch_id']) && $filterParameters['branch_id'] == $branch->id) ? 'selected' : '' }} value="{{$branch->id}}">{{ucfirst($branch->name)}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                @endif

                <div class="col-xxl col-xl-3 col-md-6">
                    <label class="form-label fw-bold text-muted small text-uppercase">{{ __('index.department') }}</label>
                    <select class="form-select shadow-none modern-select" id="department_id" name="department_id" style="height: 48px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <option value="" selected disabled>{{ __('index.select_department') }}</option>
                    </select>
                </div>

                <div class="col-xxl col-xl-3 col-md-6">
                    <label class="form-label fw-bold text-muted small text-uppercase">{{ __('index.employee') }}</label>
                    <select class="form-select shadow-none modern-select" id="requestedBy" name="requested_by" style="height: 48px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <option value="" selected disabled>{{ __('index.select_employee') }}</option>
                    </select>
                </div>

                <div class="col-xxl col-xl-2 col-md-6">
                    <label class="form-label fw-bold text-muted small text-uppercase">{{ __('index.year') }}</label>
                    <input type="number" min="{{ $filterData['min_year'] }}" max="{{ $filterData['max_year'] }}" step="1"
                           placeholder="{{ $filterData['min_year'] }}" id="year" name="year" 
                           value="{{ $filterParameters['year'] ?? '' }}" class="form-control shadow-none" 
                           style="height: 48px; border-radius: 10px; border: 1px solid #e2e8f0;">
                </div>

                <div class="col-xxl col-xl-2 col-md-6">
                    <label class="form-label fw-bold text-muted small text-uppercase">{{ __('index.month') }}</label>
                    <select class="form-select shadow-none modern-select" name="month" id="month" style="height: 48px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <option value="" {{!isset($filterParameters['month']) ? 'selected': ''}} >{{ __('index.all_month') }}</option>
                        @foreach($months as $key => $value)
                            <option value="{{$key}}" {{ (isset($filterParameters['month']) && $key == $filterParameters['month'] ) ?'selected':'' }} >
                                {{$value[$filterData['month']]}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xxl col-xl-2 col-md-6">
                    <label class="form-label fw-bold text-muted small text-uppercase">{{ __('index.status') }}</label>
                    <select class="form-select shadow-none modern-select" name="status" id="status" style="height: 48px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <option value="" {{!isset($filterParameters['status']) ? 'selected': ''}} >{{ __('index.all_status') }}</option>
                        @foreach(LeaveRequestMaster::STATUS as $value)
                            <option value="{{$value}}" {{ (isset($filterParameters['status']) && $value == $filterParameters['status'] ) ?'selected':'' }} > {{ucfirst($value)}} </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xxl col-xl-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn w-100" style="background: #057db0; color: white; height: 48px; border-radius: 10px; font-weight: 600;">
                            {{ __('index.filter') }}
                        </button>
                        <a href="{{route('admin.posts.index')}}" class="btn-theme-outline w-100 text-decoration-none d-flex align-items-center justify-content-center" 
                   style="height: 48px; border: 1px solid #e2e8f0; border-radius: 12px; color: #64748b; background: #fff; font-weight: 600;">
                    {{ __('index.reset') }}
                </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- 3. Cards Grid (Rest of your original layout) --}}
    <div class="row g-4 justify-content-start">
        @php
            $statusTheme = [
                'approved'  => ['bg' => '#057db0', 'text' => '#fff'],
                'accepted'  => ['bg' => '#057db0', 'text' => '#fff'],
                'rejected'  => ['bg' => '#ef4444', 'text' => '#fff'],
                'cancelled' => ['bg' => '#ef4444', 'text' => '#fff'],
                'pending'   => ['bg' => '#fb8233', 'text' => '#fff'],
            ];
        @endphp

        @forelse($timeLeaves as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    <div class="card-glossy-header">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="briefcase"></i>
                            </div>
                            
                            @can('update_time_leave')
                                <a href="javascript:void(0)" 
                                   class="leaveRequestUpdate"
                                   data-href="{{route('admin.time-leave-request.update-status',$value->id)}}"
                                   data-status="{{$value->status}}"
                                   data-remark="{{$value->admin_remark}}"
                                   style="text-decoration: none;">
                                    <span class="badge" style="background-color: {{ $statusTheme[$value->status]['bg'] ?? '#6c757d' }}; color: {{ $statusTheme[$value->status]['text'] ?? '#fff' }}; padding: 6px 12px; border-radius: 6px; font-weight: 600;">
                                        {{ $value->status === 'accepted' ? 'Approved' : ucfirst($value->status) }}
                                    </span>
                                </a>
                            @endcan
                        </div>
                        <h4 class="branch-name-display">{{ $value->leaveRequestedBy ? ucfirst($value->leaveRequestedBy->name) : 'N/A' }}</h4>
                        <span class="branch-ref-pill">ID: #{{$value->id}}</span>
                    </div>

                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="calendar" style="color: #057db0;"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.leave_date') }}</small>
                                    <p>{{ AppHelper::timeLeaverequestDate($value->issue_date) }}</p>
                                </div>
                            </div>
                            
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="watch" style="color: #10b981;"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.start_time') }}</small>
                                    <p>{{ AppHelper::convertLeaveTimeFormat($value->start_time) }}</p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="clock" style="color: #ef4444;"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.end_time') }}</small>
                                    <p>{{ AppHelper::convertLeaveTimeFormat($value->end_time) }}</p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="user"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.requested_by') }}</small>
                                    <p>{{ $value->leaveRequestedBy ? ucfirst($value->leaveRequestedBy->name) : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="stats-footer-box">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="emp-group">
                                    <span class="emp-label text-muted" style="font-size: 11px;">
                                        Created: {{ $value->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div class="action-dock">
                                    @can('time_leave_list')
                                        <a href="javascript:void(0)" class="btn-action edit showTimeLeaveReason" 
                                           data-href="{{ route('admin.time-leave-request.show', $value->id) }}"
                                           title="{{ __('index.show_leave_reason') }}">
                                            <i data-feather="eye" style="widtg:18px; height:18px"></i>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state card shadow-sm p-5" style="border-radius: 20px; background: white;">
                    <i data-feather="info" style="width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 15px;"></i>
                    <h4 class="text-muted">{{ __('index.no_records_found') }}</h4>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{$timeLeaves->appends($_GET)->links()}}
    </div>
</section>

@include('admin.timeLeaveRequest.show')
@include('admin.timeLeaveRequest.common.form-model')
@endsection

@section('scripts')
    @include('admin.timeLeaveRequest.common.scripts')
    <script>
        $(document).ready(function() {
            feather.replace();
            
            $('.showTimeLeaveReason').on('click', function (event) {
                event.preventDefault();
                const url = $(this).attr('data-href');
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.data) {
                            const leaveRequest = data.data;
                            document.getElementById('referral').innerText = leaveRequest.name || 'Admin';
                            document.getElementById('description').innerText = leaveRequest.reasons || 'N/A';
                            document.getElementById('adminRemark').innerText = leaveRequest.admin_remark || 'N/A';
                            const modal = new bootstrap.Modal(document.getElementById('addslider'));
                            modal.show();
                        }
                    }).catch(error => console.error('Error:', error));
            });

            // Re-using your existing dropdown logic
            const branchId = "{{ $filterParameters['branch_id'] ?? '' }}";
            const departmentId = "{{ $filterParameters['department_id'] ?? '' }}";
            const employeeId = "{{ $filterParameters['requested_by'] ?? '' }}";

            const loadDepartments = async (selectedBranchId) => {
                if (!selectedBranchId) return;
                try {
                    $('#department_id').empty().append('<option selected disabled>{{ __("index.select_department") }}</option>');
                    const response = await $.ajax({
                        type: 'GET',
                        url: `{{ url('admin/departments/get-All-Departments') }}/${selectedBranchId}`,
                    });
                    response.data.forEach(data => {
                        $('#department_id').append(`<option value="${data.id}" ${data.id == departmentId ? 'selected' : ''}>${data.dept_name}</option>`);
                    });
                    if (departmentId) loadEmployees();
                } catch (error) { console.error(error); }
            };

            const loadEmployees = async () => {
                const depId = $('#department_id').val();
                if (!depId) return;
                try {
                    $('#requestedBy').empty().append('<option selected disabled>{{ __("index.select_employee") }}</option>');
                    const response = await fetch(`{{ url('admin/employees/get-all-employees') }}/${depId}`);
                    const data = await response.json();
                    data.data.forEach(user => {
                        $('#requestedBy').append(`<option value="${user.id}" ${user.id == employeeId ? 'selected' : ''}>${user.name}</option>`);
                    });
                } catch (error) { console.error(error); }
            };

            $('#branch_id').change(function() { loadDepartments($(this).val()); });
            $('#department_id').change(loadEmployees);

            if($('#branch_id').val()) loadDepartments($('#branch_id').val());
        });
    </script>
@endsection