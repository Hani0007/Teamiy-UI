@extends('layouts.master')

@section('title', __('index.leave_requests'))

@section('main-content')
<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
   @include('admin.section.flash_message')

    {{-- 1. Modern Breadcrumbs & Top Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.leave_requests') }}</h2>
            @include('admin.leaveRequest.common.breadcrumb')
            <!--<nav aria-label="breadcrumb" class="mt-1">
                <ol class="breadcrumb" style="background: transparent; padding: 0; margin: 0;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: #94a3b8; text-decoration: none; font-size: 12px;">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #057db0; font-weight: 600; font-size: 12px;">Leave Requests</li>
                </ol>
            </nav>-->
        </div>

        @canany(['create_leave_request','access_admin_leave'])
            <a href="{{ route('admin.leave-request.add')}}" style="text-decoration: none;">
                <button class="btn-premium-add shadow-sm" style="background: #057db0; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 600; border: none; display: flex; align-items: center; gap: 8px;">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.create_leave_request') }}</span>
                </button>
            </a>
        @endcanany
    </div>

    {{-- 2. Glass-morphism Filter Panel --}}
    <div class="glass-filter-panel mb-5 shadow-sm border-0" style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-radius: 20px; padding: 25px; border: 1px solid #ffffff;">
        <form class="forms-sample" action="{{route('admin.leave-request.index')}}" method="get">
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

                <div class="col-xxl col-xl-3 col-md-6">
                    <label class="form-label fw-bold text-muted small text-uppercase">{{ __('index.leave_type') }}</label>
                    <select class="form-select shadow-none modern-select" name="leave_type" id="leaveType" style="height: 48px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <option value="" {{!isset($filterParameters['leave_type']) ? 'selected': ''}} >{{ __('index.all_leave_type') }}</option>
                         @if(isset($companyDetail))
                            @foreach($companyDetail->leaveTypes()->get() as $key => $leaveType)
                                <option value="{{$leaveType->id}}" {{ (isset($filterParameters['leave_type']) && $filterParameters['leave_type'] == $leaveType->id) ? 'selected' : '' }} >
                                    {{ucfirst($leaveType->name)}}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-xxl col-xl-3 col-md-6">
                    <label class="form-label fw-bold text-muted small text-uppercase">{{ __('index.year') }}</label>
                    <input type="number" 
                           min="{{ $filterData['min_year'] ?? 2020 }}" 
                           max="{{ $filterData['max_year'] ?? date('Y') }}" 
                           step="1"
                           placeholder="{{ __('index.leave_requested_year') }}" 
                           id="year" name="year" 
                           value="{{ $filterParameters['year'] ?? '' }}" class="form-control shadow-none" 
                           style="height: 48px; border-radius: 10px; border: 1px solid #e2e8f0;">
                </div>

                <div class="col-xxl col-xl-3 col-md-6">
                    <label class="form-label fw-bold text-muted small text-uppercase">{{ __('index.month') }}</label>
                    <select class="form-select shadow-none modern-select" name="month" id="month" style="height: 48px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <option value="" {{!isset($filterParameters['month']) ? 'selected': ''}} >{{ __('index.all_month') }}</option>
                        @isset($months)
                            @foreach($months as $key => $value)
                                <option value="{{$key}}" {{ (isset($filterParameters['month']) && $key == $filterParameters['month'] ) ?'selected':'' }} >
                                    {{ $value[$filterData['month'] ?? 'en'] ?? $value }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                <div class="col-xxl col-xl-3 col-md-6">
                    <label class="form-label fw-bold text-muted small text-uppercase">{{ __('index.status') }}</label>
                    <select class="form-select shadow-none modern-select" name="status" id="status" style="height: 48px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <option value="" {{!isset($filterParameters['status']) ? 'selected': ''}} >{{ __('index.all_status') }}</option>
                        @foreach(\App\Models\LeaveRequestMaster::STATUS as  $value)
                            <option value="{{$value}}" {{ (isset($filterParameters['status']) && $value == $filterParameters['status'] ) ?'selected':'' }} > {{ucfirst($value)}} </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xxl col-xl-3">
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

    {{-- 3. Data Cards Section --}}
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

        @forelse($leaveDetails as $key => $value)
            @php 
                $currentStatus = strtolower($value->status);
                $theme = $statusTheme[$currentStatus] ?? ['bg' => '#64748b', 'text' => '#fff'];
            @endphp
            
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    <div class="card-glossy-header">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square"><i data-feather="file-text"></i></div>
                            
                            <a href="javascript:void(0)" class="leaveRequestUpdate text-decoration-none"
                               data-href="{{route('admin.leave-request.update-status',$value->id)}}"
                               data-status="{{$value->status}}" data-remark="{{$value->admin_remark}}" data-id="{{$value->id}}">
                                <span class="badge" style="background-color: {{ $theme['bg'] }}; color: {{ $theme['text'] }}; padding: 8px 12px; border-radius: 8px; font-weight: 600; font-size: 11px; cursor: pointer;">
                                    {{ $value->status === 'accepted' ? 'Approved' : ucfirst($value->status) }}
                                </span>
                            </a>
                        </div>
                        <h4 class="branch-name-display">{{ $value->leaveType ? ucfirst($value->leaveType->name) : 'Leave Request'}}</h4>
                        <span class="branch-ref-pill">Request ID: #{{$value->id}}</span>
                    </div>

                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="calendar"></i></div>
                                <div class="text-content">
                                    <small>{{ strtoupper(__('index.from')) }}</small>
                                    <p>{{ \App\Helpers\AppHelper::convertLeaveDateFormat($value->leave_from) }}</p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="calendar"></i></div>
                                <div class="text-content">
                                    <small>{{ strtoupper(__('index.to')) }}</small>
                                    <p>{{ \App\Helpers\AppHelper::convertLeaveDateFormat($value->leave_to) }}</p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="user"></i></div>
                                <div class="text-content">
                                    <small>{{ strtoupper(__('index.requested_by')) }}</small>
                                    <p>{{ $value->leaveRequestedBy ? ucfirst($value->leaveRequestedBy->name) : 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="clock"></i></div>
                                <div class="text-content">
                                    <small>{{ strtoupper(__('index.requested_days')) }}</small>
                                    <p>{{ $value->no_of_days }} Days</p>
                                </div>
                            </div>
                        </div>

                        <div class="stats-footer-box">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="emp-group">
                                    <span style="color: #64748b; font-weight: 700; font-size: 10px; margin-right: 5px;">{{ strtoupper(__('index.requested_date')) }}:</span>
                                    <span class="emp-label" style="font-size: 11px; font-weight: 600; color: #057db0;">
                                        {{ \App\Helpers\AppHelper::formatDateForView($value->leave_requested_date) }}
                                    </span>
                                </div>
                                <div class="action-dock">
                                    <a href="#" class="btn-action edit showLeaveReason" 
                                       data-href="{{ route('admin.leave-request.show', $value->id) }}"
                                       title="{{ __('index.show_leave_reason') }}">
                                        <i data-feather="eye" style="color: #057db0;"></i>
                                    </a>
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
        {{$leaveDetails->links()}}
    </div>
</section>

@include('admin.leaveRequest.show')
@include('admin.leaveRequest.common.form-model')
@include('admin.leaveRequest.common.approval-info-model')

@endsection

@section('scripts')
    @include('admin.leaveRequest.common.scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            feather.replace();

            document.querySelectorAll('.showLeaveReason').forEach(function (element) {
                element.addEventListener('click', function (event) {
                    event.preventDefault();
                    const url = this.getAttribute('data-href');

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.data) {
                                const leaveRequest = data.data;
                                document.getElementById('referredBy').innerText = leaveRequest.name || 'Admin';
                                document.getElementById('description').innerText = leaveRequest.reasons || 'N/A';
                                document.getElementById('adminRemark').innerText = leaveRequest.admin_remark || 'N/A';

                                const modalElement = document.getElementById('addslider');
                                if (modalElement) {
                                    const modal = new bootstrap.Modal(modalElement);
                                    modal.show();
                                }
                            }
                        })
                        .catch(error => console.error('Error fetching data:', error));
                });
            });
        });
    </script>
@endsection