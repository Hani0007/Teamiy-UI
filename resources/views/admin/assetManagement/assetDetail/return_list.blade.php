@php use App\Models\Asset; @endphp
@php use App\Helpers\AppHelper; @endphp
@extends('layouts.master')

@section('title', __('index.assets'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    {{-- Tabs Navigation --}}
    <div class="custom-tabs-container mb-4  border-bottom pb-2">
        <ul class="nav nav-pills gap-2" id="assetTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active custom-pill" id="return-tab" data-bs-toggle="tab" href="#return" role="tab">{{ __('index.return') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link custom-pill" id="maintenance-tab" data-bs-toggle="tab" href="#maintenance" role="tab">{{ __('index.maintenance') }}</a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="assetTabContent">
        <div class="tab-pane fade show active" id="return" role="tabpanel">
            <div class="row g-4">
                @forelse($returnLists as $key => $value)
                    @php
                        $assignedDate = new DateTime($value->assigned_date);
                        $returnedDate = !empty($value->returned_date) ? new DateTime($value->returned_date) : new DateTime();
                        $daysDifference = $assignedDate->diff($returnedDate)->days + 1;
                    @endphp
                    <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                        <div class="branch-master-card">
                            <div class="card-glossy-header">
                                <div class="header-overlay"></div>
                                <div class="branch-icon-square position-relative" style="z-index: 2;">
                                    <i data-feather="user"></i>
                                </div>
                                <h4 class="branch-name-display position-relative text-start" style="z-index: 2;">
                                    {{ucfirst($value->user?->name)}}
                                </h4>
                                
                                {{-- ID Left, Eye Right --}}
                                <div class="d-flex justify-content-between align-items-center position-relative mt-3" style="z-index: 2;">
                                    <span class="branch-ref-pill">#{{$value->id}}</span>
                                    <a href="javascript:void(0)" class="btn-view-circle show-assignment" 
                                       data-asset="{{ $value->asset->name }}" 
                                       data-employee="{{ $value->user->name }}"
                                       data-notes="{!! $value->notes !!}" 
                                       data-status="{{ ucfirst($value->status) }}"
                                       data-assigned_date="{{ AppHelper::formatDateForView($value->assigned_date) }}"
                                       data-returned_date="{{ isset($value->returned_date) ? AppHelper::formatDateForView($value->returned_date) : '' }}"
                                       data-used_for="{{ $daysDifference }}"
                                       data-return_condition="{{ ucfirst($value->return_condition) }}">
                                        <i data-feather="eye" style="width: 16px;"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-white-body">
                                <div class="info-listing">
                                    {{-- Asset --}}
                                    <div class="info-item-box">
                                        <div class="icon-circle"><i data-feather="box"></i></div>
                                        <div class="text-content">
                                            <small>{{__('index.asset')}}</small>
                                            <p style="font-weight: 600; color: #057db0; cursor:pointer;" onclick="showAssetDetails('{{ route('admin.assets.show',$value->asset_id) }}')">{{ucfirst($value->asset?->name)}}</p>
                                        </div>
                                    </div>
                                    {{-- Returned Date + Days Calculation --}}
                                    <div class="info-item-box">
                                        <div class="icon-circle"><i data-feather="calendar"></i></div>
                                        <div class="text-content">
                                            <small>{{__('index.returned_date')}}</small>
                                            <p style="font-weight: 600;">
                                                {{ isset($value->returned_date) ? AppHelper::formatDateForView($value->returned_date) : '' }}
                                                @if($daysDifference > 0)
                                                    <span style="color: #64748b; font-size: 11px; font-weight: normal;">(Used for: {{$daysDifference}} days)</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    {{-- Returned Status --}}
                                    <div class="info-item-box">
                                        <div class="icon-circle"><i data-feather="shield"></i></div>
                                        <div class="text-content">
                                            <small>{{__('index.return_condition')}}</small>
                                            <p style="font-weight: 600;">
                                                <span class="badge" style="background: #e0f2fe; color: #057db0; font-weight: 600; padding: 5px 10px; border-radius: 8px;">
                                                    {{ucfirst($value->return_condition)}}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5"><b>{{ __('index.no_records_found') }}</b></div>
                @endforelse
            </div>
        </div>

        <div class="tab-pane fade" id="maintenance" role="tabpanel">
            <div class="row g-4">
                @forelse($maintenanceLists as $key => $value)
                    <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                        <div class="branch-master-card">
                            <div class="card-glossy-header">
                                <div class="header-overlay"></div>
                                <div class="branch-icon-square position-relative" style="z-index: 2;"><i data-feather="tool"></i></div>
                                <h4 class="branch-name-display position-relative text-start" style="z-index: 2;">{{ucfirst($value->asset?->name)}}</h4>
                                <div class="d-flex justify-content-between align-items-center position-relative mt-3" style="z-index: 2;">
                                    <span class="branch-ref-pill">#{{$value->id}}</span>
                                    @can('assign_repair_update')
                                    <a href="javascript:void(0)" class="btn-view-circle show-repair-detail"
                                       data-asset="{{ $value->asset?->name }}" data-type="{{ $value->asset?->type?->name }}"
                                       data-notes="{!! $value->notes !!}">
                                        <i data-feather="eye" style="width: 16px;"></i>
                                    </a>
                                    @endcan
                                </div>
                            </div>
                            <div class="card-white-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="text-content">
                                        <small>{{__('index.type')}}</small>
                                        <p style="font-weight: 600;">{{ucfirst($value->asset?->type?->name)}}</p>
                                    </div>
                                    <div class="text-end">
                                        <small style="font-size: 10px; color: #94a3b8;">Repaired?</small>
                                        <div class="mt-1">
                                            <label class="switch">
                                                <input class="toggleStatus" href="{{route('admin.asset.toggle-repair-status',$value->id)}}"
                                                       type="checkbox" {{ $value->return_condition == \App\Enum\AssetReturnConditionEnum::repaired->value ?'checked':''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5"><b>{{ __('index.no_records_found') }}</b></div>
                @endforelse
            </div>
        </div>
    </div>
</section>

{{-- Modals --}}
@include('admin.assetManagement.assetDetail.common.assignment_detail')
@include('admin.assetManagement.assetDetail.show')

<div class="modal fade" id="repairDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header position-relative">
                <h5 class="modal-title repairTitle"></h5>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr><th class="text-muted w-25">{{ __('index.asset') }}</th><td class="asset fw-bold"></td></tr>
                        <tr><th class="text-muted w-25">{{ __('index.type') }}</th><td class="type fw-bold"></td></tr>
                        <tr><th class="text-muted w-25">{{ __('index.notes') }}</th><td class="notes"></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
    /* Card Design */
    .branch-master-card { background: white; border-radius: 20px; overflow: hidden; transition: all 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.08); height: 100%; border: 1px solid #f1f5f9; }
    .card-glossy-header { background: linear-gradient(135deg, #057db0 0%, #045d83 100%); padding: 20px; position: relative; color: white; }
    
    .custom-pill { border-radius: 20px !important; padding: 8px 25px !important; font-weight: 600; color: #64748b; background: #f1f5f9; border:none; }
    .nav-pills .nav-link.active { background-color: #057db0 !important; color: white; }

    .btn-view-circle { width: 32px; height: 32px; background: #FFFF; border-radius: 20%; display: flex; align-items: center; justify-content: center; color: #057db0; backdrop-filter: blur(5px); transition: 0.3s; }
    .btn-view-circle:hover { background: white; color: #057db0; transform: scale(1.1); }
    
    .branch-icon-square { width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(5px); margin-bottom: 12px; }
    .branch-name-display { font-size: 17px; font-weight: 700; margin: 0; text-align: left !important; }
    .branch-ref-pill { font-size: 11px; background: rgba(255,255,255,0.15); padding: 4px 12px; border-radius: 20px; color: white; }

    .card-white-body { padding: 20px; }
    .info-item-box { display: flex; align-items: center; margin-bottom: 15px; }
    .icon-circle { width: 32px; height: 32px; background: #f0f7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px; color: #057db0; }
    .text-content small { display: block; font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
    .text-content p { margin: 0; font-size: 13px; color: #334155; }

    /* Modal Fixes */
    .modal-content { border: none !important; border-radius: 15px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.15) !important; outline: none !important; }
    .modal-header { background-color: #057db0 !important; color: white !important; border-bottom: none !important; padding: 18px 25px !important; }
    .modal-title { font-weight: 700 !important; text-align: left !important; flex: 1; }
    .btn-close { filter: invert(1) brightness(200%); opacity: 0.8; outline: none !important; box-shadow: none !important; }
    .modal { outline: none !important; }

    /* Switch Style */
    .switch { position: relative; display: inline-block; width: 38px; height: 20px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: #057db0; }
    input:checked + .slider:before { transform: translateX(18px); }
</style>

<script>
    // Logic for popups and toggles remains the same as previously fixed
    $('.toggleStatus').change(function (event) {
        var href = $(this).attr('href');
        Swal.fire({
            title: '{{ __('index.confirm_change_status') }}',
            showDenyButton: true, confirmButtonText: `{{ __('index.yes') }}`, denyButtonText: `{{ __('index.no') }}`,
        }).then((result) => {
            if (result.isConfirmed) { window.location.href = href; } 
            else { $(this).prop('checked', !$(this).prop('checked')); }
        });
    });

    $(document).on('click', '.show-assignment', function () {
        const $el = $(this);
        let rdate = $el.data('returned_date');
        if($el.data('used_for') > 0) rdate += ' (used for: ' + $el.data('used_for') + ' days)';
        $('.assignmentTitle').text('Asset Assignment Detail');
        $('.assigned_date').text($el.data('assigned_date'));
        $('.status').text($el.data('status'));
        $('.returned_date').text(rdate);
        $('.return_condition').text($el.data('return_condition'));
        $('.employee').text($el.data('employee'));
        $('.asset').text($el.data('asset'));
        $('.notes').html($el.data('notes'));
        new bootstrap.Modal(document.getElementById('assignmentDetail')).show();
    });

    $(document).on('click', '.show-repair-detail', function () {
        const $el = $(this);
        $('.repairTitle').text('Asset Repair Detail');
        $('.type').text($el.data('type'));
        $('.asset').text($el.data('asset'));
        $('.notes').html($el.data('notes'));
        new bootstrap.Modal(document.getElementById('repairDetail')).show();
    });

    function showAssetDetails(url) {
        $.get(url, function (response) {
            if (response && response.data) {
            const data = response.data;
            
            // Modal Title
            $('.assetTitle').html('Asset Detail');

            // Existing & New Fields mapping
            $('.name').text(data.name || 'N/A');
            $('.type').text(data.assetType || 'N/A');
            $('.asset_code').text(data.asset_code || 'N/A');
            
            // Serial Number (Matching your modal class 'asset_serial_no')
            $('.asset_serial_no').text(data.asset_serial_no || 'N/A');
            
            $('.is_working').text(data.is_working || 'N/A');
            
            // Purchased Date
            $('.purchased_date').text(data.purchased_date || 'N/A');
            
            // Available for Employee (Matching your modal class 'is_available')
            $('.is_available').text(data.is_available_for_employee || 'N/A');
            
            $('.note').text(data.note || 'N/A');
            
            // Used For
            $('.used_for').text(data.used_for || '0 days');

            // Image Handle (Adding logic for the image tag in your modal)
            if(data.image) {
                $('.image').attr('src', data.image).show();
            } else {
                $('.image').hide();
            }

            // Show Modal
            new bootstrap.Modal(document.getElementById('assetDetail')).show();
            }
        });
    }
</script>
@endsection