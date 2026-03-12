@extends('layouts.master')

@section('title', 'Permission')

@section('main-content')
<section class="content">
    @include('admin.section.flash_message')

    {{-- Blue Header --}}
    <div class="teamy-top-header">
        <div>
            <h2>Permission Management</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #fff7ed; color: #f97316;">@lang('index.edit')</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-edit"></i> Editing: <strong>{{ $permission->name }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="teamy-main-card">
        <div class="card-body pb-0">
            <form class="forms-sample" action="{{route('admin.permissions.update', $permission->id)}}" enctype="multipart/form-data" method="post">
                @method('PUT')
                @csrf
                <input type="hidden" name="guard" value="{{ $permission->guard_name }}" />

                @include('admin.permission.common.form')

                {{-- Footer Actions --}}
                <div class="branch-footer-actions">
                    <a href="{{route('admin.permissions.index')}}" class="branch-back-btn">
                        <i class="fa fa-arrow-left"></i> @lang('index.back')
                    </a>
                    <button type="submit" class="btn btn-primary">
                        @lang('index.update') Permission
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection