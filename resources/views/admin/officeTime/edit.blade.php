@extends('layouts.master')

@section('title', __('index.office_time'))

@section('main-content')

<div class="teamy-body-wrapper">
    @include('admin.officeTime.common.breadcrumb')
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.office_time') }} {{ __('index.edit') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="position: relative;top: 0;right: 0;">Editing</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-clock"></i> Update Schedule
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form class="forms-sample" action="{{ route('admin.office-times.update', $officeTime->id) }}" enctype="multipart/form-data" method="POST">
        @method('PUT')
        @csrf

        <div class="teamy-main-card">

            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-edit"></i>
                </div>
                <div class="section-heading-text">
                    <h4>{{ __('index.office_schedule') }} {{ __('index.details') }}</h4>
                    <p>Modify existing shift timings and rules</p>
                </div>
            </div>

            <div class="section-divider"></div>

            <div class="row">
                {{-- Backend form fields intact --}}
                @include('admin.officeTime.common.form')
            </div>

        </div>

        <div class="branch-footer-actions">

            <a href="{{ route('admin.office-times.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i>
                {{ __('index.back') }}
            </a>

            <button type="submit" class="btn btn-primary">
                {{ __('index.update') }} {{ __('index.office_time') }}
            </button>

        </div>

    </form>

</div>

@endsection

@section('scripts')
    @include('admin.officeTime.common.scripts')
@endsection