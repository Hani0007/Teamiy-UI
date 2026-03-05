@extends('layouts.master')

@section('title', __('index.edit_post_title'))

{{-- Header button section --}}
@section('button')
    <a href="{{ route('admin.posts.index') }}">
        <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.button_back') }}</button>
    </a>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">

    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.edit_post_title') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">{{ __('index.edit') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-user-tag"></i> {{ $postDetail->post_name ?? 'Update Designation' }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form action="{{ route('admin.posts.update', $postDetail->id) }}" 
          method="POST" 
          enctype="multipart/form-data" 
          id="postUpdateForm">
        
        @method('PUT') {{-- Backend safety --}}
        @csrf

        <div class="teamy-main-card">

            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-edit"></i>
                </div>
                <div class="section-heading-text">
                    <h4>{{ __('index.edit') }} Designation Details</h4>
                    <p>Modify the information below to update designation records</p>
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
                {{ __('index.update') }}
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