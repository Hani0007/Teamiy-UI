@extends('layouts.master')

@section('title', __('index.employee_payroll'))

@section('action', __('index.payroll_generate'))


@section('main-content')
<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh;">
    @include('admin.section.flash_message')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 style="color: #057db0; font-weight: 700;">{{ __('index.employee_payroll') }}</h2>
            @include('admin.payroll.employeeSalary.common.breadcrumb')
        </div>
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

            {{-- Full Detail Modal --}}
            <div class="modal fade" id="viewModal{{$key}}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="border-radius:24px; border:none; overflow:hidden;">
                        <div class="modal-header border-0 pb-0 px-4 pt-4">
                            <h5 class="fw-bold" style="color:#057db0;">Payroll Breakdown</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="d-flex align-items-center mb-4 p-3 rounded-4" style="background:#f8fafc;">
                                <div class="icon-box me-3" style="width:45px; height:45px; background:#057db0; color:#fff; border-radius:12px;">
                                    <i data-feather="user"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $payroll?->employee?->name }}</h6>
                                    <small class="text-muted">ID: #{{ $payroll->id }}</small>
                                </div>
                            </div>

                            <div class="p-3 rounded-4 border">
                                <div class="d-flex justify-content-between mb-2"><span>Base Salary:</span><strong>{{$currency}}{{number_format($payroll->base_salary,2)}}</strong></div>
                                <div class="d-flex justify-content-between mb-2 text-success"><span>Allowances:</span><strong>+{{$currency}}{{number_format($payroll->total_allowance,2)}}</strong></div>
                                <div class="d-flex justify-content-between mb-2 text-danger"><span>Deductions:</span><strong>-{{$currency}}{{number_format($payroll->total_deduction,2)}}</strong></div>
                                <div class="d-flex justify-content-between mb-2 text-danger"><span>Tax:</span><strong>-{{$currency}}{{number_format($payroll->tax,2)}}</strong></div>
                                <div class="d-flex justify-content-between border-top pt-2 mt-2 fw-bold text-primary" style="font-size:1.1rem;">
                                    <span>NET PAYABLE:</span>
                                    <span>{{$currency}}{{number_format($payroll->net_salary,2)}}</span>
                                </div>
                            </div>
                            
                            <a href="{{ route('admin.payroll.salary.slip', $payroll->id) }}" class="btn-premium w-100 mt-4 d-flex align-items-center justify-content-center text-decoration-none">
                                <i data-feather="download" style="width:16px; margin-right:8px;"></i> Download PDF Slip
                            </a>
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