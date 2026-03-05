
@extends('layouts.master')

@section('title', 'Permission')

@section('action',__('index.edit'))

@section('button')
    <a href="{{route('admin.permissions.index')}}" >
        <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> @lang('index.back')</button>
    </a>
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.role.common.breadcrumb')

        <div class="card">
            <div class="card-body pb-0">
                <form class="forms-sample" action="{{route('admin.permissions.update', $permission->id)}}" enctype="multipart/form-data" method="post">
                    @method('PUT')
                    @csrf

                    <input type="hidden" name="guard" value="{{ $permission->guard_name }}" />

                    @include('admin.permission.common.form')
                </form>
            </div>
        </div>

    </section>
@endsection

