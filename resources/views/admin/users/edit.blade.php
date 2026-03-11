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
                    <span class="status-badge" style="background: #fff7ed; color: #f97316;">{{ __('index.edit') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-user-edit"></i> {{ $userDetail->name }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    {{-- Main Form Card --}}
    <form class="forms-sample" id="employeeDetail" action="{{ route('admin.users.update', $userDetail->id) }}" enctype="multipart/form-data" method="POST">
        @method('PUT')
        @csrf
        
        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-sync-alt"></i>
                </div>
                <div class="section-heading-text">
                    <h4>Update User Profile</h4>
                    <p>Modify account settings and update user permissions</p>
                </div>
            </div>

            <div class="section-divider"></div>

            {{-- Common Form Fields (Idhar sirf inputs honge) --}}
            @include('admin.users.common.form')
        </div>

        {{-- Footer Actions: Isme Back aur Update buttons honge --}}
        <div class="branch-footer-actions">
            <a href="{{ route('admin.users.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i> {{ __('index.back') }}
            </a>
            <button type="submit" class="btn btn-primary">
                 {{ __('index.update_user') }}
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
    @include('admin.users.common.scripts')
@endsection