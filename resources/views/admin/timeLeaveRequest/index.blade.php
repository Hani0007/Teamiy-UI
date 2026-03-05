{{--@php use App\Models\LeaveRequestMaster; @endphp
@php use App\Enum\LeaveStatusEnum; @endphp
@php use App\Helpers\AppHelper; @endphp
@extends('layouts.master')

@section('title',__('index.time_leave_request'))

@section('action',__('index.lists'))

@section('button')
    @can('create_time_leave_request')
        <a href="{{ route('admin.time-leave-request.create')}}">
            <button class="btn btn-primary">
                <i class="link-icon" data-feather="plus"></i>{{__('index.create_time_leave_request')}}
            </button>
        </a>
    @endcan
@endsection

@section('main-content')
        <?php
        if (AppHelper::ifDateInBsEnabled()) {
            $filterData['min_year'] = '2076';
            $filterData['max_year'] = '2089';
            $filterData['month'] = 'np';
        } else {
            $filterData['min_year'] = '2020';
            $filterData['max_year'] = '2033';
            $filterData['month'] = 'en';
        }
        ?>

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.timeLeaveRequest.common.breadcrumb')

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">{{__('index.time_leave_request_filter')}}</h6>
            </div>
            <form class="forms-sample card-body pb-0" action="{{route('admin.time-leave-request.index')}}" method="get">

                <div class="row align-items-center">

                    @if(!isset(auth()->user()->branch_id))
                        <div class="col-xxl col-xl-3 col-md-6 mb-4">
                            <select class="form-select" id="branch_id" name="branch_id" required>
                                <option selected disabled>{{ __('index.select_branch') }}
                                </option>
                                @if(isset($companyDetail))
                                    @foreach($companyDetail->branches()->get() as $key => $branch)
                                        <option
                                            {{ $filterParameters['branch_id'] == $branch->id ? 'selected' : '' }} value="{{$branch->id}}">{{ucfirst($branch->name)}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @endif
                    <!-- Departments Field -->
                    <div class="col-xxl col-xl-3 col-md-6 mb-4">
                        <select class="form-select" id="department_id" name="department_id">
                            <option selected disabled>{{ __('index.select_department') }}</option>

                        </select>
                    </div>
                    <div class="col-xxl col-xl-3 col-md-6 mb-4">
                        <select class="form-select" id="requestedBy" name="requested_by">
                            <option selected disabled>{{ __('index.select_employee') }}</option>

                        </select>

                    </div>

                    <div class="col-xxl col-xl-3 col-md-6  mb-4">
                        <input type="number" min="{{ $filterData['min_year']}}"
                               max="{{ $filterData['max_year']}}" step="1"
                               placeholder="{{ __('index.leave_requested_year') }} : {{$filterData['min_year']}}"
                               id="year"
                               name="year" value="{{$filterParameters['year']}}"
                               class="form-control">
                    </div>

                    <div class="col-xxl col-xl-3 col-md-6 mb-4">
                        <select class="form-select form-select-lg" name="month" id="month">
                            <option
                                value="" {{!isset($filterParameters['month']) ? 'selected': ''}} >{{ __('index.all_month') }}</option>
                            @foreach($months as $key => $value)
                                <option
                                    value="{{$key}}" {{ (isset($filterParameters['month']) && $key == $filterParameters['month'] ) ?'selected':'' }} >
                                    {{$value[$filterData['month']]}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xxl col-xl-3 col-md-6 mb-4">
                        <select class="form-select form-select-lg" name="status" id="status">
                            <option
                                value="" {{!isset($filterParameters['status']) ? 'selected': ''}} >{{ __('index.all_status') }}</option>
                            @foreach(LeaveRequestMaster::STATUS as  $value)
                                <option
                                    value="{{$value}}" {{ (isset($filterParameters['status']) && $value == $filterParameters['status'] ) ?'selected':'' }} > {{ucfirst($value)}} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xxl col-xl-3  mb-4">
                        <div class="d-flex">
                            <button type="submit"
                                    class="btn btn-block btn-secondary me-2">{{ __('index.filter') }}</button>
                            <a class="btn btn-block btn-primary"
                               href="{{route('admin.time-leave-request.index')}}">{{ __('index.reset') }}</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">@lang('index.time_leave_list')</h6>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('index.leave_date') }}</th>
                            <th>{{ __('index.start_time') }}</th>
                            <th>{{ __('index.end_time') }}</th>
                            <th>{{ __('index.requested_by') }}</th>
                            @can('time_leave_list')
                                <th class="text-center">{{ __('index.reason') }}</th>
                            @endcan
                            @can('update_time_leave')
                                <th class="text-center">{{ __('index.status') }}</th>
                            @endcan
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <?php
                            $color = [
                                LeaveStatusEnum::approved->value => 'success',
                                LeaveStatusEnum::rejected->value => 'danger',
                                LeaveStatusEnum::pending->value => 'secondary',
                                LeaveStatusEnum::cancelled->value => 'danger',
                                LeaveStatusEnum::accepted->value => 'success',
                            ];

                            ?>
                        @forelse($timeLeaves as $key => $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ AppHelper::timeLeaverequestDate($value->issue_date) }}</td>
                                <td>{{ AppHelper::convertLeaveTimeFormat($value->start_time) }}</td>
                                <td>{{ AppHelper::convertLeaveTimeFormat($value->end_time) }}</td>
                                <td>{{$value->leaveRequestedBy ? ucfirst($value->leaveRequestedBy->name) : 'N/A'}} </td>

                                @can('time_leave_list')
                                    <td class="text-center">
                                        <a href="#" class="showTimeLeaveReason"
                                           data-href="{{ route('admin.time-leave-request.show', $value->id) }}"
                                           title="{{ __('index.show_leave_reason') }}">
                                            <i class="link-icon" data-feather="eye"></i>
                                        </a>
                                    </td>
                                @endcan

                                @can('update_time_leave')
                                    <td class="text-center">
                                        <a href=""
                                           id="leaveRequestUpdate"
                                           data-href="{{route('admin.time-leave-request.update-status',$value->id)}}"
                                           data-status="{{$value->status}}"
                                           data-remark="{{$value->admin_remark}}"
                                        >
                                            <button class="btn btn-{{ $color[$value->status] }} btn-xs">
                                                {{ $value->status === 'accepted' ? 'Approved' : ucfirst($value->status) }}
                                            </button>
                                        </a>
                                    </td>
                            @endcan
                        @empty
                            <tr>
                                <td colspan="100%">
                                    <p class="text-center"><b>{{ __('index.no_records_found') }}</b></p>
                                </td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
    <div class="dataTables_paginate mt-3">
        {{$timeLeaves->appends($_GET)->links()}}
    </div>

    @include('admin.timeLeaveRequest.show')
    @include('admin.timeLeaveRequest.common.form-model')
@endsection

@section('scripts')
    @include('admin.timeLeaveRequest.common.scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.showTimeLeaveReason').forEach(function (element) {
                element.addEventListener('click', function (event) {
                    event.preventDefault();
                    const url = this.getAttribute('data-href');

                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data && data.data) {
                                const leaveRequest = data.data;
                                document.getElementById('referral').innerText = leaveRequest.name || 'Admin';
                                document.getElementById('description').innerText = leaveRequest.reasons || 'N/A';
                                document.getElementById('adminRemark').innerText = leaveRequest.admin_remark || 'N/A';

                                const modal = new bootstrap.Modal(document.getElementById('addslider'));
                                modal.show();
                            } else {
                                console.error('Data format is incorrect or data is missing:', data);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });

        $(document).ready(function () {
            // Pre-selected values from $filterParameters
            const branchId = String({{ $filterParameters['branch_id'] ?? 'null' }});
            const departmentId = String({{ $filterParameters['department_id'] ?? 'null' }});
            const employeeId = String({{ $filterParameters['requested_by'] ?? 'null' }});

            const isAdmin = {{ auth('admin')->check() ? 'true' : 'false' }};
            const defaultBranchId = {{ auth()->user()->branch_id ?? 'null' }};

            const loadDepartments = async (selectedBranchId) => {
                if (!selectedBranchId) return;

                try {
                    $('#department_id').empty().append('<option selected disabled>{{ __("index.select_department") }}</option>');
                    $('#requestedBy').empty().append('<option selected disabled>{{ __("index.select_employee") }}</option>');

                    const response = await $.ajax({
                        type: 'GET',
                        url: `{{ url('admin/departments/get-All-Departments') }}/${selectedBranchId}`,
                    });

                    if (!response || !response.data || response.data.length === 0) {
                        $('#department_id').append('<option disabled>{{ __("index.no_departments_found") }}</option>');
                        return;
                    }

                    response.data.forEach(data => {
                        $('#department_id').append(`<option value="${data.id}" ${data.id == departmentId ? 'selected' : ''}>${data.dept_name}</option>`);
                    });

                    // If departmentId is pre-selected, load employees
                    if (departmentId) {
                        await loadEmployees();
                    }
                } catch (error) {
                    console.error('Error loading departments:', error);
                    $('#department_id').append('<option disabled>{{ __("index.error_loading_departments") }}</option>');
                }
            };

            const loadEmployees = async () => {
                const selectedDepartmentId = $('#department_id').val();
                if (!selectedDepartmentId) return;

                try {
                    $('#requestedBy').empty().append('<option selected disabled>{{ __("index.select_employee") }}</option>');

                    const response = await fetch(`{{ url('admin/employees/get-all-employees') }}/${selectedDepartmentId}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    });

                    const data = await response.json();

                    if (data.data && data.data.length > 0) {
                        data.data.forEach(user => {
                            $('#requestedBy').append(`<option value="${user.id}" ${user.id == employeeId ? 'selected' : ''}>${user.name}</option>`);
                        });
                    } else {
                        $('#requestedBy').append('<option disabled>{{ __("index.no_employees_found") }}</option>');
                    }
                } catch (error) {
                    console.error('Error loading employees:', error);
                    $('#requestedBy').append('<option disabled>{{ __("index.error_loading_employees") }}</option>');
                }
            };

            // Load departments and employees based on pre-selected branch_id
            const initializeDropdowns = async () => {
                let selectedBranchId;

                if (isAdmin) {
                    selectedBranchId = $('#branch_id').val() || branchId; // Use DOM value or fallback to filterParameters
                    $('#branch_id').change(() => loadDepartments($('#branch_id').val())); // Bind change event
                } else {
                    selectedBranchId = defaultBranchId; // Non-admin users use their default branch
                }

                if (selectedBranchId) {
                    await loadDepartments(selectedBranchId);
                }
            };

            // Call initialization
            initializeDropdowns();

            // Bind change event for department_id
            $('#department_id').change(loadEmployees);
        });

    </script>

@endsection


--}}

@php use App\Models\LeaveRequestMaster; @endphp
@php use App\Enum\LeaveStatusEnum; @endphp
@php use App\Helpers\AppHelper; @endphp
@extends('layouts.master')

@section('title', __('index.time_leave_request'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0;">
                {{ __('index.time_leave_request') }}
            </h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px;">
                <i data-feather="list" style="width: 14px; vertical-align: middle;"></i> {{__('index.lists')}}
            </p>
        </div>
        
        @can('create_time_leave_request')
            <a href="{{ route('admin.time-leave-request.create') }}" style="text-decoration: none;">
                <button class="btn-premium-add">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.create_time_leave_request') }}</span>
                </button>
            </a>
        @endcan
    </div>

    {{-- Cards Grid --}}
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
                            
                            {{-- Status Button --}}
                            @can('update_time_leave')
                                <a href="javascript:void(0)" 
                                   id="leaveRequestUpdate"
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
                            {{-- Leave Date --}}
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="calendar" style="color: #057db0;"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.leave_date') }}</small>
                                    <p>{{ AppHelper::timeLeaverequestDate($value->issue_date) }}</p>
                                </div>
                            </div>
                            
                            {{-- Start Time --}}
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="watch" style="color: #10b981;"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.start_time') }}</small>
                                    <p>{{ AppHelper::convertLeaveTimeFormat($value->start_time) }}</p>
                                </div>
                            </div>

                            {{-- End Time --}}
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="clock" style="color: #ef4444;"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.end_time') }}</small>
                                    <p>{{ AppHelper::convertLeaveTimeFormat($value->end_time) }}</p>
                                </div>
                            </div>

                            {{-- Requested By --}}
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
                                            <i data-feather="eye"></i>
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
                <div class="empty-state">
                    <i data-feather="info" style="width: 50px; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3">{{ __('index.no_records_found') }}</p>
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
            
            // Eye Icon Detail Popup
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
        });
    </script>
@endsection



