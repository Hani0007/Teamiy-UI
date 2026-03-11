@extends('layouts.master')

@section('title', __('index.create_notice'))

@section('button')
    <a href="{{ route('admin.notices.index') }}">
        <button class="btn btn-sm btn-primary">
            <i class="link-icon" data-feather="arrow-left"></i> @lang('index.back')
        </button>
    </a>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">
    @include('admin.notice.common.breadcrumb')
    {{-- Top Header --}}
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.notice') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">{{ __('index.create') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-bullhorn"></i> Broadcast information to employees
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form id="notification" action="{{ route('admin.notices.store') }}" method="POST">
        @csrf

        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-info-circle"></i>
                </div>
                <div class="section-heading-text">
                    <h4>Notice Content</h4>
                    <p>Provide a title, description and select target receivers</p>
                </div>
            </div>

            <div class="section-divider"></div>

            @include('admin.notice.common.form')
        </div>

        {{-- Footer Actions --}}
        <div class="branch-footer-actions">
            <a href="{{ route('admin.notices.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i> {{ __('index.back') }}
            </a>
            <button type="submit" class="btn btn-primary">
                 @lang('index.send_notice')
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
    @include('admin.notice.common.scripts')
@endsection