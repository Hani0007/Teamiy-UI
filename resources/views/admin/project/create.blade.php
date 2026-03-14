@extends('layouts.master')

@section('title', __('index.create_project'))

@section('styles')
    <link rel="stylesheet" href="{{asset('assets/css/imageuploadify.min.css')}}">
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
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;position: relative;top: 0;right: 0;">{{ __('index.create') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-plus-circle"></i> Add New Project
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form action="{{ route('admin.projects.store') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          id="projectManagement">
        @csrf

        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-briefcase"></i>
                </div>
                <div class="section-heading-text">
                    <h4>{{ __('index.create_project') }}</h4>
                    <p>Fill in the details below to initialize a new company project</p>
                </div>
            </div>

            <div class="section-divider"></div>

            @include('admin.project.common.form')
            
        </div>

        {{-- Footer Actions --}}
        <div class="branch-footer-actions">
            <a href="{{ route('admin.projects.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i>
                {{ __('index.button_back') }}
            </a>
            <div>
                <button type="submit" class="btn btn btn-primary me-2">
                    {{ __('index.create') }}
                </button>
                <button type="submit" id="withProjectNotification" class="btn btn-primary">
                    {{ __('index.create_send') }}
                </button>
            </div>
        </div>
    </form>
</div>

@include('admin.project.common.client_form_model')

@endsection

@section('scripts')
    @include('admin.project.common.form_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') { feather.replace(); }
        });
    </script>
@endsection