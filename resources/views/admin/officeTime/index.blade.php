@extends('layouts.master')

@section('title', __('index.office_time'))

@section('main-content')
<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh;">
    @include('admin.section.flash_message')

    {{-- Header & Breadcrumbs Integration --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.office_time') }}</h2>
            @include('admin.officeTime.common.breadcrumb')
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px; margin-top: 5px;">
                <i data-feather="clock" style="width: 14px; vertical-align: middle;"></i> Overview of office shift timings
            </p>
            <!--<nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background: transparent; padding: 0; margin-bottom: 8px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: #94a3b8; text-decoration: none; font-size: 12px;">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #057db0; font-size: 12px; font-weight: 600;">{{ __('index.office_time') }}</li>
                </ol>
            </nav>-->
        </div>

        @can('create_office_time')
            <a href="{{ route('admin.office-times.create') }}" style="text-decoration: none;">
                <button class="btn btn-primary">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.add_office_time') }}</span>
                </button>
            </a>
        @endcan
    </div>

    {{-- Premium Glass Filter Panel --}}
    <div class="glass-filter-panel mb-5 shadow-sm border-0" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border-radius: 20px; padding: 25px;">
        <form action="{{ route('admin.office-times.index') }}" method="get" class="row g-3 align-items-end">
            @if(!isset(auth()->user()->branch_id))
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px;">BRANCH</label>
                    <select class="form-select shadow-none" id="branch_id" name="branch_id" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <option {{ !isset($filterParameters['branch_id']) || old('branch_id') ? 'selected': ''}} disabled>{{ __('index.select_branch') }}</option>
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

            <div class="col-xl-3 col-lg-3 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px;">SHIFT TYPE</label>
                <select class="form-select shadow-none" id="shift_type" name="shift_type" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <option selected disabled>{{ __('index.select_shift') }}</option>
                    @foreach($shifts as $type)
                        <option value="{{ $type->value }}" {{ (isset($filterParameters['shift_type']) && $filterParameters['shift_type'] == $type->value) ? 'selected':old('shift_type') }} >{{ ucfirst($type->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px;">CATEGORY</label>
                <select class="form-select shadow-none" id="category" name="category" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <option selected disabled>{{ __('index.select_category') }}</option>
                    @foreach($category as $value)
                        <option value="{{ $value }}" {{ (isset($filterParameters['category']) && $filterParameters['category'] == $value) ? 'selected':old('category') }} >{{ removeSpecialChars($value) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 d-flex gap-2">
                <button type="submit" class="btn-theme-primary w-100 border-0" style="background-color:#057db0; color:#fff; height: 48px; border-radius: 12px; font-weight: 600; transition: all 0.3s ease;">
                    {{ __('index.filter') }}
                </button>
                <a href="{{ route('admin.office-times.index') }}" class="btn-theme-outline w-100 text-decoration-none d-flex align-items-center justify-content-center" 
                   style="height: 48px; border: 1px solid #e2e8f0; border-radius: 12px; color: #64748b; background: #fff; font-weight: 600;">
                    {{ __('index.reset') }}
                </a>
            </div>
        </form>
    </div>

    {{-- Office Time Cards Grid --}}
    <div class="row g-4 justify-content-start">
        @forelse($officeTimes as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    <div class="card-glossy-header">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="clock"></i>
                            </div>
                            <label class="switch-modern">
                                <input class="toggleStatus" href="{{route('admin.office-times.toggle-status',$value->id)}}"
                                       type="checkbox" {{($value->is_active) == 1 ? 'checked' : ''}}>
                                <span class="slider-modern round"></span>
                            </label>
                        </div>
                        <h4 class="branch-name-display text-truncate">{{ ucfirst($value->shift) }} Shift</h4>
                        <span class="branch-ref-pill">{{ removeSpecialChars($value->category) }}</span>
                    </div>

                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle" style="background: #e0f2fe; color: #0ea5e9;">
                                    <i data-feather="log-in" style="width: 16px;"></i>
                                </div>
                                <div class="text-content">
                                    <small class="text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">{{ __('shift_start_time') }}</small>
                                    <p class="fw-bold mb-0" style="color: #1e293b;">{{\App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceView($value->opening_time)}}</p>
                                </div>
                            </div>

                            <div class="info-item-box mt-3">
                                <div class="icon-circle" style="background: #e0f2fe; color: #FB8233;">
                                    <i data-feather="log-out" style="width: 16px;"></i>
                                </div>
                                <div class="text-content">
                                    <small class="text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">{{ __('shift_end_time') }}</small>
                                    <p class="fw-bold mb-0" style="color: #1e293b;">{{\App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceView($value->closing_time)}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="stats-footer-box">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="badge rounded-pill" style="background: #f1f5f9; color: #64748b; font-weight: 500; padding: 6px 12px;">#{{ $key + 1 }} Shift</span>
                                
                                <div class="action-dock">
                                    @can('show_office_time')
                                        <a href="javascript:void(0)" id="showOfficeTimeDetail" 
                                           data-href="{{route('admin.office-times.show',$value->id)}}" 
                                           data-id="{{ $value->id }}" class="btn-action edit" title="View" 
                                           >
                                            <i data-feather="eye" style="width:18px; height:18px"></i>
                                        </a>
                                    @endcan

                                    @can('edit_office_time')
                                        <a href="{{route('admin.office-times.edit',$value->id)}}" class="btn-action edit" title="Edit">
                                            <i data-feather="edit-3"></i>
                                        </a>
                                    @endcan

                                    @can('delete_office_time')
                                        <a href="#" data-href="{{route('admin.office-times.delete',$value->id)}}" class="btn-action delete deleteOfficeTime" title="Delete">
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
                    <i data-feather="clock" style="width: 60px; height: 60px; color: #e2e8f0;"></i>
                    <p class="text-muted mt-3" style="font-size: 16px;"><b>{{ __('index.no_records_found') }}</b></p>
                </div>
            </div>
        @endforelse
    </div>
</section>

@include('admin.officeTime.show')
@endsection

@section('scripts')
    @include('admin.officeTime.common.scripts')
    <script>
        $(document).ready(function() {
            feather.replace();
        });
    </script>
@endsection