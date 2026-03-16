@extends('layouts.master')

@section('title', __('index.edit_branch'))

@section('button')
    <a href="{{ route('admin.branch.index') }}">
        <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.button_back') }}</button>
    </a>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@6.11.0/css/flag-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.5.5/build/css/intlTelInput.css">
<style>
    /* Create page wale styles for consistency */
    #country_code + .select2 .select2-selection--single { height: 38px; display: flex; align-items: center; }
    #country_code + .select2 .select2-selection__rendered { padding-left: 8px; padding-right: 28px; display: flex; align-items: center; line-height: 38px; }
    #country_code + .select2 .select2-selection__arrow { right: 8px; top: 50%; transform: translateY(-50%); }
    #country_code + .select2 .fi { margin-right: 10px; vertical-align: middle; }
</style>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">

    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.edit_branch') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;position: relative;top: 0;right: 0;">{{ __('index.edit') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-code-branch"></i> {{ $branch->name ?? 'Update Branch' }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form action="{{ route('admin.branch.update', $branch->id) }}" 
          method="POST" 
          enctype="multipart/form-data" 
          id="branchForm">
        @method('PUT')
        @csrf

        <div class="teamy-main-card">
            
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-edit"></i>
                </div>
                <div class="section-heading-text">
                    <h4>{{ __('index.edit') }} Branch Details</h4>
                    <p>Modify the information below to update branch records</p>
                </div>
            </div>

            <div class="section-divider"></div>

            <div class="row">
                @include('admin.branch.common.form')
            </div>
        </div>

        <div class="branch-footer-actions">
            <a href="{{ route('admin.branch.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i>
                {{ __('index.button_back') }}
            </a>
            <button type="submit" class="btn btn-primary">
                {{ __('index.update') }}
            </button>
        </div>

    </form>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.5.5/build/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection