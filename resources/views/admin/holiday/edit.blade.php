@extends('layouts.master')

@section('title', __('index.holiday'))

@section('button')
    <a href="{{ route('admin.holidays.index') }}">
        <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.back') }}</button>
    </a>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">
    @include('admin.holiday.common.breadcrumb')
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.holiday') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">{{ __('index.edit') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-calendar-alt"></i> {{ $holidayDetail->event ?? 'Update Holiday' }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form action="{{ route('admin.holidays.update', $holidayDetail->id) }}" 
          method="POST" 
          id="holidayForm">
        @method('PUT')
        @csrf

        <div class="teamy-main-card">
            
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-edit"></i>
                </div>
                <div class="section-heading-text">
                    <h4>{{ __('index.edit') }} Holiday Details</h4>
                    <p>Modify the information below to update the holiday schedule</p>
                </div>
            </div>

            <div class="section-divider"></div>

            @include('admin.holiday.common.form')
            
        </div>

        <div class="branch-footer-actions">
            <a href="{{ route('admin.holidays.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i>
                {{ __('index.back') }}
            </a>
            <button type="submit" class="btn btn-primary">
                {{ __('index.update') }} {{ __('index.holiday') }}
            </button>
        </div>

    </form>
</div>

@endsection

@section('scripts')
    @include('admin.holiday.common.scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') { feather.replace(); }
        });
    </script>
@endsection