@extends('layouts.master')

@section('title', __('index.holiday'))

@section('action', __('index.csv_import'))

@section('button')
    <div class="float-end">
        <a href="{{ route('admin.holidays.index') }}">
            <button class="btn btn-sm btn-primary shadow-sm" style="border-radius: 8px; background: #057db0; border: none; padding: 8px 15px;">
                <i class="link-icon" data-feather="arrow-left" style="width: 16px;"></i> {{ __('index.back')}}
            </button>
        </a>
    </div>
@endsection

@section('main-content')

<section class="content">

    @include('admin.section.flash_message')
    @include('admin.holiday.common.breadcrumb')

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                
                {{-- Glossy Header --}}
                <div class="card-header border-0 position-relative" style="background: linear-gradient(135deg, #057db0 0%, #046690 100%); padding: 25px;">
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1;"></div>
                    <div class="d-flex align-items-center position-relative" style="z-index: 2;">
                        <div style="background: rgba(255,255,255,0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 15px; backdrop-filter: blur(5px);">
                            <i data-feather="upload-cloud" style="color: white; width: 20px;"></i>
                        </div>
                        <div>
                            <h5 class="text-white fw-bold mb-0">@lang('index.holiday') CSV Import</h5>
                            <p class="text-white-50 mb-0 small" style="font-size: 11px;">Upload your holiday data efficiently</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4" style="background: #f8fafc;">
                    <div class="row g-4">
                        
                        {{-- Left Side: Upload Form --}}
                        <div class="col-md-6">
                            <div class="p-4 h-100 shadow-sm" style="background: white; border-radius: 15px; border: 1px solid #e2e8f0;">
                                <div class="d-flex align-items-center mb-4">
                                    <h6 class="fw-bold mb-0 text-dark" style="border-left: 4px solid #057db0; padding-left: 10px;">@lang('index.holiday_detail_csv')</h6>
                                </div>

                                <form class="forms-sample" action="{{ route('admin.holidays.import-csv.store') }}" enctype="multipart/form-data" method="POST">
                                    @csrf
                                    
                                    <div class="upload-zone mb-4" style="border: 2px dashed #cbd5e1; border-radius: 12px; padding: 30px; text-align: center; background: #f1f5f9; transition: all 0.3s;">
                                        <i data-feather="file" class="text-muted mb-2" style="width: 40px; height: 40px;"></i>
                                        <p class="text-muted small mb-3">Choose your CSV file to begin</p>
                                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" style="border-radius: 8px;">
                                        @error('file')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm" style="border-radius: 10px; background: #FB8233; border: none;">
                                        <i data-feather="check-circle" class="me-1" style="width: 18px;"></i> @lang('index.import')
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Right Side: Example Preview --}}
                        <div class="col-md-6">
                            <div class="p-4 h-100 shadow-sm" style="background: white; border-radius: 15px; border: 1px solid #e2e8f0;">
                                <div class="d-flex align-items-center mb-4">
                                    <h6 class="fw-bold mb-0 text-dark" style="border-left: 4px solid #64748b; padding-left: 10px;">@lang('index.holiday_csv_example')</h6>
                                </div>
                                
                                <div class="example-container" style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
                                    <div class="bg-light p-2 border-bottom d-flex align-items-center">
                                        <span style="width: 10px; height: 10px; background: #ff5f56; border-radius: 50%; display: inline-block; margin-right: 5px;"></span>
                                        <span style="width: 10px; height: 10px; background: #ffbd2e; border-radius: 50%; display: inline-block; margin-right: 5px;"></span>
                                        <span style="width: 10px; height: 10px; background: #27c93f; border-radius: 50%; display: inline-block;"></span>
                                        <small class="ms-2 text-muted fw-bold" style="font-size: 10px;">sample-csv-preview.png</small>
                                    </div>
                                    <img src="{{ asset('assets/images/sample-csv-holiday.png') }}" 
                                         class="img-fluid" 
                                         alt="CSV Example" 
                                         style="width: 100%; display: block; filter: grayscale(10%) contrast(1.1);">
                                </div>
                                <p class="mt-3 text-muted small"><i data-feather="alert-circle" class="me-1" style="width: 14px;"></i> Make sure your column headers match the example above.</p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="d-flex justify-content-end my-1 mb-3 me-4">
                    <a href="{{ route('admin.holidays.index') }}" class="branch-back-btn">
                            <i data-feather="arrow-left" style="width: 16px;"></i> {{ __('index.back')}}
                    </a>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection