@extends('layouts.master')

@section('title', __('index.resignation'))
@section('action', __('index.show_detail'))

@section('button')
    <div class="float-md-end">
        <a href="{{route('admin.resignation.index')}}">
            <button class="btn btn-primary shadow-sm px-4" style="border-radius: 10px; background: linear-gradient(135deg, #057db0 0%, #046690 100%); border: none;">
                <i class="link-icon" data-feather="arrow-left" style="width: 18px;"></i> {{ __('index.back') }}
            </button>
        </a>
    </div>
@endsection

@section('main-content')
<style>
    .detail-card { border-radius: 20px; border: none; overflow: hidden; }
    .detail-header { background: linear-gradient(135deg, #057db0 0%, #046690 100%); padding: 40px 30px; color: white; }
    .info-box { background: #fff; border-radius: 15px; padding: 20px; border: 1px solid #f0f0f0; height: 100%; transition: all 0.3s; }
    .info-box:hover { box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
    .label-text { font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 1px; margin-bottom: 5px; }
    .value-text { font-size: 15px; color: #1e293b; font-weight: 600; }
    .avatar-circle { width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 700; border: 3px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px); }
    .status-badge-lg { padding: 8px 20px; border-radius: 10px; font-weight: 700; font-size: 13px; }
    .document-preview { border: 2px dashed #e2e8f0; border-radius: 15px; padding: 20px; text-align: center; background: #f8fafc; }
    .reason-area { background: #f0f9ff; border-left: 4px solid #057db0; padding: 20px; border-radius: 0 15px 15px 0; margin-top: 20px; }
</style>

<section class="content">
    @include('admin.section.flash_message')

    <div class="card detail-card shadow-sm">
        {{-- Top Header Section --}}
        <div class="detail-header">
            <div class="d-flex align-items-center">
                <div class="avatar-circle me-4">
                    {{ substr($resignationDetail->employee?->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="mb-1 fw-bold">{{ $resignationDetail->employee?->name }}</h3>
                    <p class="mb-0 opacity-75"><i data-feather="map-pin" style="width: 14px;"></i> {{ $resignationDetail->branch?->name }} | {{ $resignationDetail->department?->dept_name }}</p>
                </div>
                <div class="ms-auto">
                    @php
                        $color = [
                            \App\Enum\ResignationStatusEnum::approved->value => 'primary',
                            \App\Enum\ResignationStatusEnum::onReview->value => 'primary',
                            \App\Enum\ResignationStatusEnum::pending->value => 'secondary',
                            \App\Enum\ResignationStatusEnum::cancelled->value => 'danger',
                        ];
                    @endphp
                    <span class="status-badge-lg bg-white text-{{ $color[$resignationDetail->status] }} shadow-sm">
                        {{ ucfirst($resignationDetail->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-4 bg-light">
            <div class="row g-4">
                {{-- Date Information --}}
                <div class="col-md-4">
                    <div class="info-box shadow-sm text-center">
                        <div class="label-text text-primary">{{ __('index.resignation_date') }}</div>
                        <div class="value-text">
                            <i data-feather="calendar" class="me-1 text-muted" style="width: 16px;"></i>
                            {{ \App\Helpers\AppHelper::formatDateForView($resignationDetail->resignation_date) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box shadow-sm text-center border-primary">
                        <div class="label-text text-primary">{{ __('index.last_date') }}</div>
                        <div class="value-text">
                            <i data-feather="clock" class="me-1 text-muted" style="width: 16px;"></i>
                            {{ \App\Helpers\AppHelper::formatDateForView($resignationDetail->last_working_day) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box shadow-sm text-center">
                        <div class="label-text">{{ __('index.department') }}</div>
                        <div class="value-text">{{ $resignationDetail->department?->dept_name }}</div>
                    </div>
                </div>

                {{-- Reason Section --}}
                <div class="col-lg-8">
                    <div class="info-box shadow-sm">
                        <div class="label-text mb-3"><i data-feather="align-left" class="me-1"></i> {{ __('index.reason') }}</div>
                        <div class="reason-area">
                            <div class="value-text" style="line-height: 1.6; font-style: italic; color: #475569;">
                                {!! $resignationDetail->reason !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Document Section --}}
                <div class="col-lg-4">
                    <div class="info-box shadow-sm">
                        <div class="label-text mb-3"><i data-feather="file" class="me-1"></i> {{ __('index.document') }}</div>
                        @if(isset($resignationDetail->document))
                            @php $fileExtension = pathinfo($resignationDetail->document, PATHINFO_EXTENSION); @endphp
                            <div class="document-preview">
                                @if(in_array($fileExtension, ['jpeg', 'jpg', 'png', 'webp']))
                                    <img class="img-fluid rounded shadow-sm mb-3" style="max-height: 150px; cursor: pointer;" 
                                         src="{{ asset(\App\Models\Resignation::UPLOAD_PATH . $resignationDetail->document) }}" 
                                         data-bs-toggle="modal" data-bs-target="#imgModal">
                                    <br>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#imgModal">View Document</button>
                                @elseif($fileExtension === 'pdf')
                                    <div class="py-3">
                                        <i data-feather="file-text" class="text-danger mb-2" style="width: 40px; height: 40px;"></i>
                                        <p class="small fw-bold text-muted">Resignation Letter (PDF)</p>
                                        <a href="{{ asset(\App\Models\Resignation::UPLOAD_PATH . $resignationDetail->document) }}" target="_blank" class="btn btn-sm btn-danger px-3">Open PDF</a>
                                    </div>
                                @else
                                    <a href="{{ asset(\App\Models\Resignation::UPLOAD_PATH . $resignationDetail->document) }}" download class="btn btn-sm btn-dark">Download File</a>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4 text-muted small">No document uploaded</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="float-md-end mt-3">
                <a href="{{ route('admin.resignation.index') }}">
                    <button class="btn btn-primary shadow-sm px-4" style="border-radius: 10px; background: linear-gradient(135deg, #057db0 0%, #046690 100%); border: none;">
                        <i class="link-icon" data-feather="arrow-left" style="width: 18px;"></i> 
                        {{ __('index.back') }}
                    </button>
                </a>
            </div>    
        </div>
    </div>
</section>

{{-- Image Modal --}}
@if(isset($resignationDetail->document))
<div class="modal fade" id="imgModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body p-0 text-center">
                <img class="img-fluid rounded shadow-lg" src="{{ asset(\App\Models\Resignation::UPLOAD_PATH . $resignationDetail->document) }}">
                <div class="mt-3">
                    <button type="button" class="btn btn-light px-4 fw-bold shadow" data-bs-dismiss="modal" style="border-radius: 10px;">Close Preview</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
    @include('admin.resignation.common.scripts')
    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') { feather.replace(); }
        });
    </script>
@endsection