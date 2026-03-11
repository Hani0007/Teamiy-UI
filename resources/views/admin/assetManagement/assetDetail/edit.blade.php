{{--@extends('layouts.master')
@section('title', __('index.asset'))
@section('action', __('index.edit'))
@section('main-content')

    <section class="content">
        @include('admin.section.flash_message')
        @include('admin.assetManagement.assetDetail.common.breadcrumb')
        <div class="card">
            <div class="card-body">
                <form id="" class="forms-sample" action="{{route('admin.assets.update',$assetDetail->id)}}" enctype="multipart/form-data"  method="post">
                    @method('PUT')
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
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">{{ __('index.edit') }}</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-tag"></i> {{ $assetDetail->name ?? 'Update Asset' }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form action="{{ route('admin.assets.update', $assetDetail->id) }}" 
          method="POST" 
          enctype="multipart/form-data" 
          id="assetEditForm">
        @method('PUT')
        @csrf

        <div class="teamy-main-card">
            
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-edit"></i>
                </div>
                <div class="section-heading-text">
                    <h4>{{ __('index.edit') }} Asset Details</h4>
                    <p>Modify asset specifications, warranty, and availability</p>
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
                {{ __('index.update') }} {{ __('index.asset') }}
            </button>
        </div>

    </form>
</div>

@endsection

@section('scripts')
    @include('admin.assetManagement.assetDetail.common.form_scripts')
@endsection