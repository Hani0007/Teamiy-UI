@extends('layouts.master')

@section('title', __('index.asset_type'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')
    @include('admin.assetManagement.types.common.breadcrumb')

    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0;">
                {{ ucfirst($assetTypeDetail->name) }} <small style="font-size: 14px; color: #94a3b8;">(Assets List)</small>
            </h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px;">
                <i data-feather="list" style="width: 14px; vertical-align: middle;"></i> Showing all assets under this category
            </p>
        </div>
        
        <div class="float-end">
            <a href="{{route('admin.asset-types.index')}}" class="btn btn-sm btn-primary px-3" style="border-radius: 20px; background-color: #057db0;">
                <i data-feather="arrow-left" style="width: 16px;"></i> {{ __('index.back') }}
            </a>
        </div>
    </div>

    {{-- Grid Layout --}}
    <div class="row g-4 justify-content-start">
        @forelse($assetTypeDetail->assets as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    {{-- Header --}}
                    <div class="card-glossy-header">
                        <div class="header-overlay"></div>
                        
                        {{-- Icon Box --}}
                        <div class="branch-icon-square position-relative" style="z-index: 2;">
                            <i data-feather="box"></i>
                        </div>

                        {{-- Asset Name --}}
                        <h4 class="branch-name-display position-relative text-start" style="z-index: 2;">
                            {{ucfirst($value->name)}}
                        </h4>
                        
                        {{-- ID and Eye Button - Perfectly Opposite --}}
                        <div class="d-flex justify-content-between align-items-center position-relative mt-3" style="z-index: 2;">
                            {{-- ID on Left --}}
                            <span class="branch-ref-pill">Asset Type ID: #{{$value->id}}</span>
                            
                            {{-- Eye Icon on Right (Opposite ID) --}}
                            <a href="javascript:void(0)" 
                               class="btn-view-circle" 
                               title="View Details"
                               onclick="showAssetDetails('{{ route('admin.assets.show',$value->id) }}')">
                                <i data-feather="eye" style="width: 16px;"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-white-body" style="padding-bottom: 10px;">
                        <div class="info-listing" style="margin-bottom: 0;">
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="calendar"></i></div>
                                <div class="text-content">
                                    <small>{{__('index.purchased_date')}}</small>
                                    <p style="font-weight: 600;">{{\App\Helpers\AppHelper::formatDateForView($value->purchased_date)}}</p>
                                </div>
                            </div>
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="activity"></i></div>
                                <div class="text-content">
                                    <small>{{__('index.is_working')}}</small>
                                    <p style="font-weight: 600;">{{ucfirst($value->is_working)}}</p>
                                </div>
                            </div>
                            <div class="info-item-box" style="margin-bottom: 5px;">
                                <div class="icon-circle"><i data-feather="check-circle"></i></div>
                                <div class="text-content">
                                    <small>{{__('index.is_available')}}</small>
                                    <p style="font-weight: 600; color: {{ $value->is_available ? '#0dcaf0' : '#6c757d' }};">
                                        {{ isset($value->is_available) && $value->is_available == 1 ? __('index.yes'):__('index.no')}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state" style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <i data-feather="alert-circle" style="width: 50px; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3"><b>{{ __('index.no_records_found') }}</b></p>
                </div>
            </div>
        @endforelse
    </div>
</section>

@include('admin.assetManagement.assetDetail.show')
@endsection

@section('scripts')
<style>
    /* Card UI */
    .branch-master-card {
        background: white; border-radius: 20px; overflow: hidden;
        transition: all 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        height: 100%; border: 1px solid #f1f5f9;
    }
    .branch-master-card:hover { transform: translateY(-5px); }
    
    .card-glossy-header {
        background: linear-gradient(135deg, #057db0 0%, #045d83 100%);
        padding: 20px; position: relative; color: white;
    }

    /* Eye Button Styling */
    .btn-view-circle {
        width: 32px; height: 32px; background: rgba(255, 255, 255, 0.2);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        color: white; text-decoration: none; transition: all 0.3s ease;
        backdrop-filter: blur(5px);
    }
    .btn-view-circle:hover { background: white; color: #057db0; transform: scale(1.1); }

    .branch-icon-square {
        width: 40px; height: 40px; background: rgba(255,255,255,0.2);
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(5px); margin-bottom: 12px;
    }
    .branch-name-display { font-size: 17px; font-weight: 700; margin: 0; text-align: left !important; }
    .branch-ref-pill { font-size: 11px; background: rgba(255,255,255,0.15); padding: 4px 12px; border-radius: 20px; }

    .card-white-body { padding: 20px; }
    .info-item-box { display: flex; align-items: center; margin-bottom: 12px; }
    .icon-circle { width: 30px; height: 30px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px; color: #057db0; }
    .text-content small { display: block; font-size: 10px; color: #94a3b8; text-transform: uppercase; }
    .text-content p { margin: 0; font-size: 13px; color: #334155; }

    /* Modal Header Fixes */
    #assetDetail .modal-header {
        background-color: #057db0 !important;
        color: white !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
    }
    #assetDetail .modal-title {
        color: white !important;
        font-weight: 700 !important;
        text-align: left !important;
        margin: 0 !important;
    }
    #assetDetail .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
</style>

<script>
    // showAssetDetails function remains the same as your working version
    function showAssetDetails(url) {
        $.get(url, function (response) {
            if (response && response.data) {
                const data = response.data;
                $('.assetTitle').html('Asset Detail');
                $('.name').text(data.name);
                $('.type').text(data.assetType);
                $('.asset_code').text(data.asset_code);
                $('.asset_serial_no').text(data.asset_serial_no);
                $('.is_working').text(data.is_working);
                $('.purchased_date').text(data.purchased_date);
                $('.is_available').text(data.is_available);
                $('.note').text(data.note);

                if (data.used_for > 0) {
                    $('.used_for').text(data.used_for + ' days').parent().show();
                } else {
                    $('.used_for').parent().hide();
                }

                if (data.image) {
                    $('.image').attr('src', data.image).parent().show();
                } else {
                    $('.image').parent().hide();
                }

                const modal = new bootstrap.Modal(document.getElementById('assetDetail'));
                modal.show();
            }
        });
    }
</script>
@endsection