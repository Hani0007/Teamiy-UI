@extends('layouts.master')

@section('title', __('index.employee_payroll'))

@section('action', __('index.payroll_generate'))
<style>
    a.btn-act.btn-view-style:hover {
    color: #fb8233;
}
</style>

@section('main-content')
<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh;">
    @include('admin.section.flash_message')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 style="color: #057db0; margin-bottom:10px;">{{ __('index.employee_payroll') }}</h2>
            @include('admin.payroll.employeeSalary.common.breadcrumb')
        </div>
        <!--<div class="card-header">
                <h6 class="card-title mb-0">{{__('index.payroll_create')}}</h6>
            </div>-->
    </div>

    {{-- Glass Filters --}}
    <div class="glass-panel mb-5">
        <form action="{{route('admin.employee-salary.payroll')}}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-bold text-muted small">BRANCH</label>
                <select class="form-select modern-select shadow-none" id="branch_id" name="branch_id">
                    <option value="" {{!isset($filterData['branch_id']) ? 'selected': ''}}>{{__('index.select_branch')}}</option>
                    @foreach($branches as $key => $value)
                        <option value="{{$value->id}}" {{ ((isset($filterData['branch_id']) && $value->id == $filterData['branch_id']) || (isset(auth()->user()->branch_id) && auth()->user()->branch_id == $value->id) ) ?'selected':'' }} > {{ucfirst($value->name)}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold text-muted small">DEPARTMENT</label>
                <select class="form-select modern-select shadow-none" name="department_id" id="department_id">
                    <option selected disabled>{{ __('index.select_department') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold text-muted small">MONTH</label>
                <select class="form-select modern-select shadow-none" name="month">
                    @foreach(['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'] as $m)
                        <option value="{{$m}}" {{ (request('month') === $m) ? 'selected' : '' }}>{{ucfirst($m)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold text-muted small">YEAR</label>
                <select class="form-select modern-select shadow-none" name="year">
                    @foreach (range(date('Y'), date('Y') - 5, -1) as $year)
                        <option {{ ($filterData['year'] ?? date('Y')) == $year ? 'selected': '' }} value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2" style="display: flex; gap: 10px;">
                <button type="submit" class="btn-premium">{{ __('index.filter') }}</button>
                <button type="clear" class="btn-premium" style="height: 48px; border: 1px solid #e2e8f0; border-radius: 12px; color: #64748b; background: #fff; font-weight: 600;">{{ __('index.clear') }}</button>
            </div>
            
        </form>
    </div>

    {{-- Cards Grid --}}
    <div class="row g-4">
        @forelse($payrolls['employeeSalary'] as $key => $payroll)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="payroll-card">
                    <span class="card-id">#{{ $payroll->id }}</span>
                    <div class="card-top-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="icon-box" style="background:#fff;"><i data-feather="user" style="width:16px;"></i></div>
                            <span class="status-badge {{ ($payroll->status == 'paid') ? 'status-paid' : 'status-pending' }}">
                                {{ ucfirst($payroll->status) }}
                            </span>
                        </div>
                        <span class="emp-name text-truncate">{{ $payroll?->employee?->name }}</span>
                        <div class="salary-val">{{ $currency }} {{ number_format($payroll->net_salary, 2) }}</div>
                    </div>

                    <div class="body-content">
                        <div class="info-item">
                            <div class="icon-box"><i data-feather="calendar" style="width:14px;"></i></div>
                            <div class="info-box">
                                <small>DURATION</small>
                                <p>
                                    @if(isset($payroll->payment_type) && $payroll->payment_type == 'monthly')
                                        {{ \App\Helpers\AppHelper::getMonthYear($salary_from) }}
                                    @else
                                        {{ \App\Helpers\AttendanceHelper::payslipDate($salary_from) }} - {{ \App\Helpers\AttendanceHelper::payslipDate($salary_to) }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon-box"><i data-feather="clock" style="width:14px;"></i></div>
                            <div class="info-box">
                                <small>PAID ON</small>
                                <p>{{ $payroll->created_at ? \App\Helpers\AttendanceHelper::paidDate($payroll->created_at) : '-' }}</p>
                            </div>
                        </div>

                        <div class="action-row">
                            @can('show_payroll_detail')
                                <a href="javascript:void(0)" class="btn-act btn-view-style" data-bs-toggle="modal" data-bs-target="#viewModal{{$key}}">
                                    <i data-feather="eye" style="width:14px; margin-right:5px;"></i> {{ __('index.view') }}
                                </a>
                            @endcan
                            @can('payroll_payment')
                                @if($payroll->status == 'pending')
                                    <form action="{{ route('admin.employee-salaries.make_payment', $payroll->id) }}" method="post" style="flex:1;">
                                        @csrf @method('put')
                                        <button type="submit" class="btn-act btn-pay-style w-100">
                                            <i data-feather="dollar-sign" style="width:14px; margin-right:5px;"></i> Pay
                                        </button>
                                    </form>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            {{-- Full Detail Modal - Premium Style --}}
<div class="modal fade" id="viewModal{{$key}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);">
            
            <div class="modal-header border-0 p-4 pb-0" style="display: flex; align-items: center; justify-content: space-between;">
                <h4 class="fw-bold" style="color: #057db0; letter-spacing: -0.5px;">Payroll</h4>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    
                    {{-- Left Column: Employee Info --}}
                    <div class="col-md-4">
                        <div class="h-100 p-4" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                            <h6 class="fw-bold mb-4" style="color: #057db0; font-size: 0.85rem; text-transform: uppercase;">Employee Information</h6>
                            
                            <div class="text-center mb-4">
                                <div class="mx-auto d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #057db0; border-radius: 50%; font-size: 1.5rem; font-weight: 700; border: 2px solid #fff; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.1);">
                                    {{ substr($payroll?->employee?->name, 0, 1) }}
                                </div>
                                <div class="mt-3 fw-bold text-dark" style="font-size: 1rem;">{{ $payroll?->employee?->name }}</div>
                                <div class="text-muted small">{{ $payroll?->employee?->email ?? 'no-email@mail.com' }}</div>
                            </div>

                            <div class="space-y-3">
                                <div class="mb-3">
                                    <label class="d-block text-muted small mb-1">Payroll Period</label>
                                    <span class="fw-semibold text-dark small">
                                        @if(isset($payroll->payment_type) && $payroll->payment_type == 'monthly')
                                            {{ \App\Helpers\AppHelper::getMonthYear($salary_from) }}
                                        @else
                                            {{ \App\Helpers\AttendanceHelper::payslipDate($salary_from) }} - {{ \App\Helpers\AttendanceHelper::payslipDate($salary_to) }}
                                        @endif
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <label class="d-block text-muted small mb-1">Status</label>
                                    <span class="badge px-3 py-2" style="background: #fb823326; color: #fb8233; border-radius: 8px; font-weight: 600;">{{ ucfirst($payroll->status) }}</span>
                                </div>
                                <div class="mb-3">
                                    <label class="d-block text-muted small mb-1">Branch</label>
                                    <span class="fw-semibold text-dark small">{{ $payroll?->employee?->branch?->name ?? 'Main Branch' }}</span>
                                </div>
                                <div>
                                    <label class="d-block text-muted small mb-1">Department</label>
                                    <span class="fw-semibold text-dark small">{{ $payroll?->employee?->department?->dept_name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Middle Column: Salary Breakdown --}}
                    <div class="col-md-5">
                        <div class="h-100 p-4" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                            <h6 class="fw-bold mb-4" style="color: #057db0; font-size: 0.85rem; text-transform: uppercase;">Salary Breakdown</h6>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Payment Type</span>
                                <span class="fw-medium small text-dark">{{ ucfirst($payroll->payment_type) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-muted small">Payroll Type</span>
                                <span class="fw-medium small text-dark">{{ $payroll->payroll_type ?? 'Hourly' }}</span>
                            </div>

                            <div style="border-top: 1px dashed #e2e8f0; margin: 15px 0;"></div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-dark">Base Salary</span>
                                <span class="fw-bold text-dark">{{$currency}}{{number_format($payroll->base_salary,2)}}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Tax</span>
                                <span class="fw-bold text-danger">- {{$currency}}{{number_format($payroll->tax,2)}}</span>
                            </div>

                            <div class="mt-4 p-3 rounded-3" style="background: #f8fafc; border: 1px solid #f1f5f9;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary" style="font-size: 1.1rem;">Net Salary</span>
                                    <span class="fw-bold text-primary" style="font-size: 1.3rem;">{{$currency}}{{number_format($payroll->net_salary,2)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Hours & Quick Actions --}}
                    <div class="col-md-3">
                        <div class="h-100 d-flex flex-column gap-3">
                            {{-- Hours Info --}}
                            <div class="p-4" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                                <h6 class="fw-bold mb-3" style="color: #057db0; font-size: 0.85rem; text-transform: uppercase;">Hours</h6>
                                <div class="text-muted small mb-1">Worked Hours</div>
                                <div class="h4 fw-bold text-dark mb-0">{{ $payroll->worked_hours ?? '120' }} hrs</div>
                            </div>

                            {{-- Visual Element --}}
                            <div class="flex-grow-1 p-3 d-flex align-items-center justify-content-center" style="opacity: 0.5;">
                                <i data-feather="trending-up" style="width: 50px; height: 50px; color: #cbd5e1;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Buttons 
                <div class="mt-4 pt-2">
                    <a href="{{ route('admin.payroll.salary.slip', $payroll->id) }}" class="btn w-100 mb-2 py-3 shadow-sm border-0" 
                       style="background: #0ea5e9; color: white; border-radius: 12px; font-weight: 600; transition: all 0.3s ease;">
                        <i data-feather="download" style="width: 18px; margin-right: 8px; vertical-align: middle;"></i> Download Salary Slip (PDF)
                    </a>
                    <button type="button" class="btn w-100 py-3 border-0" data-bs-dismiss="modal" 
                            style="background: #f97316; color: white; border-radius: 12px; font-weight: 600;">
                        Close
                    </button>
                </div>--}}
                <div class="branch-footer-actions">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <a href="{{ route('admin.payroll.salary.slip', $payroll->id) }}" class="branch-back-btn text-decoration-none">
                <i class="download"></i>
                Download Salary Slip (PDF)
            </a>
        </div>
            </div>
        </div>
    </div>
</div>
        @empty
            <div class="col-12 text-center py-5">No records found.</div>
        @endforelse
    </div>
</section>
@endsection

@section('scripts')
    @include('admin.payroll.employeeSalary.common.scripts')
    <script>
        $(document).ready(function() {
            feather.replace();
            
            $('#branch_id').change(function() {
                let branchId = $(this).val();
                let deptId = "{{ $filterData['department_id'] ?? '' }}";
                $('#department_id').empty();
                if (branchId) {
                    $.ajax({
                        type: 'GET',
                        url: "{{ url('admin/departments/get-All-Departments') }}/" + branchId,
                    }).done(function(res) {
                        $('#department_id').append('<option disabled selected>Select Department</option>');
                        res.data.forEach(function(d) {
                            $('#department_id').append('<option ' + (d.id == deptId ? "selected" : "") + ' value="'+d.id+'">'+d.dept_name+'</option>');
                        });
                    });
                }
            }).trigger('change');
        });
    </script>
@endsection