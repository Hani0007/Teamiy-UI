@extends('layouts.master')

@section('title', __('index.resignation'))

@section('button')
    <a href="{{ route('admin.resignation.index') }}">
        <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.button_back') }}</button>
    </a>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">

    {{-- Top Header --}}
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.resignation') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">
                        {{ isset($resignationDetail) ? __('index.edit') : __('index.create') }}
                    </span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-file-alt"></i> 
                    {{ isset($resignationDetail) ? ($resignationDetail->employee->name ?? 'Update') : 'Add New Resignation' }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form action="{{ isset($resignationDetail) ? route('admin.resignation.update', $resignationDetail->id) : route('admin.resignation.store') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          id="resignationForm">
        @csrf
        @if(isset($resignationDetail))
            @method('PUT')
        @endif

        <div class="teamy-main-card">
            
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa {{ isset($resignationDetail) ? 'fa-edit' : 'fa-plus' }}"></i>
                </div>
                <div class="section-heading-text">
                    <h4>{{ isset($resignationDetail) ? __('index.edit') : __('index.create') }} Resignation Details</h4>
                    <p>Enter the employee resignation information and status</p>
                </div>
            </div>

            <div class="section-divider"></div>

            @include('admin.resignation.common.form')
            
        </div>

        {{-- Footer Actions --}}
        <div class="branch-footer-actions">
            <a href="{{ route('admin.resignation.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i>
                {{ __('index.button_back') }}
            </a>
            <button type="submit" class="btn btn-primary">
                {{ isset($resignationDetail) ? __('index.update') : __('index.create') }}
            </button>
        </div>

    </form>
</div>

@endsection

@section('scripts')
    @include('admin.resignation.common.scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') { feather.replace(); }
        });
    </script>
@endsection