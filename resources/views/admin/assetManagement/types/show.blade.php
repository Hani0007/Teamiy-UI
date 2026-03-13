@extends('layouts.master')

@section('title', __('index.asset_type'))

@section('main-content')
<section class="content" style="padding: 15px 25px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
    @include('admin.section.flash_message')
    @include('admin.assetManagement.types.common.breadcrumb')

    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-4 mt-3">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 800; margin-bottom: 5px;">
                {{ ucfirst($assetTypeDetail->name) }} 
                <span style="font-size: 14px; color: #94a3b8; font-weight: 500; background: #fff; padding: 4px 12px; border-radius: 20px; border: 1px solid #e2e8f0; margin-left: 10px;">
                    {{ count($assetTypeDetail->assets) }} @lang('index.assets')
                </span>
            </h2>
            <p style="color: #64748b; font-weight: 500; font-size: 13px;">
                <i data-feather="list" style="width: 14px; vertical-align: middle; color: #057db0;"></i> Showing all assets under this category
            </p>
        </div>
        
        <div class="float-end">
            <a href="{{route('admin.asset-types.index')}}" class="btn d-flex align-items-center gap-2" 
               style="border-radius: 12px; background: white; color: #057db0; border: 1px solid #057db0; padding: 10px 20px; font-weight: 600; transition: 0.3s;">
                <i data-feather="arrow-left" style="width: 18px;"></i> {{ __('index.back') }}
            </a>
        </div>
    </div>

    {{-- Assets Grid --}}
    <div class="row g-4 justify-content-start">
        @forelse($assetTypeDetail->assets as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="modern-asset-card">
                    {{-- Card Header with Gradient --}}
                    <div class="asset-card-header">
                        <div class="header-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="asset-icon-box">
                                    <i data-feather="package"></i>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="javascript:void(0)" 
                                       class="glass-action-btn" 
                                       title="Quick View"
                                       onclick="showAssetDetails('{{ route('admin.assets.show',$value->id) }}')">
                                        <i data-feather="eye"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <h4 class="asset-name-title text-truncate mt-3" title="{{ $value->name }}">
                                {{ucfirst($value->name)}}
                            </h4>
                            <div class="asset-badge-id">Code: #{{ $value->asset_code ?? $value->id }}</div>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="asset-card-body">
                        <div class="asset-info-row">
                            <div class="info-icon"><i data-feather="calendar"></i></div>
                            <div class="info-data">
                                <span class="label">{{__('index.purchased_date')}}</span>
                                <span class="value">{{\App\Helpers\AppHelper::formatDateForView($value->purchased_date)}}</span>
                            </div>
                        </div>

                        <div class="asset-info-row">
                            <div class="info-icon"><i data-feather="tool"></i></div>
                            <div class="info-data">
                                <span class="label">{{__('index.is_working')}}</span>
                                <span class="value badge-status {{ strtolower($value->is_working) == 'yes' ? 'bg-success-light' : 'bg-danger-light' }}">
                                    {{ucfirst($value->is_working)}}
                                </span>
                            </div>
                        </div>

                        <div class="asset-info-row border-0 mb-0">
                            <div class="info-icon"><i data-feather="check-square"></i></div>
                            <div class="info-data">
                                <span class="label">{{__('index.is_available')}}</span>
                                <span class="value availability-text {{ $value->is_available ? 'text-cyan' : 'text-muted' }}">
                                    <span class="status-dot {{ $value->is_available ? 'bg-cyan' : 'bg-gray' }}"></span>
                                    {{ isset($value->is_available) && $value->is_available == 1 ? __('index.yes'):__('index.no')}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state-container">
                    <div class="empty-icon-box">
                        <i data-feather="box" style="width: 50px; height: 50px; color: #cbd5e1;"></i>
                    </div>
                    <h5 class="mt-4 fw-bold" style="color: #64748b;">No Assets Found</h5>
                    <p class="text-muted">There are no assets registered under this category yet.</p>
                </div>
            </div>
        @endforelse
    </div>
</section>

@include('admin.assetManagement.assetDetail.show')
@endsection

@section('scripts')
<style>
    /* Premium Asset Card Styling */
    .modern-asset-card {
        background: white; border-radius: 24px; overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        height: 100%; border: 1px solid #eff2f5; position: relative;
    }
    .modern-asset-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
    
    .asset-card-header {
        background: linear-gradient(135deg, #057db0 0%, #035b80 100%);
        padding: 24px; color: white; position: relative;
    }
    .asset-card-header::after {
        content: ""; position: absolute; top: 0; right: 0; width: 100px; height: 100px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%; transform: translate(30%, -30%);
    }

    .asset-icon-box {
        width: 44px; height: 44px; background: rgba(255,255,255,0.15);
        border-radius: 14px; display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(10px); color: white;
    }
    .asset-icon-box i { width: 22px; }

    .glass-action-btn {
        width: 38px; height: 38px; background: rgba(255,255,255,0.2);
        border-radius: 12px; display: flex; align-items: center; justify-content: center;
        color: white; text-decoration: none; transition: 0.3s; backdrop-filter: blur(8px);
    }
    .glass-action-btn:hover { background: white; color: #057db0; transform: rotate(15deg); }

    .asset-name-title { font-size: 18px; font-weight: 700; margin: 0; }
    .asset-badge-id { font-size: 11px; color: rgba(255,255,255,0.7); font-weight: 500; margin-top: 5px; }

    .asset-card-body { padding: 24px; }
    .asset-info-row { 
        display: flex; align-items: center; gap: 15px; 
        padding-bottom: 15px; margin-bottom: 15px; border-bottom: 1px solid #f1f5f9;
    }
    .info-icon { 
        width: 32px; height: 32px; background: #f0f7ff; 
        border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #057db0;
    }
    .info-icon i { width: 16px; }
    .info-data .label { display: block; font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
    .info-data .value { font-size: 14px; color: #1e293b; font-weight: 600; display: flex; align-items: center; gap: 6px; }

    /* Status Badges */
    .bg-success-light { background: #dcfce7; color: #15803d; padding: 2px 10px; border-radius: 6px; font-size: 12px; }
    .bg-danger-light { background: #fee2e2; color: #b91c1c; padding: 2px 10px; border-radius: 6px; font-size: 12px; }
    .text-cyan { color: #0891b2; }
    .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
    .bg-cyan { background: #0891b2; box-shadow: 0 0 8px rgba(8,145,178,0.4); }
    .bg-gray { background: #cbd5e1; }

    /* Empty State */
    .empty-state-container { background: white; padding: 60px; border-radius: 30px; border: 2px dashed #e2e8f0; }
    
    /* Modal Customization */
    #assetDetail .modal-content { border-radius: 24px; border: none; overflow: hidden; }
    #assetDetail .modal-header { background: #057db0 !important; color: white; padding: 25px; border: none; }
    #assetDetail .modal-title { font-weight: 800; letter-spacing: -0.5px; }
</style>

<script>
    function showAssetDetails(url) {
        $.get(url, function (response) {
            if (response && response.data) {
                const data = response.data;
                $('.assetTitle').html('Asset Specifications');
                $('.name').text(data.name);
                $('.type').text(data.assetType);
                $('.asset_code').text(data.asset_code);
                $('.asset_serial_no').text(data.asset_serial_no || 'N/A');
                $('.is_working').text(data.is_working);
                $('.purchased_date').text(data.purchased_date);
                $('.is_available').text(data.is_available == 1 ? 'Yes' : 'No');
                $('.note').text(data.note || 'No notes available');

                if (data.used_for > 0) {
                    $('.used_for').text(data.used_for + ' Days').parent().fadeIn();
                } else {
                    $('.used_for').parent().hide();
                }

                if (data.image) {
                    $('.image').attr('src', data.image).parent().fadeIn();
                } else {
                    $('.image').parent().hide();
                }

                const modal = new bootstrap.Modal(document.getElementById('assetDetail'));
                modal.show();
            }
        });
    }

    $(document).ready(function() {
        if (typeof feather !== 'undefined') { feather.replace(); }
    });
</script>
@endsection