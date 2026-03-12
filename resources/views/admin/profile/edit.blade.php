@extends('layouts.master')

@section('title', __('index.edit_user_detail'))

@section('action', __('index.edit'))

@section('button')
    <a href="{{ route('admin.employees.index') }}">
        <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.back') }}</button>
    </a>
@endsection

@section('main-content')

{{-- Teamy Wrapper: Jo aapki theme ka main part hai --}}
<div class="teamy-body-wrapper">
    @include('admin.section.flash_message')
    <nav class="page-breadcrumb d-flex align-items-center justify-content-between">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('index.dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">{{ __('index.admin_section') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">@yield('action')</li>
        </ol>
    </nav>

    {{-- Teamy Top Header: Branch Edit jaisa --}}
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.edit_user_detail') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">@yield('action')</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-user-circle"></i> {{ $userDetail->name }}
                </div>
            </div>
        </div>
    </div>

    <form class="forms-sample" id="employeeDetail" action="{{ route('admin.profile_update', $userDetail->id) }}"
        enctype="multipart/form-data" method="POST">
        @csrf

        {{-- Teamy Main Card --}}
        <div class="teamy-main-card">
            
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-user-edit"></i>
                </div>
                <div class="section-heading-text">
                    <h4>{{ __('index.personal_detail') }}</h4>
                    <p>Modify your personal information below</p>
                </div>
            </div>

            <div class="section-divider"></div>

            {{-- Avatar Section --}}
            <div class="d-md-flex align-items-center text-md-start text-center mb-4 ps-2">
                @php
                    $avatarPath = \App\Models\Admin::AVATAR_UPLOAD_PATH . ($userDetail->avatar ?? '');
                    $avatar = (!empty($userDetail->avatar) && file_exists(public_path($avatarPath)))
                                ? asset($avatarPath)
                                : asset('assets/images/img.png');
                @endphp

                <div class="position-relative">
                    <img id="avatarPreview" class="wd-100 ht-100 rounded-circle border shadow-sm"
                        style="object-fit: cover; cursor:pointer;"
                        src="{{ $avatar }}"
                        alt="profile">
                    
                    <input type="file" name="avatar" id="avatar" accept="image/*" hidden>
                    
                    <span id="chooseFileText" class="ms-2" style="cursor:pointer; color:#6366f1; font-weight: 600; font-size: 13px;">
                        <i class="fa fa-camera me-1"></i> Change Photo
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="name" class="form-label fw-bold"> {{ __('index.name') }} <span style="color: red">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ ( isset($userDetail) ? $userDetail->name: old('name') )}}"
                        autocomplete="off" placeholder="{{ __('index.enter_name') }}" required>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="username" class="form-label fw-bold">{{ __('index.username') }} <span style="color: red">*</span></label>
                    <input type="text" class="form-control" id="username" name="username"
                        value="{{ ( isset($userDetail) ? $userDetail->username: '' )}}" >
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="email" class="form-label fw-bold">Email <span style="color: red">*</span></label>
                    <input type="email" class="form-control bg-light" id="email"
                        value="{{ ( isset($userDetail) ? $userDetail->email: '' )}}" disabled style="cursor: not-allowed;">
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="role" class="form-label fw-bold">{{ __('index.role') }} <span style="color: red">*</span></label>
                    <input type="text" class="form-control bg-light"
                        value="{{ $userDetail->getRoleNames()->first() }}" disabled style="cursor: not-allowed;">
                </div>
            </div>
        </div>

        {{-- Teamy Style Footer Buttons --}}
        <div class="branch-footer-actions">
            <a href="{{ route('admin.employees.index') }}" class="branch-back-btn text-decoration-none">
                <i class="fa fa-arrow-left"></i> {{ __('index.back') }}
            </a>
            <button type="submit" class="btn btn-primary px-4">
                {{ __('index.update_user') }}
            </button>
        </div>

    </form>
</div>

@endsection

@section('scripts')
<script>
    // Manual Trigger for File Input
    document.getElementById('chooseFileText').addEventListener('click', function () {
        document.getElementById('avatar').click();
    });
    document.getElementById('avatarPreview').addEventListener('click', function () {
        document.getElementById('avatar').click();
    });

    // Image Preview Logic
    document.getElementById('avatar').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('avatarPreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Feather Icons Load
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
@endsection
