{{--@extends('layouts.master')

@section('title', __('index.asset'))

@section('action', __('index.create'))

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.assetManagement.assetDetail.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <form id="" class="forms-sample" action="{{route('admin.assets.store')}}" enctype="multipart/form-data"  method="POST">
                    @csrf
                    @include('admin.assetManagement.assetDetail.common.form')
                </form>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    @include('admin.assetManagement.assetDetail.common.form_scripts')
@endsection
--}}

@extends('layouts.master')

@section('title', __('index.asset'))

@section('button')
    <a href="{{ route('admin.assets.index') }}">
        <button class="btn btn-sm btn-primary">
            <i class="link-icon" data-feather="arrow-left"></i> {{ __('index.back') }}
        </button>
    </a>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">
    @include('admin.assetManagement.assetDetail.common.breadcrumb')
    {{-- Top Header --}}
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.asset') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">{{ __('index.create') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-plus-circle"></i> Add New Asset Detail
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form action="{{ route('admin.assets.store') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          id="assetCreateForm">
        @csrf

        <div class="teamy-main-card">
            
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-boxes"></i>
                </div>
                <div class="section-heading-text">
                    <h4>Asset Information</h4>
                    <p>Enter general details and purchase information of the asset</p>
                </div>
            </div>

            <div class="section-divider"></div>

            @include('admin.assetManagement.assetDetail.common.form')
            
        </div>

        {{-- Footer Actions --}}
        <div class="branch-footer-actions">
            <a href="{{ route('admin.assets.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i> {{ __('index.back') }}
            </a>
            <button type="submit" class="btn btn-primary">
                {{ __('index.create') }} {{ __('index.asset') }}
            </button>
        </div>

    </form>
</div>

@endsection

@section('scripts')
    @include('admin.assetManagement.assetDetail.common.form_scripts')
@endsection