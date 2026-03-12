
@extends('layouts.master')

@section('title', __('index.office_time'))

@section('main-content')

<div class="teamy-body-wrapper">
    @include('admin.officeTime.common.breadcrumb')
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.office_schedule') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">New</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-clock"></i> Schedule Setup
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form class="forms-sample" action="{{ route('admin.office-times.store') }}" enctype="multipart/form-data" method="POST">
        @csrf

        <div class="teamy-main-card">

            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-calendar-alt"></i>
                </div>
                <div class="section-heading-text">
                    <h4>Office Timing Information</h4>
                    <p>Enter office timing details below</p>
                </div>
            </div>

            <div class="section-divider"></div>

            <div class="row">
                @include('admin.officeTime.common.form')
            </div>

        </div>

        <div class="branch-footer-actions">

            <a href="{{ route('admin.office-times.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i>
                {{ __('index.back') }}
            </a>

            <button type="submit" class="btn btn-primary">
                {{ __('index.create') }} {{ __('index.office_time') }}
            </button>

        </div>

    </form>

</div>

@endsection

@section('scripts')
    @include('admin.officeTime.common.scripts')
@endsection