@extends('layouts.master')

@section('title', __('index.leave_request'))

{{-- Modern Top Action Button --}}
@section('button')
    <a href="{{ route('admin.leave-request.index') }}">
        <button class="btn btn-sm btn-primary">
            <i class="link-icon" data-feather="arrow-left"></i> {{ __('index.button_back') }}
        </button>
    </a>
@endsection

@section('main-content')
{{-- @dd($companyDetail) --}}
<div class="teamy-body-wrapper">
    
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.leave_request') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge">Admin Panel</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-plus-circle"></i> {{ __('index.create') }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form class="forms-sample" action="{{route('admin.leave-request.save')}}" enctype="multipart/form-data" method="post">
        @csrf

        <div class="teamy-main-card">
            
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-user-edit"></i>
                </div>
                <div class="section-heading-text">
                    <h4>Employee Selection</h4>
                    <p>Select the branch, department, and employee for the leave request</p>
                </div>
            </div>

            <div class="section-divider"></div>

            <div class="row">
                {{-- Branch Selection (Visible for Super Admin) --}}
                @if(!isset(auth()->user()->branch_id))
                <div class="col-lg-4 col-md-6 mb-4">
                    <label for="branch_id" class="form-label">{{ __('index.branch') }} <span style="color: red">*</span></label>
                    <select class="form-select select2-input" id="branch_id" name="branch_id" required>
                        <option selected disabled>{{ __('index.select_branch') }}</option>
                        @if(isset($companyDetail))
                            @foreach($companyDetail->branches()->get() as $key => $branch)
                                <option value="{{$branch->id}}">{{ucfirst($branch->name)}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                @endif

                {{-- Department Selection --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <label for="department_id" class="form-label">{{ __('index.department') }} <span style="color: red">*</span></label>
                    <select class="form-select select2-input" id="department_id" name="department_id" required>
                        <option selected disabled>{{ __('index.select_department') }}</option>
                        
                        @if(isset($companyDetail))
                            @foreach($companyDetail->departments()->get() as $key => $department)
                                <option value="{{$department->id}}">{{ucfirst($department->dept_name)}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Employee Selection --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <label for="requestedBy" class="form-label">{{ __('index.requested_for') }}<span style="color: red">*</span></label>
                    <select class="form-select select2-input" id="requestedBy" name="requested_by" required>
                        <option selected disabled>{{ __('index.select_employee') }}</option>
                    </select>
                </div>
            </div>

            <div class="section-title-wrapper mt-4">
                <div class="section-icon">
                    <i class="fa fa-calendar-alt"></i>
                </div>
                <div class="section-heading-text">
                    <h4>Leave Duration & Reason</h4>
                    <p>Specify leave type, dates, and documentation</p>
                </div>
            </div>
            <div class="section-divider"></div>

            <div class="row">
                {{-- Leave Type --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <label for="leaveType" class="form-label">{{ __('index.leave_type') }}<span style="color: red">*</span></label>
                    <select class="form-select select2-input" id="leaveType" name="leave_type_id" required>
                        <option selected disabled>{{ __('index.select_leave_type') }} </option>
                    </select>
                </div>

                {{-- From Date --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <label for="leave_from" class="form-label">{{ __('index.from_date') }}<span style="color: red">*</span></label>
                    @if($bsEnabled)
                        <input type="text" class="form-control leave_from" id="leave_from" value="{{old('leave_from')}}" name="leave_from" autocomplete="off" readonly>
                    @else
                        <input class="form-control" type="date" name="leave_from" value="{{old('leave_from')}}" required />
                    @endif
                </div>

                {{-- To Date --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <label for="leave_to" class="form-label">{{ __('index.to_date') }}<span style="color: red">*</span></label>
                    @if($bsEnabled)
                        <input type="text" class="form-control leave_to" id="leave_to" value="{{old('leave_to')}}" name="leave_to" autocomplete="off" readonly>
                    @else
                        <input class="form-control" type="date" name="leave_to" value="{{old('leave_to')}}" required />
                    @endif
                </div>

                {{-- Avatar/Document Upload --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <label for="avatar" class="form-label">{{ __('index.upload_avatar') }} </label>
                    <input class="form-control" type="file" id="avatar" name="avatar" accept="image/*">
                </div>

                {{-- Reason --}}
                <div class="col-lg-8 mb-4">
                    <label for="reasons" class="form-label">{{ __('index.reason') }}<span style="color: red">*</span></label>
                    <textarea class="form-control" name="reasons" rows="3" placeholder="Explain the reason for leave...">{{ old('reasons') }}</textarea>
                </div>
            </div>
        </div>

        <div class="branch-footer-actions">
            <a href="{{ route('admin.leave-request.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i> {{ __('index.button_back') }}
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> {{ __('index.submit') }}
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // Initialize Select2
        $("#department_id, #branch_id, #requestedBy, #leaveType").select2();

        // Nepali Date Picker setup
        $('.leave_from, .leave_to').nepaliDatePicker({
            language: "english",
            dateFormat: "YYYY-MM-DD",
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 50,
            readOnlyInput: true,
            disableAfter: "2089-12-30",
        });

        // AJAX Logic: Load Departments
        const loadDepartments = async () => {
            const isAdmin = {{ auth('admin')->check() ? 'true' : 'false' }};
            const defaultBranchId = {{ auth()->user()->branch_id ?? 'null' }};
            const selectedBranchId = isAdmin ? $('#branch_id').val() : defaultBranchId;

            if (!selectedBranchId) return;

            try {
                const response = await $.ajax({
                    type: 'GET',
                    url: `{{ url('admin/departments/get-All-Departments') }}/${selectedBranchId}`,
                });

                $('#department_id').empty().append('<option selected disabled>{{ __("index.select_department") }}</option>');
                if (response.data && response.data.length > 0) {
                    response.data.forEach(department => {
                        $('#department_id').append(`<option value="${department.id}">${department.dept_name}</option>`);
                    });
                } else {
                    $('#department_id').append('<option disabled>{{ __("index.no_department_found") }}</option>');
                }
                loadEmployees();
            } catch (error) {
                $('#department_id').append('<option disabled>{{ __("index.error_loading_department") }}</option>');
            }
        };

        // AJAX Logic: Load Employees
        const loadEmployees = async () => {
            const selectedDepartmentId = $('#department_id').val();
            if (!selectedDepartmentId) return;

            try {
                const response = await fetch(`{{ url('admin/employees/get-all-employees') }}/${selectedDepartmentId}`);
                const data = await response.json();
                
                $('#requestedBy').empty().append('<option selected disabled>{{ __("index.select_employee") }}</option>');
                if (data.data && data.data.length > 0) {
                    data.data.forEach(user => {
                        $('#requestedBy').append(`<option value="${user.id}">${user.name}</option>`);
                    });
                } else {
                    $('#requestedBy').append('<option disabled>{{ __("index.no_employees_found") }}</option>');
                }
            } catch (error) {
                $('#requestedBy').append('<option disabled>{{ __("index.error_loading_employees") }}</option>');
            }
        };

        // AJAX Logic: Load Leave Types
        const loadLeaveTypes = async () => {
            const selectedEmployee = $('#requestedBy').val();
            if (!selectedEmployee) return;
            try {
                const response = await fetch(`{{ url('admin/leaves/get-employee-leave-types') }}/${selectedEmployee}`);
                const data = await response.json();
                
                $('#leaveType').empty().append('<option selected disabled>{{ __("index.select_leave_type") }}</option>');
                if (data.leveTypes && data.leveTypes.length > 0) {
                    data.leveTypes.forEach(type => {
                        $('#leaveType').append(`<option value="${type.id}">${type.name}</option>`);
                    });
                } else {
                    $('#leaveType').append('<option disabled>{{ __("index.leave_type_not_found") }}</option>');
                }
            } catch (error) {
                $('#leaveType').append('<option disabled>{{ __("index.error_loading_leave_types") }}</option>');
            }
        };

        // Events
        $('#branch_id').change(loadDepartments);
        $('#department_id').change(loadEmployees);
        $('#requestedBy').change(loadLeaveTypes);

        // Initial check for regular users
        if (!{{ auth('admin')->check() ? 'true' : 'false' }}) {
            loadDepartments();
        }
    });
</script>
@endsection