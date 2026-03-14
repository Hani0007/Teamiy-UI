@extends('layouts.master')

@section('title', 'Permission')

@section('styles')
    <style>
        /* Teamy Style for Bootstrap Select */
        .bootstrap-select .btn {
            background-color: #fff !important;
            border: 2px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 0.6rem 1rem !important;
            height: auto !important;
        }
        .bootstrap-select .filter-option-inner-inner {
            color: #475569 !important;
            font-weight: 500;
        }
    </style>
@endsection

@section('main-content')
<section class="content">
    @include('admin.section.flash_message')
    @include('admin.permission.common.breadcrumb')
    {{-- Blue Header --}}
    <div class="teamy-top-header">
        <div>
            <h2>Permission Management</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #fb8233; position: static;">@lang('index.create')</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-plus-circle"></i> Add new system-wide permission
                </div>
            </div>
        </div>
    </div>

    <div class="teamy-main-card">
        <div class="card-body pb-0">
            <form class="forms-sample" action="{{route('admin.permissions.store')}}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="guard" value="{{ $guard }}" />

                @include('admin.permission.common.form')

                {{-- Footer Actions --}}
                <div class="branch-footer-actions">
                    <a href="{{route('admin.permissions.index')}}" class="branch-back-btn">
                        <i class="fa fa-arrow-left"></i> @lang('index.back')
                    </a>
                    <button type="submit" class="btn btn-primary">
                         @lang('index.create') Permission
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection