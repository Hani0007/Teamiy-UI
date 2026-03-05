
@extends('layouts.master')

@section('title', 'Permisison')

@section('action',__('index.create'))

@section('styles')
    <style>
        .bootstrap-select .btn,
        .bootstrap-select .btn:focus,
        .bootstrap-select .btn:active {
            background-color: #fff !important;
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
            color: #212529 !important;
            padding: 0.475rem 0.75rem !important;
            height: calc(2.49rem + 2px) !important;
            box-shadow: none !important;
        }

        /* Fix the placeholder "Nothing selected" text */
        .bootstrap-select .filter-option-inner-inner {
            color: #6c757d !important; /* muted like placeholder in form-control */
        }
    </style>
@endsection

@section('button')
    <a href="{{route('admin.permissions.index')}}" >
        <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> @lang('index.back')</button>
    </a>
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.permission.common.breadcrumb')

        <div class="card">
            <div class="card-body pb-0">
                <form class="forms-sample" action="{{route('admin.permissions.store')}}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <input type="hidden" name="guard" value="{{ $guard }}" />

                    @include('admin.permission.common.form')
                </form>
            </div>
        </div>

    </section>
@endsection
