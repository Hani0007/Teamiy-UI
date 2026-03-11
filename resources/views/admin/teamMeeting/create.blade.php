@extends('layouts.master')

@section('title', __('index.team_meeting'))

@section('button')
    <a href="{{ route('admin.team-meetings.index') }}">
        <button class="btn btn-sm btn-primary">
            <i class="link-icon" data-feather="arrow-left"></i> {{ __('index.back') }}
        </button>
    </a>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">
    @include('admin.teamMeeting.common.breadcrumb')
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.team_meeting') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">{{ __('index.create') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-users"></i> Schedule New Team Interaction
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form id="notification" class="forms-sample" action="{{ route('admin.team-meetings.store') }}" 
          enctype="multipart/form-data" method="POST">
        @csrf

        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon"><i class="fa fa-calendar-alt"></i></div>
                <div class="section-heading-text">
                    <h4>Meeting Details</h4>
                    <p>Enter the venue, time, and select departments/participants</p>
                </div>
            </div>

            <div class="section-divider"></div>

            @include('admin.teamMeeting.common.form')
        </div>

        {{-- Footer Actions with Dual Submit --}}
        <div class="branch-footer-actions d-flex justify-content-between">
            <a href="{{ route('admin.team-meetings.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i> {{ __('index.back') }}
            </a>
            <div class="btn-group-wrapper">
                <button type="submit" class="btn btn-secondary me-2">
                    {{ __('index.create') }}
                </button>
                <button type="submit" id="withTeamNotification" class="btn btn-primary">
                    <i class="fa fa-paper-plane me-1"></i> {{ __('index.create_send') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    @include('admin.teamMeeting.common.scripts')
@endsection