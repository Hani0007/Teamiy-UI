@extends('layouts.master')

@section('title', __('index.edit_department'))

<!--{{-- Force remove any header button from master --}}
@section('button')
    <a href="{{ route('admin.departments.index') }}">
        <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.button_back') }}</button>
    </a>
@endsection-->

@section('main-content')

<div class="teamy-body-wrapper">

    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.edit_department') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">Edit</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-edit"></i> Update Department
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')
    
    <!--{{-- Original Breadcrumb --}}
    @include('admin.department.common.breadcrumb', ['title' => __('index.edit')])-->

    <form action="{{ route('admin.departments.update', $departmentsDetail->id) }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="forms-sample">
        @method('PUT')
        @csrf

        <div class="teamy-main-card">

            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-sitemap"></i>
                </div>
                <div class="section-heading-text">
                    <h4>Edit Information</h4>
                    <p>Modify department details below</p>
                </div>
            </div>

            <div class="section-divider"></div>

            <div class="row">
                {{-- Keeping your original form fields inclusion --}}
                @include('admin.department.common.form')
            </div>

        </div>

        <div class="branch-footer-actions">
            <a href="{{ route('admin.departments.index') }}" class="branch-back-btn">
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
    {{-- Original script inclusion --}}
    @include('admin.department.common.form_script')
@endsection
