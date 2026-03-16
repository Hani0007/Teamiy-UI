@extends('layouts.master')

@section('title', __('index.holiday'))

@section('main-content')
    @php
        if(\App\Helpers\AppHelper::ifDateInBsEnabled()){
            $filterData['min_year'] = '2076'; $filterData['max_year'] = '2089'; $filterData['month'] = 'np';
        } else {
            $filterData['min_year'] = '2020'; $filterData['max_year'] = '2033'; $filterData['month'] = 'en';
        }
    @endphp

<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh;">
    @include('admin.section.flash_message')

    {{-- 1. Modern Breadcrumbs & Top Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.holiday') }}</h2>
            @include('admin.holiday.common.breadcrumb')
        </div>

        <div class="d-flex gap-2">
            @can('import_holiday')
                <a href="{{ route('admin.holidays.import-csv.show') }}" style="text-decoration: none;">
                    <button class="btn btn-outline-primary d-flex align-items-center gap-2" style="border-radius: 12px; padding: 10px 18px; font-weight: 600;">
                        <i data-feather="upload-cloud" style="width: 18px;"></i>
                        <span class="d-none d-sm-inline">@lang('index.import_holiday_csv')</span>
                    </button>
                </a>
            @endcan

            @can('create_holiday')
                <a href="{{ route('admin.holidays.create') }}" style="text-decoration: none;">
                    <button class="btn btn-primary">
                        <i data-feather="plus" style="width: 20px;"></i>
                        <span>@lang('index.add_holiday')</span>
                    </button>
                </a>
            @endcan
        </div>
    </div>

    {{-- 2. Glass-morphism Filter Panel --}}
    <div class="glass-filter-panel mb-5 shadow-sm border-0" style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-radius: 20px; padding: 25px; border: 1px solid #ffffff;">
        <form action="{{ route('admin.holidays.index') }}" method="get" class="row g-3 align-items-end">
            
            <div class="col-lg col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">@lang('index.event_name')</label>
                <input type="text" placeholder="@lang('index.event_name')" id="event" name="event" value="{{ $filterParameters['event'] }}" class="form-control shadow-none" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
            </div>

            <div class="col-lg col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">@lang('index.leave_requested_year')</label>
                <input type="number" min="{{ $filterData['min_year'] }}" max="{{ $filterData['max_year'] }}" step="1"
                       placeholder="e.g : {{ $filterData['min_year'] }}"
                       id="year" name="event_year" value="{{ $filterParameters['event_year'] }}" class="form-control shadow-none" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
            </div>

            <div class="col-lg col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">@lang('month')</label>
                <select class="form-select shadow-none modern-select" name="month" id="month" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                    <option value="" {{ !isset($filterParameters['month']) ? 'selected' : '' }}>@lang('index.all_month')</option>
                    @foreach($months as $key => $value)
                        <option value="{{ $key }}" {{ (isset($filterParameters['month']) && $key == $filterParameters['month'] ) ? 'selected' : '' }}>
                            {{ $value[$filterData['month']] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2 col-md-6">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn w-100" style="background: #057db0; color: white; height: 48px; border-radius: 12px; font-weight: 600; border: none; transition: all 0.3s ease;">
                        @lang('index.filter')
                    </button>
                    <a href="{{ route('admin.holidays.index') }}" class="btn w-100 d-flex align-items-center justify-content-center" style="height: 48px; border: 1px solid #e2e8f0; border-radius: 12px; color: #64748b; background: #fff; font-weight: 600; text-decoration: none;">
                        @lang('index.reset')
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Cards Grid --}}
    <div class="row g-4 justify-content-start">
        @forelse($holidays as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    {{-- Glossy Header with all Actions --}}
                    <div class="card-glossy-header" style="padding-bottom: 15px;">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="gift"></i>
                            </div>
                            <label class="switch-modern">
                                <input class="toggleStatus" href="{{ route('admin.holidays.toggle-status', $value->id) }}"
                                       type="checkbox" {{ ($value->is_active) == 1 ? 'checked' : '' }}>
                                <span class="slider-modern round"></span>
                            </label>
                        </div>
                        
                        <h4 class="branch-name-display text-truncate" title="{{ $value->event }}">{{ ucfirst($value->event) }}</h4>
                        
                        {{-- ID Pill and 3 Action Buttons in Header --}}
                        <div class="d-flex align-items-center justify-content-between position-relative mt-2" style="z-index: 2;">
                            <span class="branch-ref-pill">ID: #{{ $value->id }}</span>
                            
                            <div class="d-flex gap-1">
                                @can('show_holiday')
                                    <a href="javascript:void(0)" id="showHolidayDetail" data-href="{{ route('admin.holidays.show', $value->id) }}" data-id="{{ $value->id }}" 
                                       class="btn-header-action" title="View">
                                        <i data-feather="eye"></i>
                                    </a>
                                @endcan

                                @can('edit_holiday')
                                    <a href="{{ route('admin.holidays.edit', $value->id) }}" class="btn-header-action" title="Edit">
                                        <i data-feather="edit-3"></i>
                                    </a>
                                @endcan

                                @can('delete_holiday')
                                    <a data-href="{{ route('admin.holidays.delete', $value->id) }}" class="btn-header-action deleteHoliday cursor-pointer" title="Delete" style="color:#EF955D">
                                        <i data-feather="trash-2"></i>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>

                    {{-- Simple Card Body (Only Date) --}}
                    <div class="card-white-body" style="padding: 15px 20px;">
                        <div class="info-listing mb-0">
                            <div class="info-item-box border-0 pb-0">
                                <div class="icon-circle"><i data-feather="calendar"></i></div>
                                <div class="text-content">
                                    <small>EVENT DATE</small>
                                    <p class="mb-0">{{ \App\Helpers\AppHelper::formatDateForView($value->event_date) }}</p>
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
                    <p class="text-muted mt-3">@lang('index.no_records_found')</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $holidays->appends($_GET)->links() }}
    </div>
</section>

<style>
    /* Teeno buttons ke liye special glassy style */
    .btn-header-action {
        background: #FFFF;
        color: #057DB0;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .btn-header-action:hover {
        background: rgba(255, 255, 255, 0.4);
        color: #fff;
        transform: translateY(-2px);
    }
    .btn-header-action svg {
        width: 14px;
        height: 14px;
    }
</style>

@include('admin.holiday.show')
@endsection

@section('scripts')
    @include('admin.holiday.common.scripts')
    <script>
        $(document).ready(function() {
            feather.replace();

            // Toggle Status Logic
            $('.toggleStatus').change(function (event) {
                event.preventDefault();
                var status = $(this).prop('checked') === true ? 1 : 0;
                var href = $(this).attr('href');
                Swal.fire({
                    title: 'Update Status?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    confirmButtonColor: '#057db0'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    } else {
                        $(this).prop('checked', !status);
                    }
                })
            });

            // Delete Logic
            $('.deleteHoliday').click(function (event) {
                event.preventDefault();
                let href = $(this).data('href');
                Swal.fire({
                    title: 'Delete Holiday?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    confirmButtonColor: '#fb8233'
                }).then((result) => {
                    if (result.isConfirmed) window.location.href = href;
                })
            });
        });
    </script>
@endsection