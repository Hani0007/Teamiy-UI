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
                    <span class="status-badge" style="background: #fff7ed; color: #f97316;">{{ __('index.edit') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-tools"></i> Configuration for: {{ $routerDetail->router_ssid }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form class="forms-sample" action="{{ route('admin.routers.update', $routerDetail->id) }}" enctype="multipart/form-data" method="POST">
        @method('PUT')
        @csrf
        
        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-edit"></i>
                </div>
                <div class="section-heading-text">
                    <h4>Update Router</h4>
                    <p>Modify network credentials for the selected branch</p>
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
                {{ __('index.update') }} @lang('index.router')
            </button>
        </div>
    </form>
</div>

@endsection