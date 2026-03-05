@extends('layouts.master')

@section('title', __('index.time_leave_request'))

{{-- Top Header Buttons --}}
@section('button')
    <a href="{{ route('admin.time-leave-request.index') }}">
        <button class="btn btn-sm btn-primary">
            <i class="link-icon" data-feather="arrow-left"></i> {{ __('index.button_back') }}
        </button>
    </a>
@endsection

@section('styles')
<style>
    /* Styling consistency with sample page */
</style>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">

    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.time_leave_request') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge">New</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-clock"></i> {{ __('index.create') }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form class="forms-sample" action="{{route('admin.time-leave-request.store')}}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="teamy-main-card">

            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-calendar-alt"></i>
                </div>
                <div class="section-heading-text">
                    <h4>{{ __('index.time_leave_request') }} Details</h4>
                    <p>Please fill in the leave details below</p>
                </div>
            </div>

            <div class="section-divider"></div>

            <div class="row">
                {{-- Branch Selection --}}
                @if(!isset(auth()->user()->branch_id))
                <div class="col-lg-4 col-md-6 mb-4">
                    <label for="branch_id" class="form-label">{{ __('index.branch') }} <span style="color: red">*</span></label>
                    <select class="form-select" id="branch_id" name="branch_id">
                        <option selected disabled>{{ __('index.select_branch') }}</option>
                        @if(isset($companyDetail))
                            @foreach($companyDetail->branches()->get() as $key => $branch)
                                <option value="{{$branch->id}}"
                                    {{ ((isset($noticeDetail) && ($noticeDetail->branch_id ) == $branch->id) || (isset(auth()->user()->branch_id) && auth()->user()->branch_id == $branch->id)) ? 'selected': '' }}>
                                    {{ucfirst($branch->name)}}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                @endif

                {{-- Department Selection --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <label for="department_id" class="form-label">{{ __('index.department') }} <span style="color: red">*</span></label>
                    <select class="form-select" id="department_id" name="department_id">
                        <option selected disabled>{{ __('index.select_department') }}</option>
                    </select>
                </div>

                {{-- Requested For (Employee) --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <label for="requestedBy" class="form-label">{{__('index.requested_for')}}<span style="color: red">*</span></label>
                    <select class="form-select" id="requestedBy" name="requested_by" required>
                        <option selected disabled> {{__('index.select_employee')}}</option>
                    </select>
                </div>

                {{-- Date Picker --}}
                @if(\App\Helpers\AppHelper::ifDateInBsEnabled())
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="nepali_startDate" class="form-label">{{__('index.leave_date')}} <span style="color: red">*</span></label>
                        <input type="text" id="nepali_startDate" name="issue_date" value="{{ old('issue_date') }}" placeholder="yyyy-mm-dd" class="form-control startDate"/>
                    </div>
                @else
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label for="issue_date" class="form-label">{{__('index.leave_date')}}<span style="color: red">*</span></label>
                        <input class="form-control" type="date" name="issue_date" value="{{old('issue_date')}}" required />
                    </div>
                @endif

                {{-- Time From --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <label for="leave_from" class="form-label">{{__('index.from')}} <span style="color: red">*</span></label>
                    <input class="form-control" type="time" name="leave_from" value="{{old('leave_from')}}" required />
                </div>

                {{-- Time To --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <label for="leave_to" class="form-label">{{__('index.to')}}</label>
                    <input class="form-control end_time" type="time" name="leave_to" value="{{old('leave_to')}}" />
                </div>

                {{-- Reason --}}
                <div class="col-lg-12 mb-4">
                    <label for="reasons" class="form-label">{{__('index.reason')}}<span style="color: red">*</span></label>
                    <textarea class="form-control" name="reasons" rows="4">{{ old('reasons') }}</textarea>
                </div>
            </div>
        </div>

        <div class="branch-footer-actions">
            <a href="{{ route('admin.time-leave-request.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i>
                {{ __('index.button_back') }}
            </a>
            <button type="submit" class="btn btn-primary">
                {{__('index.submit')}}
            </button>
        </div>

    </form>
</div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $("#branch_id").select2({});
            $("#requestedBy").select2({});
            $("#department_id").select2({});

            $('#nepali_startDate').nepaliDatePicker({
                language: "english",
                dateFormat: "YYYY-MM-DD",
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 20,
                disableAfter: "2089-12-30",
            });
        });

        $(document).ready(function () {
            const loadDepartments = async () => {
                const isAdmin = {{ auth('admin')->check() ? 'true' : 'false' }};
                const defaultBranchId = {{ auth()->user()->branch_id ?? 'null' }};
                const selectedBranchId = isAdmin ? $('#branch_id').val() : defaultBranchId;

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
                        $('#department_id').append(`<option value="${data.id}">${data.dept_name}</option>`);
                    });
                } catch (error) {
                    console.error('Error loading departments:', error);
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
                            $('#requestedBy').append(`<option value="${user.id}">${user.name}</option>`);
                        });
                    } else {
                        $('#requestedBy').append('<option disabled>{{ __("index.no_employees_found") }}</option>');
                    }
                } catch (error) {
                    $('#requestedBy').append('<option disabled>{{ __("index.error_loading_employees") }}</option>');
                }
            };

            const isAdmin = {{ auth('admin')->check() ? 'true' : 'false' }};
            if (isAdmin) {
                $('#branch_id').change(loadDepartments);
            } else {
                loadDepartments();
            }
            $('#department_id').change(loadEmployees);
        });
    </script>
@endsection