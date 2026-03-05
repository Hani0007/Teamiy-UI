@extends('layouts.master')

@section('title', __('index.termination'))

@section('button')
    <a href="{{ route('admin.termination.index') }}">
        <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.button_back') }}</button>
    </a>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">

    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.termination') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">{{ __('index.create') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-plus-circle"></i> Add New Record
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form action="{{ route('admin.termination.store') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          id="terminationForm">
        @csrf

        <div class="teamy-main-card">
            
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-user-times"></i>
                </div>
                <div class="section-heading-text">
                    <h4>{{ __('index.create') }} Termination</h4>
                    <p>Enter the details below to create a new termination record</p>
                </div>
            </div>

            <div class="section-divider"></div>

            @include('admin.terminationManagement.termination.common.form')
            
        </div>

        <div class="branch-footer-actions">
            <a href="{{ route('admin.termination.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i>
                {{ __('index.button_back') }}
            </a>
            <button type="submit" class="btn btn-primary">
                {{ __('index.create') }}
            </button>
        </div>

    </form>
</div>

@endsection

@section('scripts')
    @include('admin.terminationManagement.termination.common.scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') { feather.replace(); }
        });
    </script>
@endsection