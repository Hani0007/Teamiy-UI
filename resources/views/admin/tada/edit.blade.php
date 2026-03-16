{{--
@extends('layouts.master')

@section('title',__('index.tada'))

@section('action',__('index.edit'))

@section('styles')
    <link rel="stylesheet" href="{{asset('assets/css/imageuploadify.min.css')}}">
@endsection

@section('button')
    <a href="{{route('admin.tadas.index')}}" >
        <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.back') }}</button>
    </a>
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.tada.common.breadcrumb')

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="editForm" class="forms-sample" action="{{route('admin.tadas.update',$tadaDetail->id)}}" enctype="multipart/form-data" method="POST">
                            @method('PUT')
                            @csrf
                            @include('admin.tada.common.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    @include('admin.tada.common.scripts')
@endsection
--}}

@extends('layouts.master')

@section('title', __('index.tada'))

@section('button')
    <a href="{{ route('admin.tadas.index') }}">
        <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.back') }}</button>
    </a>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{asset('assets/css/imageuploadify.min.css')}}">
@endsection

@section('main-content')
<div class="teamy-body-wrapper">

    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.edit') }} TADA</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge bg-warning text-dark text-white" style="position: relative;top: 0;right: 0;background:#E3823F !important">Editing</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-file-invoice-dollar"></i> TADA ID: #{{$tadaDetail->id}}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form action="{{ route('admin.tadas.update', $tadaDetail->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
        @method('PUT')
        @csrf

        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa fa-edit"></i>
                </div>
                <div class="section-heading-text">
                    <h4>TADA Information</h4>
                    <p>Update existing TADA record details</p>
                </div>
            </div>

            <div class="section-divider"></div>

            @include('admin.tada.common.form')
        </div>

        <div class="branch-footer-actions">
            <a href="{{ route('admin.tadas.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i> {{ __('index.back') }}
            </a>
            <button type="submit" class="btn btn-primary"> {{ __('index.update') }} </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    @include('admin.tada.common.scripts')
@endsection