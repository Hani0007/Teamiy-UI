@extends('layouts.master')

@section('title', __('index.leave_requests'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700;">{{ __('index.leave_requests') }}</h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 13px;">
                <i data-feather="list" style="width: 14px; vertical-align: middle;"></i> {{ __('index.lists') }}
            </p>
        </div>
        
        @canany(['create_leave_request','access_admin_leave'])
            <a href="{{ route('admin.leave-request.add')}}" style="text-decoration: none;">
                <button class="btn btn-primary">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.create_leave_request') }}</span>
                </button>
            </a>
        @endcanany
    </div>

    {{-- Data Cards Section --}}
    <div class="row g-4 justify-content-start">
        @php
            $statusTheme = [
                'approved'  => ['bg' => '#057db0', 'text' => '#fff'],
                'accepted'  => ['bg' => '#057db0', 'text' => '#fff'],
                'rejected'  => ['bg' => '#ef4444', 'text' => '#fff'],
                'cancelled' => ['bg' => '#ef4444', 'text' => '#fff'],
                'pending'   => ['bg' => '#fb8233', 'text' => '#fff'],
            ];
        @endphp

        @forelse($leaveDetails as $key => $value)
            @php 
                $currentStatus = strtolower($value->status);
                $theme = $statusTheme[$currentStatus] ?? ['bg' => '#64748b', 'text' => '#fff'];
            @endphp
            
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    <div class="card-glossy-header">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square"><i data-feather="file-text"></i></div>
                            
                            <a href="javascript:void(0)" class="leaveRequestUpdate text-decoration-none"
                               data-href="{{route('admin.leave-request.update-status',$value->id)}}"
                               data-status="{{$value->status}}" data-remark="{{$value->admin_remark}}" data-id="{{$value->id}}">
                                <span class="badge" style="background-color: {{ $theme['bg'] }}; color: {{ $theme['text'] }}; padding: 8px 12px; border-radius: 8px; font-weight: 600; font-size: 11px; cursor: pointer;">
                                    {{ $value->status === 'accepted' ? 'Approved' : ucfirst($value->status) }}
                                </span>
                            </a>
                        </div>
                        <h4 class="branch-name-display">{{ $value->leaveType ? ucfirst($value->leaveType->name) : 'Leave Request'}}</h4>
                        {{-- Top ID stays as requested --}}
                        <span class="branch-ref-pill">Request ID: #{{$value->id}}</span>
                    </div>

                    <div class="card-white-body">
                        <div class="info-listing">
                           

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="calendar"></i></div>
                                <div class="text-content">
                                    <small>{{ strtoupper(__('index.from')) }}</small>
                                    <p>{{ \App\Helpers\AppHelper::convertLeaveDateFormat($value->leave_from) }}</p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="calendar"></i></div>
                                <div class="text-content">
                                    <small>{{ strtoupper(__('index.to')) }}</small>
                                    <p>{{ \App\Helpers\AppHelper::convertLeaveDateFormat($value->leave_to) }}</p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="user"></i></div>
                                <div class="text-content">
                                    <small>{{ strtoupper(__('index.requested_by')) }}</small>
                                    <p>{{ $value->leaveRequestedBy ? ucfirst($value->leaveRequestedBy->name) : 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="clock"></i></div>
                                <div class="text-content">
                                    <small>{{ strtoupper(__('index.requested_days')) }}</small>
                                    <p>{{ $value->no_of_days }} Days</p>
                                </div>
                            </div>
                        </div>

                        <div class="stats-footer-box">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="emp-group">
                                    <span style="color: #64748b; font-weight: 700; font-size: 10px; margin-right: 5px;">{{ strtoupper(__('index.requested_date')) }}:</span>
                                    <span class="emp-label" style="font-size: 11px; font-weight: 600; color: #057db0;">
                                        {{ \App\Helpers\AppHelper::formatDateForView($value->leave_requested_date) }}
                                    </span>
                                </div>
                                <div class="action-dock">
                                    {{-- The class 'showLeaveReason' and 'data-href' are critical for the JS below --}}
                                    <a href="#" class="btn-action edit showLeaveReason" 
                                       data-href="{{ route('admin.leave-request.show', $value->id) }}"
                                       title="{{ __('index.show_leave_reason') }}">
                                        <i data-feather="eye" style="color: #057db0;height:18px;widht:18px"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state card shadow-sm p-5" style="border-radius: 20px;">
                    <h4 class="text-muted">{{ __('index.no_records_found') }}</h4>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{$leaveDetails->links()}}
    </div>
</section>

@include('admin.leaveRequest.show')
@include('admin.leaveRequest.common.form-model')
@include('admin.leaveRequest.common.approval-info-model')

@endsection

@section('scripts')
    @include('admin.leaveRequest.common.scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Re-initialize Feather Icons
            feather.replace();

            // Handle Eye Icon Click (Popup/Modal)
            document.querySelectorAll('.showLeaveReason').forEach(function (element) {
                element.addEventListener('click', function (event) {
                    event.preventDefault();
                    const url = this.getAttribute('data-href');

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.data) {
                                const leaveRequest = data.data;
                                // Update modal fields
                                document.getElementById('referredBy').innerText = leaveRequest.name || 'Admin';
                                document.getElementById('description').innerText = leaveRequest.reasons || 'N/A';
                                document.getElementById('adminRemark').innerText = leaveRequest.admin_remark || 'N/A';

                                // Open Bootstrap Modal (assuming ID is 'addslider')
                                const modalElement = document.getElementById('addslider');
                                if (modalElement) {
                                    const modal = new bootstrap.Modal(modalElement);
                                    modal.show();
                                }
                            }
                        })
                        .catch(error => console.error('Error fetching data:', error));
                });
            });
        });
    </script>
@endsection