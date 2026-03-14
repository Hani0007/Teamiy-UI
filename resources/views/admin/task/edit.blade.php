{{--@extends('layouts.master')

@section('title', __('index.edit_task'))

@section('action', __('index.edit_task_detail'))

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/imageuploadify.min.css') }}">
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.task.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <form id="projectEdit" class="forms-sample" action="{{ route('admin.tasks.update', $taskDetail->id) }}" enctype="multipart/form-data" method="post">
                    @method('PUT')
                    @csrf
                    @include('admin.task.common.form')
                </form>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    @include('admin.task.common.scripts')
@endsection
--}}

@extends('layouts.master')

@section('title', __('index.edit_task'))

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/imageuploadify.min.css') }}">
@endsection

@section('button')
    <a href="{{ route('admin.tasks.index') }}">
        <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.button_back') }}</button>
    </a>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.tasks') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;position: relative;top: 0;right: 0;">{{ __('index.edit') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-tasks"></i> {{ $taskDetail->name }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form action="{{ route('admin.tasks.update', $taskDetail->id) }}" 
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
                    <h4>{{ __('index.edit_task_detail') }}</h4>
                    <p>Update task assignments, status, and attached documents</p>
                </div>
            </div>

            <div class="section-divider"></div>

            @include('admin.task.common.form')
            
        </div>

        {{-- Footer Actions --}}
        <div class="branch-footer-actions">
            <a href="{{ route('admin.tasks.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i>
                {{ __('index.button_back') }}
            </a>
            <div>
                <button type="submit" class="btn btn-primary me-2">
                    {{ __('index.update_task') }}
                </button>
                <button type="submit" id="withTaskNotification" class="btn btn-primary">
                    {{ __('index.update_send') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    @include('admin.task.common.scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') { feather.replace(); }
        });
    </script>
@endsection