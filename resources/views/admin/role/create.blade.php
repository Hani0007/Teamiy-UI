@extends('layouts.master')

@section('title',__('index.role'))

@section('main-content')

    <section class="content">
        @include('admin.section.flash_message')
        @include('admin.role.common.breadcrumb')

        {{-- Blue Header --}}
        <div class="teamy-top-header">
            <div>
                <h2>{{ __('index.role') }}</h2>
                <div class="header-info-row">
                    <div class="header-info-item">
                        <span class="status-badge" style="background: #eef2ff; color: #fb8233; position: static;">{{ __('index.create') }}</span>
                    </div>
                    <div class="header-info-item">
                        <i class="fa fa-shield-alt"></i> Create new system access levels
                    </div>
                </div>
            </div>
        </div>

        <div class="teamy-main-card">
            <div class="card-body pb-0">
                <form class="forms-sample" action="{{route('admin.roles.store')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @include('admin.role.common.form')

                    {{-- Footer Actions --}}
                    <div class="branch-footer-actions">
                        <a href="{{route('admin.roles.index')}}" class="branch-back-btn">
                            <i class="fa fa-arrow-left"></i> @lang('index.back')
                        </a>
                        <button type="submit" class="btn btn-primary"> 
                             {{ __('index.create') }} @lang('index.role')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection