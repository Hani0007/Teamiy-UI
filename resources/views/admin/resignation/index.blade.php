@php use App\Enum\ResignationStatusEnum; @endphp
@extends('layouts.master')

@section('title', __('index.resignation'))

@section('main-content')
<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
    @include('admin.section.flash_message')

    {{-- 1. Modern Breadcrumbs & Top Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.resignation') }}</h2>
            @include('admin.resignation.common.breadcrumb')
        </div>
        <a href="{{ route('admin.resignation.create') }}" style="text-decoration: none;">
            <button class="btn-premium-add shadow-sm" style="background: #057db0; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 600; border: none; display: flex; align-items: center; gap: 8px;">
                <i data-feather="plus" style="width: 20px;"></i>
                <span>{{ __('index.add_resignation') }}</span>
            </button>
        </a>
    </div>

    {{-- 2. Glass-morphism Filter Panel --}}
    <div class="glass-filter-panel mb-5 shadow-sm border-0" style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-radius: 20px; padding: 25px; border: 1px solid #ffffff;">
        <form action="{{route('admin.resignation.index')}}" method="get" class="row g-3 align-items-end">
            
            @if(!isset(auth()->user()->branch_id))
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">{{ __('index.branch') }}</label>
                    <select class="form-select shadow-none modern-select" id="branch_id" name="branch_id" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
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
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">{{ __('index.resignation_date') }}</label>
                <div>
                    @if(\App\Helpers\AppHelper::ifDateInBsEnabled())
                        <input type="text" id="nepali-datepicker-from" name="resignation_date" value="{{ $filterParameters['resignation_date'] ?? '' }}" placeholder="mm/dd/yyyy" class="form-control nepaliDate shadow-none" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                    @else
                        <input type="date" name="resignation_date" value="{{ $filterParameters['resignation_date'] ?? '' }}" class="form-control shadow-none" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                    @endif
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn w-100" style="background: #057db0; color: white; height: 48px; border-radius: 12px; font-weight: 600; border: none; transition: all 0.3s ease;">
                        {{ __('index.filter') }}
                    </button>
                    <a href="{{route('admin.resignation.index')}}" class="btn w-100 d-flex align-items-center justify-content-center" style="height: 48px; border: 1px solid #e2e8f0; border-radius: 12px; color: #64748b; background: #fff; font-weight: 600; text-decoration: none;">
                        {{ __('index.reset') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Cards Grid --}}
    <div class="row g-4 justify-content-start">
        @php
            $statusTheme = [
                ResignationStatusEnum::approved->value  => ['bg' => '#057db0', 'text' => '#fff'],
                ResignationStatusEnum::onReview->value  => ['bg' => '#057DB0', 'text' => '#fff'],
                ResignationStatusEnum::pending->value   => ['bg' => '#FB8233', 'text' => '#fff'],
                ResignationStatusEnum::cancelled->value => ['bg' => '#ef4444', 'text' => '#fff'],
            ];
        @endphp

        @forelse($resignationLists as $key => $value)
            @php
                $theme = $statusTheme[$value->status] ?? ['bg' => '#64748b', 'text' => '#fff'];
                $isExpired = (($value->status == ResignationStatusEnum::approved->value) && strtotime(date('Y-m-d')) > strtotime($value->last_working_day));
            @endphp
            
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    {{-- Card Header --}}
                    <div class="card-glossy-header" style="background: linear-gradient(135deg, #057db0 0%, #046691 100%);">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="user"></i>
                            </div>
                            
                            {{-- Status Badge --}}
                            @if($isExpired)
                                <span class="badge shadow-sm" style="background-color: {{ $theme['bg'] }}; color: {{ $theme['text'] }}; border: none;">
                                    {{ ucfirst($value->status) }}
                                </span>
                            @else
                                <a href="javascript:void(0)" class="resignationStatusUpdate text-decoration-none"
                                   data-href="{{ route('admin.resignation.update-status',$value->id) }}"
                                   data-status="{{$value->status}}" data-reason="{{$value->admin_remark}}" data-id="{{$value->id}}">
                                   <span class="badge shadow-sm" style="background-color: {{ $theme['bg'] }}; color: {{ $theme['text'] }}; border: none;">
                                       {{ ucfirst($value->status) }}
                                   </span>
                                </a>
                            @endif
                        </div>
                        <h4 class="branch-name-display">{{ $value->employee?->name }}</h4>
                        
                        <div class="d-flex justify-content-between align-items-center mt-2 position-relative" style="z-index:2;">
                            <span class="branch-ref-pill">Resignation ID: #{{$value->id}}</span>
                            @can('show_resignation')
                                <a href="{{route('admin.resignation.show',$value->id)}}" 
                                   style="background: rgba(255,255,255,0.2); color: white; padding: 4px 8px; border-radius: 6px; backdrop-filter: blur(5px);" 
                                   title="View Detail">
                                    <i data-feather="eye" style="width: 16px; height: 16px;"></i>
                                </a>
                            @endcan
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="calendar"></i></div>
                                <div class="text-content">
                                    <small>RESIGNATION DATE</small>
                                    <p>{{ \App\Helpers\AppHelper::formatDateForView($value->resignation_date) }}</p>
                                </div>
                            </div>
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="alert-circle"></i></div>
                                <div class="text-content">
                                    <small>LAST WORKING DAY</small>
                                    <p>{{ \App\Helpers\AppHelper::formatDateForView($value->last_working_day) }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons at bottom --}}
                        @if(strtotime(date('Y-m-d')) <= strtotime($value->last_working_day))
                            <div class="pt-3 border-top d-flex justify-content-end gap-2">
                                @can('update_resignation')
                                    <a href="{{route('admin.resignation.edit',$value->id)}}" class="btn-action edit" title="Edit">
                                        <i data-feather="edit-3"></i>
                                    </a>
                                @endcan
                                
                                @can('delete_resignation')
                                    <a data-href="{{route('admin.resignation.delete',$value->id)}}" class="btn-action delete deleteBranch cursor-pointer" title="Delete">
                                        <i data-feather="trash-2"></i>
                                    </a>
                                @endcan
                            </div>
                        @endif
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
        {{ $resignationLists->appends($_GET)->links() }}
    </div>
</section>

@include('admin.resignation.common.status_update')
@endsection

@section('scripts')
    @include('admin.resignation.common.scripts')
    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection