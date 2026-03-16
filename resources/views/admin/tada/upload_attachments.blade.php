{{--
@extends('layouts.master')

@section('title',__('index.tada_attachment'))

@section('action',__('index.upload_tada_attachment'))

@section('button')
    <div class="float-end">
        <a href="{{route('admin.tadas.show',$tadaId)}}" >
            <button class="btn btn-sm btn-primary" ><i class="link-icon" data-feather="arrow-left"></i> {{__('index.back')}}</button>
        </a>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{asset('assets/css/imageuploadify.min.css')}}">
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.tada.common.breadcrumb')

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card ">
                    <div class="card-header">
                        <h5 class="text-muted">{{ __('index.upload_tada_attachment') }}</h5>
                    </div>
                    <div class="card-body">
                        <form id="tadaAttachment" class="forms-sample" action="{{route('admin.tadas.attachment.store')}}"
                              enctype="multipart/form-data"
                              method="POST"
                        >
                            @csrf
                            <div class="row">
                                <input type="hidden" value="{{$tadaId}}" readonly name="tada_id" >
                                <div class="mb-3 col-12">
                                    <div>
                                        <input id="image-uploadify" type="file" name="attachments[]"
                                               accept=".pdf,.jpg,.jpeg,.png,.docx,.doc,.xls,.txt,.zip" multiple />
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('admin.tadas.show', $tadaId) }}" class="btn btn-outline-secondary shadow-sm">
                                    <i class="link-icon me-1" data-feather="arrow-left" style="width: 16px;"></i> {{ __('index.back') }}
                                </a>
                            
                                <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                    <i class="link-icon me-1" data-feather="check-circle" style="width: 18px;"></i>
                                    {{ __('index.submit') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    <script src="{{asset('assets/js/imageuploadify.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            $("#image-uploadify").imageuploadify();
        });
    </script>

@endsection



--}}

@extends('layouts.master')

@section('title', __('index.tada_attachment'))
@section('action', __('index.upload_tada_attachment'))

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/imageuploadify.min.css') }}">
<style>
    /* Card styling */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    }

    .card-header {
        background-color: transparent;
        border-bottom: 1px solid #f0f0f0;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-header h5 {
        color: #057DB0;
        font-weight: 600;
        margin-bottom: 0;
    }

    /* Submit Button styling */
    .btn-submit {
        background-color: #057DB0;
        border-color: #057DB0;
        color: white;
        padding: 10px 30px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        background-color: #046a96;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Back Button */
    .btn-back {
        border: 1px solid #ced4da;
        color: #495057;
        padding: 10px 25px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-back:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }

    /* ImageUploadify styling */
    .imageuploadify {
        border: 2px dashed #dce4ec !important;
        border-radius: 10px !important;
        background-color: #f8fafc !important;
    }
</style>
@endsection

@section('main-content')
<section class="content pb-4">

    @include('admin.section.flash_message')

    <div class="mb-3">
        @include('admin.tada.common.breadcrumb')
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    <i class="link-icon text-primary" data-feather="upload-cloud"></i>
                    <h5>{{ __('index.upload_tada_attachment') }}</h5>
                </div>
                <div class="card-body p-4">
                    <form id="tadaAttachment" class="forms-sample"
                          action="{{ route('admin.tadas.attachment.store') }}"
                          enctype="multipart/form-data"
                          method="POST">
                        @csrf
                        <input type="hidden" name="tada_id" value="{{ $tadaId }}" readonly>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">
                                Allowed Files: PDF, Images, Excel, Zip
                            </label>
                            <input id="image-uploadify" type="file" name="attachments[]"
                                   accept=".pdf,.jpg,.jpeg,.png,.docx,.doc,.xls,.txt,.zip" multiple />
                        </div>

                        <!-- Footer Buttons: Back + Submit -->
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="{{ route('admin.tadas.show', $tadaId) }}" class="btn branch-back-btn shadow-sm">
                                <i class="link-icon me-1" data-feather="arrow-left" style="width: 16px;"></i>
                                {{ __('index.back') }}
                            </a>

                            <button type="submit" class="btn btn-primary shadow-sm">
                                
                                {{ __('index.submit') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/imageuploadify.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $("#image-uploadify").imageuploadify();
        feather.replace(); // make sure icons render
    });
</script>
@endsection