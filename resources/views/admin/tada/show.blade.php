{{--@php use App\Models\TadaAttachment; @endphp
@php use App\Helpers\AppHelper; @endphp
@extends('layouts.master')

@section('title',__('index.tada'))

@section('action',__('index.tada_detail'))

@section("styles")
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet"/>
@endsection

<?php
$status = [
    'pending' => 'info',
    'accepted' => 'success',
    'rejected' => 'danger',
];
?>

@section('button')
    <div class="breadcrumb-button float-md-end d-md-flex align-items-center">
        @can('edit_tada')
            @if(AppHelper::checkSuperAdmin() || ($tadaDetail->is_settled == 0))
                <a href="{{route('admin.tadas.edit',$tadaDetail->id)}}">
                    <button class="btn btn-success d-md-flex align-items-center me-2"><i class="link-icon me-1"
                                                                                         data-feather="edit"></i>{{ __('index.edit_tada') }}
                    </button>
                </a>
            @endif
        @endcan

        @can('create_attachment')
            @if(AppHelper::checkSuperAdmin() || ($tadaDetail->is_settled == 0))
                <a href="{{route('admin.tadas.attachment.create',$tadaDetail->id)}}">
                    <button class="btn btn-secondary d-md-flex align-items-center me-2"><i class="link-icon me-1"
                                                                                           data-feather="clipboard"></i>{{ __('index.upload_attachments') }}
                    </button>
                </a>
            @endif
        @endcan

        @can('edit_tada')
            @if(AppHelper::checkSuperAdmin() || ($tadaDetail->is_settled == 0))
                <button class="btn btn-primary d-md-flex align-items-center me-2"
                        id="updateStatus"
                        data-id="{{ $tadaDetail->id }}"
                        data-status="{{($tadaDetail->status)}}"
                        data-title="{{ucfirst($tadaDetail->title)}}"
                        data-reason="{{($tadaDetail->remark)}}"
                        data-action="{{route('admin.tadas.update-status', $tadaDetail->id)}}"
                >
                    <i class="link-icon me-1" data-feather="edit"></i>{{ __('index.update_status') }}</button>
            @endif
        @endcan

        <a href="{{route('admin.tadas.index')}}">
            <button class="btn btn-primary d-md-flex align-items-center"><i class="link-icon"
                                                                            data-feather="arrow-left"></i>{{ __('index.back') }}
            </button>
        </a>
    </div>
@endsection

@section('main-content')

    <section class="content">
        @include('admin.section.flash_message')
        @include('admin.tada.common.breadcrumb')
        <div class="row position-relative">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-2">{{ucfirst($tadaDetail->title)}}</h3>
                    </div>

                    <div class="card-body">
                        {!!  $tadaDetail->description!!}

                        @if(isset($attachments) && count($attachments) > 0 )
                            <div class="border-top mt-3 pt-3">
                                <h6 class="mb-2">{{ __('index.uploaded_attachment') }} </h6>
                                <div class="row">
                                    @foreach($attachments as $key => $data)
                                        @if(in_array(pathinfo(asset(TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment), PATHINFO_EXTENSION),['jpeg','png','jpg'])  )
                                            <div class="col-lg-3 mb-2">
                                                <div class="uploaded-image">
                                                    <a href="{{ asset(TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment) }}"
                                                       data-lightbox="image-1" data-title="{{$data->attachment}}">
                                                        <img class="w-100" style=""
                                                             src="{{ asset(TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment) }}"
                                                             alt="document images">
                                                    </a>
                                                    <p>{{$data->attachment}}</p>
                                                    @can('delete_attachment')
                                                        @if(AppHelper::checkSuperAdmin() || ($tadaDetail->is_settled == 0))
                                                            <a class="documentDelete" id="delete" data-title="Image"
                                                               href="{{route('admin.tadas.attachment-delete',$data->id)}}">
                                                                <i class="link-icon remove-image" data-feather="x"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </div>
                                        @else
                                            <div class="uploaded-files">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-1">
                                                        <div class="file-icon">
                                                            <i class="link-icon" data-feather="file-text"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-10">
                                                        <a target="_blank"
                                                           href="{{asset(TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment)}}">
                                                            {{asset(TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment)}}
                                                        </a>
                                                    </div>

                                                    @can('delete_attachment')
                                                        <div class="col-lg-1">
                                                            <a class="delete" data-title="attachment file"
                                                               data-href="{{route('admin.tadas.attachment-delete',$data->id)}}">
                                                                <i class="link-icon remove-files" data-feather="x"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4 sidebar-list position-relative">
                <div class="position-sticky top-0">
                    <div class="card mb-4 ">
                        <div class="card-header">
                            <h5>{{ __('index.tada_summary') }}</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-border">
                                <tbody>
                                <tr>
                                    <td>{{ __('index.total_expense') }}:</td>
                                    <td class="text-end">
                                        {{number_format($tadaDetail->total_expense)}}
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ __('index.employee') }}:</td>
                                    <td class="text-end text-primary">{{ucfirst($tadaDetail->employeeDetail->name)}}</td>
                                </tr>

                                <tr>
                                    <td>{{ __('index.submitted_date') }}:</td>
                                    <td class="text-end text-danger">{{ AppHelper::formatDateForView($tadaDetail->created_at)}}</td>
                                </tr>

                                <tr>
                                    <td>{{ __('index.verified_by') }}:</td>
                                    <td class="text-end text-success">
                                        {{$tadaDetail->verifiedBy ? ucfirst($tadaDetail->verifiedBy->name) : 'Admin'}}
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ __('index.verified_date') }}:</td>
                                    <td class="text-end text-danger">{{ $tadaDetail->verifiedBy ? AppHelper::formatDateForView($tadaDetail->updated_at)  : 'N/A'}}</td>
                                </tr>

                                <tr>
                                    <td>{{ __('index.status') }}:</td>
                                    <td class="text-end">
                                            <span
                                                class="btn btn-{{$status[$tadaDetail->status]}} cursor-default btn-xs">
                                                {{ucfirst($tadaDetail->status)}}
                                            </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ __('index.remark') }}</td>
                                    <td class="text-end">
                                        <span class="text-end text-muted"> {{$tadaDetail->remark ?? 'N/A'}}</span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.tada.update_status_form')

    </section>
@endsection

@section('scripts')
    @include('admin.tada.common.scripts')
@endsection
--}}
@php use App\Models\TadaAttachment; @endphp
@php use App\Helpers\AppHelper; @endphp
@extends('layouts.master')

@section('title',__('index.tada'))

@section('action',__('index.tada_detail'))

@section("styles")
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet"/>
    <style>

.bg-orange{
    background-color:#FB8233 !important;
    color:#fff !important;
}

.badge.bg-orange{
    background:#FB8233 !important;
}

.btn-orange{
    background:#FB8233;
    border-color:#FB8233;
    color:#fff;
}

</style>
@endsection

<?php
$status = [
    'pending' => 'primary',
    'accepted' => 'success',
    'rejected' => 'orange',
];
?>

@section('button')
    <div class="breadcrumb-button float-md-end d-md-flex align-items-center">
        @can('edit_tada')
            @if(AppHelper::checkSuperAdmin() || ($tadaDetail->is_settled == 0))
                <a href="{{route('admin.tadas.edit',$tadaDetail->id)}}">
                    <button class="btn btn-secondary d-md-flex align-items-center me-2">
                        <i class="link-icon me-1" data-feather="edit"></i>{{ __('index.edit_tada') }}
                    </button>
                </a>
            @endif
        @endcan

        @can('create_attachment')
            @if(AppHelper::checkSuperAdmin() || ($tadaDetail->is_settled == 0))
                <a href="{{route('admin.tadas.attachment.create',$tadaDetail->id)}}">
                    <button class="btn btn-primary d-md-flex align-items-center me-2">
                        <i class="link-icon me-1" data-feather="clipboard"></i>{{ __('index.upload_attachments') }}
                    </button>
                </a>
            @endif
        @endcan

        @can('edit_tada')
            @if(AppHelper::checkSuperAdmin() || ($tadaDetail->is_settled == 0))
                <button class="btn btn-primary d-md-flex align-items-center me-2"
                        id="updateStatus"
                        data-id="{{ $tadaDetail->id }}"
                        data-status="{{($tadaDetail->status)}}"
                        data-title="{{ucfirst($tadaDetail->title)}}"
                        data-reason="{{($tadaDetail->remark)}}"
                        data-action="{{route('admin.tadas.update-status', $tadaDetail->id)}}">
                    <i class="link-icon me-1" data-feather="edit"></i>{{ __('index.update_status') }}
                </button>
            @endif
        @endcan

        
    </div>
@endsection

@section('main-content')
<div class="teamy-body-wrapper">
    
    <div class="teamy-top-header">
        <div>
            <h2>{{ucfirst($tadaDetail->title)}}</h2>
            <div class="header-info-row position-relative">
                <!-- <div class="header-info-item">
                    <span class="status-badge bg-{{$status[$tadaDetail->status]}}">{{ucfirst($tadaDetail->status)}}</span>
                </div> -->
                <div class="header-info-item">
                    <i class="fa fa-user"></i> {{ucfirst($tadaDetail->employeeDetail->name)}}
                </div>
                <div class="header-info-item">
                    <i class="fa fa-calendar"></i> {{ AppHelper::formatDateForView($tadaDetail->created_at)}}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')
    @include('admin.tada.common.breadcrumb')

    <div class="row mt-4">
        {{-- Left Side: Description & Attachments --}}
        <div class="col-lg-8">
            <div class="teamy-main-card mb-4">
                <div class="section-title-wrapper mb-3">
                    <div class="section-icon"><i class="fa fa-align-left text-primary"></i></div>
                    <div class="section-heading-text">
                        <h4>{{ __('index.description') }}</h4>
                    </div>
                </div>
                <div class="p-3 border rounded bg-white" style="min-height: 200px;">
                    {!! $tadaDetail->description !!}
                </div>

                @if(isset($attachments) && count($attachments) > 0 )
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="mb-3"><i class="fa fa-paperclip me-2"></i>{{ __('index.uploaded_attachment') }}</h6>
                        <div class="row">
                            @foreach($attachments as $key => $data)
                                @if(in_array(pathinfo(asset(TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment), PATHINFO_EXTENSION),['jpeg','png','jpg']))
                                    <div class="col-lg-3 col-md-4 mb-3">
                                        <div class="uploaded-image border rounded p-1 position-relative shadow-sm bg-white">
                                            <a href="{{ asset(TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment) }}"
                                               data-lightbox="tada-gallery" data-title="{{$data->attachment}}">
                                                <img class="w-100 rounded" style="height: 120px; object-fit: cover;"
                                                     src="{{ asset(TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment) }}"
                                                     alt="document images">
                                            </a>
                                            <p class="small text-truncate mt-1 mb-0 px-1">{{$data->attachment}}</p>
                                            
                                            @can('delete_attachment')
                                                @if(AppHelper::checkSuperAdmin() || ($tadaDetail->is_settled == 0))
                                                    <a class="documentDelete position-absolute top-0 end-0 bg-danger text-white rounded-circle p-1" 
                                                       id="delete" data-title="Image" style="line-height: 0; transform: translate(30%, -30%);"
                                                       href="{{route('admin.tadas.attachment-delete',$data->id)}}">
                                                        <i data-feather="x" style="width: 12px; height: 12px;"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                        </div>
                                    </div>
                                @else
                                    <div class="col-lg-12 mb-2">
                                        <div class="uploaded-files border rounded p-2 bg-white d-flex align-items-center justify-content-between shadow-sm">
                                            <div class="d-flex align-items-center">
                                                <div class="file-icon me-2 text-primary">
                                                    <i data-feather="file-text"></i>
                                                </div>
                                                <a target="_blank" class="text-truncate" style="max-width: 250px;"
                                                   href="{{asset(TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment)}}">
                                                    {{$data->attachment}}
                                                </a>
                                            </div>

                                            @can('delete_attachment')
                                                @if(AppHelper::checkSuperAdmin() || ($tadaDetail->is_settled == 0))
                                                    <a class="delete text-danger" data-title="attachment file"
                                                       data-href="{{route('admin.tadas.attachment-delete',$data->id)}}">
                                                        <i data-feather="x"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right Side: TADA Summary --}}
        <div class="col-lg-4">
            <div class="teamy-main-card sticky-top" style="top: 20px;">
                <div class="section-title-wrapper mb-3">
                    <div class="section-heading-text">
                        <h5><i class="fa fa-info-circle me-2 text-primary"></i>{{ __('index.tada_summary') }}</h5>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td class="fw-bold text-muted">{{ __('index.total_expense') }}:</td>
                                <td class="text-end fw-bold text-dark">{{number_format($tadaDetail->total_expense)}}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">{{ __('index.employee') }}:</td>
                                <td class="text-end " style="color:#FB8233">{{ucfirst($tadaDetail->employeeDetail->name)}}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">{{ __('index.submitted_date') }}:</td>
                                <td class="text-end text-danger">{{ AppHelper::formatDateForView($tadaDetail->created_at)}}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">{{ __('index.verified_by') }}:</td>
                                <td class="text-end">
                                    {{$tadaDetail->verifiedBy ? ucfirst($tadaDetail->verifiedBy->name) : 'Admin'}}
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">{{ __('index.verified_date') }}:</td>
                                <td class="text-end">{{ $tadaDetail->verifiedBy ? AppHelper::formatDateForView($tadaDetail->updated_at) : 'N/A'}}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">{{ __('index.status') }}:</td>
                                <td class="text-end">
                                    <span class="badge bg-{{$status[$tadaDetail->status]}}">
                                        {{ucfirst($tadaDetail->status)}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="fw-bold text-muted pb-0">{{ __('index.remark') }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-muted small italic pt-1">
                                    {{$tadaDetail->remark ?? 'N/A'}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end">
        <a href="{{route('admin.tadas.index')}}">
            <button class="btn branch-back-btn d-md-flex align-items-center">
                <i class="link-icon" data-feather="arrow-left"></i>{{ __('index.back') }}
            </button>
        </a>
    </div>

    @include('admin.tada.update_status_form')
</div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    @include('admin.tada.common.scripts')
@endsection