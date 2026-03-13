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
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">{{ __('index.edit') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-edit"></i> {{ $teamMeetingDetail->title }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form id="notification" class="forms-sample" action="{{ route('admin.team-meetings.update', $teamMeetingDetail->id) }}" 
          enctype="multipart/form-data" method="POST">
        @method('PUT')
        @csrf

        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon"><i class="fa fa-sync"></i></div>
                <div class="section-heading-text">
                    <h4>Update Meeting</h4>
                    <p>Modify existing meeting details and notify participants</p>
                </div>
            </div>

            <div class="section-divider"></div>

            @include('admin.teamMeeting.common.form')
        </div>

        <div class="branch-footer-actions d-flex justify-content-end">
            
            <div class="btn-group-wrapper">
                <a href="{{ route('admin.team-meetings.index') }}" class="btn branch-back-btn me-2">
                     <i class="fa fa-arrow-left"></i> {{ __('index.back') }}
                </a>
                <button type="submit" class="btn btn-primary me-2">
                    {{ __('index.update') }}
                </button>
                <button type="submit" id="withTeamNotification" class="btn btn-primary">
                     {{ __('index.update_send') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    @include('admin.teamMeeting.common.scripts')
@endsection