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

<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0;">{{ __('index.holiday') }}</h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px;">
                <i data-feather="calendar" style="width: 14px; vertical-align: middle;"></i> Annual Events & Holidays
            </p>
        </div>
        
        <div class="d-flex gap-2">
            @can('import_holiday')
                <a href="{{ route('admin.holidays.import-csv.show') }}" style="text-decoration: none;">
                    <button class="btn btn-outline-info d-flex align-items-center gap-2" style="border-radius: 12px; padding: 10px 18px; font-weight: 600;">
                        <i data-feather="upload-cloud" style="width: 18px;"></i>
                        <span class="d-none d-sm-inline">@lang('index.import_holiday_csv')</span>
                    </button>
                </a>
            @endcan

            @can('create_holiday')
                <a href="{{ route('admin.holidays.create') }}" style="text-decoration: none;">
                    <button class="btn-premium-add">
                        <i data-feather="plus" style="width: 20px;"></i>
                        <span>@lang('index.add_holiday')</span>
                    </button>
                </a>
            @endcan
        </div>
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
                                    <a data-href="{{ route('admin.holidays.delete', $value->id) }}" class="btn-header-action deleteHoliday cursor-pointer" title="Delete">
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
        background: rgba(255, 255, 255, 0.2);
        color: white;
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
                    confirmButtonColor: '#ef4444'
                }).then((result) => {
                    if (result.isConfirmed) window.location.href = href;
                })
            });
        });
    </script>
@endsection