@extends('layouts.master')

@section('title', __('index.termination'))
@section('styles')
<style>
    .swal2-deny {
    border-color: transparent !important;
   }
</style>
@endsection
@section('main-content')
<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
    @include('admin.section.flash_message')

    {{-- 1. Modern Breadcrumbs & Top Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.termination') }}</h2>
            @include('admin.terminationManagement.termination.common.breadcrumb')
        </div>
        
        @can('create_termination')
            <a href="{{ route('admin.termination.create') }}" style="text-decoration: none;">
                <button class="btn btn-primary">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.add_termination') }}</span>
                </button>
            </a>
        @endcan
    </div>

    {{-- 2. Glass-morphism Filter Panel --}}
    <div class="glass-filter-panel mb-5 shadow-sm border-0" style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-radius: 20px; padding: 25px; border: 1px solid #ffffff;">
        <form action="{{route('admin.termination.index')}}" method="get" class="row g-3 align-items-end">
            
            @if(!isset(auth()->user()->branch_id))
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">{{ __('index.branch') }}</label>
                    <select class="form-select shadow-none modern-select" name="branch_id" id="branch_id" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                        <option selected disabled>{{ __('index.select_branch') }}</option>
                        @if(isset($companyDetail))
                            @foreach($companyDetail->branches()->get() as $key => $branch)
                                <option value="{{$branch->id}}" {{ (isset($filterParameters['branch_id']) && $filterParameters['branch_id'] == $branch->id) ? 'selected': '' }}>
                                    {{ucfirst($branch->name)}}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @endif

            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">{{ __('index.department') }}</label>
                <select class="form-select shadow-none modern-select" name="department_id" id="department_id" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                    <option selected disabled>{{ __('index.select_department') }}</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">{{ __('index.employee') }}</label>
                <select class="form-select shadow-none modern-select" name="employee_id" id="employee_id" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                    <option selected disabled>{{ __('index.select_employee') }}</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">{{ __('index.termination_date') }}</label>
                <div style="position: relative;">
                    @if(\App\Helpers\AppHelper::ifDateInBsEnabled())
                        <input type="text" id="nepali-datepicker-from" name="termination_date" value="{{ $filterParameters['termination_date'] ?? '' }}" placeholder="mm/dd/yyyy" class="form-control nepaliDate shadow-none" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                    @else
                        <input type="date" name="termination_date" value="{{ $filterParameters['termination_date'] ?? '' }}" class="form-control shadow-none" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                    @endif
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn w-100" style="background: #057db0; color: white; height: 48px; border-radius: 12px; font-weight: 600; transition: all 0.3s ease;">
                        {{ __('index.filter') }}
                    </button>
                    <a href="{{route('admin.termination.index')}}" class="btn w-100 d-flex align-items-center justify-content-center" style="height: 48px; border: 1px solid #e2e8f0; border-radius: 12px; color: #64748b; background: #fff; font-weight: 600; text-decoration: none;">
                        {{ __('index.reset') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- 3. Termination Cards Grid (Keeping your existing design) --}}
    <div class="row g-4 justify-content-start">
        @php
            $statusTheme = [
                'approved'  => ['bg' => '#057db0', 'text' => '#fff'],
                'accepted'  => ['bg' => '#057db0', 'text' => '#fff'],
                'rejected'  => ['bg' => '#ef4444', 'text' => '#fff'],
                'cancelled' => ['bg' => '#ef4444', 'text' => '#fff'],
                'pending'   => ['bg' => '#FB8233', 'text' => '#fff'],
                'on_review' => ['bg' => '#F8FAFC', 'text' => '#fff'],
            ];
        @endphp

        @forelse($terminationLists as $key => $value)
            @php
                $currentStatus = strtolower($value->status);
                $theme = $statusTheme[$currentStatus] ?? ['bg' => '#64748b', 'text' => '#fff'];
            @endphp
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    <div class="card-glossy-header" style="background: linear-gradient(135deg, #057db0 0%, #046691 100%);">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="user-x"></i>
                            </div>
                            
                            <a href="javascript:void(0)" 
                               class="terminationStatusUpdate text-decoration-none"
                               data-href="{{route('admin.termination.update-status',$value->id)}}"
                               data-status="{{$value->status}}"
                               data-reason="{{$value->admin_remark}}"
                               data-id="{{$value->id}}">
                               <span class="badge shadow-sm" style="background-color: {{ $theme['bg'] }}; color: {{ $theme['text'] }}; border: none;">
                                   {{ ucfirst($value->status) }}
                               </span>
                            </a>
                        </div>
                        <h4 class="branch-name-display">{{ $value->employee?->name }}</h4>
                        <span class="branch-ref-pill">Termination ID: #{{$value->id}}</span>
                    </div>

                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="calendar"></i></div>
                                <div class="text-content">
                                    <small>TERMINATION DATE</small>
                                    <p>{{ \App\Helpers\AppHelper::formatDateForView($value->termination_date) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="stats-footer-box">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="emp-group">
                                    <div class="avatar-stack">
                                        <i data-feather="clock" style="width: 14px; color: #64748b;"></i>
                                    </div>
                                    <span class="emp-label text-muted" style="font-size: 11px; font-weight: 600;">Notice: {{ \App\Helpers\AppHelper::formatDateForView($value->notice_date) }}</span>
                                </div>
                                <div class="action-dock">
                                    @can('show_termination')
                                        <a href="{{route('admin.termination.show',$value->id)}}" class="btn-action edit" title="View Detail" style="background: #e0f2fe; color: #0369a1;">
                                            <i data-feather="eye" style="width:16px; height:16px;"></i>
                                        </a>
                                    @endcan

                                    @can('update_termination')
                                        <a href="{{route('admin.termination.edit',$value->id)}}" class="btn-action edit" title="Edit">
                                            <i data-feather="edit-3"></i>
                                        </a>
                                    @endcan
                                    
                                    @can('delete_termination')
                                        <a data-href="{{route('admin.termination.delete',$value->id)}}" class="btn-action delete deleteBranch cursor-pointer" title="Delete">
                                            <i data-feather="trash-2"></i>
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
        {{ $terminationLists->appends($_GET)->links() }}
    </div>
</section>

@include('admin.terminationManagement.termination.common.status_update')

@endsection

@section('scripts')
    @include('admin.terminationManagement.termination.common.scripts')
    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection