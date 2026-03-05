@extends('layouts.master')

@section('title', __('index.office_time'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0;">{{ __('index.office_time') }}</h2>
            <p class="text-muted small mb-0">Overview of office shift timings</p>
        </div>

        @can('create_office_time')
            <a href="{{ route('admin.office-times.create') }}" style="text-decoration: none;">
                <button class="btn-premium-add">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.add_office_time') }}</span>
                </button>
            </a>
        @endcan
    </div>

    <!--{{-- Breadcrumb --}}
    <div class="mb-4">
        @include('admin.officeTime.common.breadcrumb')
    </div>

    <div class="glass-filter-panel mb-5">
        <form action="{{ route('admin.office-times.index') }}" method="get" class="row g-3 align-items-center">
            @if(!isset(auth()->user()->branch_id))
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <select class="form-select modern-select" id="branch_id" name="branch_id">
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
                <select class="form-select modern-select" id="shift_type" name="shift_type">
                    <option selected disabled>{{ __('index.select_shift') }}</option>
                    @foreach($shifts as $type)
                        <option value="{{ $type->value }}" {{ (isset($filterParameters['shift_type']) && $filterParameters['shift_type'] == $type->value) ? 'selected':old('shift_type') }} >{{ ucfirst($type->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6">
                <select class="form-select modern-select" id="category" name="category">
                    <option selected disabled>{{ __('index.select_category') }}</option>
                    @foreach($category as $value)
                        <option value="{{ $value }}" {{ (isset($filterParameters['category']) && $filterParameters['category'] == $value) ? 'selected':old('category') }} >{{ removeSpecialChars($value) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 d-flex gap-2">
                <button type="submit" class="btn-theme-primary w-100 border-0">{{ __('index.filter') }}</button>
                <a href="{{ route('admin.office-times.index') }}" class="btn-theme-outline w-100 text-decoration-none">{{ __('index.reset') }}</a>
            </div>
        </form>
    </div>-->

    <div class="row g-4">
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
                        <h4 class="branch-name-display">{{ ucfirst($value->shift) }} Shift</h4>
                        <span class="branch-ref-pill">{{ removeSpecialChars($value->category) }}</span>
                    </div>

                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle" style="background: #e6fffa; color: #057db0;">
                                    <i data-feather="log-in"></i>
                                </div>
                                <div class="text-content">
                                    <small>{{ __('shift_start_time') }}</small>
                                    <p>{{\App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceView($value->opening_time)}}</p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle" style="background: #fff5f0; color: #fb8233;">
                                    <i data-feather="log-out"></i>
                                </div>
                                <div class="text-content">
                                    <small>{{ __('shift_end_time') }}</small>
                                    <p>{{\App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceView($value->closing_time)}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="stats-footer-box">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="emp-label">#{{ $key + 1 }} Shift Detail</span>
                                
                                <div class="action-dock">
                                    @can('show_office_time')
                                        <a href="javascript:void(0)" id="showOfficeTimeDetail" 
                                           data-href="{{route('admin.office-times.show',$value->id)}}" 
                                           data-id="{{ $value->id }}" class="btn-action edit" title="View">
                                            <i data-feather="eye"></i>
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
                    <i data-feather="calendar" style="width: 50px; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3"><b>{{ __('index.no_records_found') }}</b></p>
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