@extends('layouts.master')

@section('title', __('index.create_post_title'))

{{-- Header Button Section --}}
@section('button')
    <a href="{{ route('admin.posts.index') }}">
        <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.button_back') }}</button>
    </a>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">
    <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('index.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">{{ __('index.post_section') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('index.create') }}</li>
            </ol>

    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.create_post_title') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;position: relative;top: 0;right: 0;">{{ __('index.create') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-briefcase"></i> {{ __('index.post_section') }} Setup
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form action="{{ route('admin.posts.store') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          id="postForm">
        @csrf

        <div class="teamy-main-card">
            
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-plus-circle"></i>
                </div>
                <div class="section-heading-text">
                    <h4>Add New Designation</h4>
                    <p>Enter the details below to create a new post/designation record</p>
                </div>
            </div>

            <div class="section-divider"></div>

            <div class="row">
                @include('admin.post.common.form')
            </div>
        </div>

        <div class="branch-footer-actions">
            <a href="{{ route('admin.posts.index') }}" class="branch-back-btn text-decoration-none">
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
    @include('admin.post.common.scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection