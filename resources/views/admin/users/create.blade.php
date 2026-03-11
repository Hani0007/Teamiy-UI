@extends('layouts.master')

@section('title', __('index.users'))

@section('button')
    <div class="float-end">
        <a href="{{ route('admin.users.index') }}">
            <button class="btn btn-sm btn-primary">
                <i class="link-icon" data-feather="arrow-left"></i> {{ __('index.back') }}
            </button>
        </a>
    </div>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">

    {{-- Blue Header (Teamy Top Header) --}}
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.users') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">{{ __('index.add') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-user-plus"></i> Register a new system user
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    {{-- Main Form Card --}}
    <form class="forms-sample" id="employeeDetail" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        
        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-id-card"></i>
                </div>
                <div class="section-heading-text">
                    <h4>Personal & Account Information</h4>
                    <p>Fill in the details below to create a new user profile</p>
                </div>
            </div>

            <div class="section-divider"></div>

            {{-- Yahan aapka common form load hoga --}}
            @include('admin.users.common.form')
        </div>

        {{-- Footer Actions (Back and Save buttons) --}}
        <div class="branch-footer-actions">
            <a href="{{ route('admin.users.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i> {{ __('index.back') }}
            </a>
            <button type="submit" class="btn btn-primary">
                {{ __('index.create_user') }}
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
    @include('admin.users.common.scripts')
@endsection