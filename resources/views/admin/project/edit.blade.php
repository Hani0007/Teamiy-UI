@extends('layouts.master')

@section('title', __('index.edit_project'))

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/imageuploadify.min.css') }}">
@endsection

@section('button')
    <a href="{{ route('admin.projects.index') }}">
        <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.button_back') }}</button>
    </a>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.project') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">{{ __('index.edit') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-tasks"></i> {{ $projectDetail->name }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form action="{{ route('admin.projects.update', $projectDetail->id) }}" 
          method="POST" 
          enctype="multipart/form-data" 
          id="projectEdit">
        @method('PUT')
        @csrf

        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-edit"></i>
                </div>
                <div class="section-heading-text">
                    <h4>{{ __('index.edit_project') }}</h4>
                    <p>Modify project parameters and assigned team members</p>
                </div>
            </div>

            <div class="section-divider"></div>

            @include('admin.project.common.form')
            
        </div>

        <div class="branch-footer-actions">
            <a href="{{ route('admin.projects.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i>
                {{ __('index.button_back') }}
            </a>
            <div>
                <button type="submit" class="btn btn-primary me-2">
                    {{ __('index.update') }}
                </button>
                <button type="submit" id="withProjectNotification" class="btn btn-primary">
                    {{ __('index.update_send') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    @include('admin.project.common.form_scripts')
@endsection