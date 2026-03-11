@extends('layouts.master')

@section('title', __('index.router'))

@section('main-content')

<div class="teamy-body-wrapper">
    @include('admin.router.common.breadcrumb')
    {{-- Blue Header --}}
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.router') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">{{ __('index.create') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-wifi"></i> Setup network access for attendance
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form class="forms-sample" action="{{ route('admin.routers.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        
        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-network-wired"></i>
                </div>
                <div class="section-heading-text">
                    <h4>@lang('index.router_detail')</h4>
                    <p>Enter the BSSID/SSID details to restrict employee check-ins</p>
                </div>
            </div>

            <div class="section-divider"></div>

            @include('admin.router.common.form')
        </div>

        {{-- Footer Actions --}}
        <div class="branch-footer-actions">
            <a href="{{ route('admin.routers.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i> {{ __('index.back') }}
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-1"></i> {{ __('index.add') }} @lang('index.router')
            </button>
        </div>
    </form>
</div>

@endsection