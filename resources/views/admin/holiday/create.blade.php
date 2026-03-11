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
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">{{ __('index.create') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-plus-circle"></i> Add New Holiday
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form action="{{ route('admin.holidays.store') }}" 
          method="POST" 
          id="holidayForm">
        @csrf

        <div class="teamy-main-card">
            
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-calendar-plus"></i>
                </div>
                <div class="section-heading-text">
                    <h4>{{ __('index.create') }} Holiday</h4>
                    <p>Enter the details below to add a new holiday to the calendar</p>
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
                {{ __('index.create') }} {{ __('index.holiday') }}
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