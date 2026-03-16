@extends('layouts.master')

@section('title', __('index.show_user_details'))

@section('action', __('index.detail'))

{{-- Buttons ko section se hata kar seedha content mein heading ke saath rakha hai --}}
@section('button')
@endsection

@section('main-content')
    {{-- @dd($userDetail->officeTime) --}}

    <section class="content">
        @include('admin.section.flash_message')
        @include('admin.employees.common.breadcrumb')
        <div class="detail-header-wrapper">
            <div class="header-text-box">
                <h3 style="color:#057db0;">Employee Details</h3>
                <p>Full profile information for <b>{{ $userDetail->name }}</b></p>
            </div>
            <div class="action-btn-group">
                @can('edit_employee')
                    <a href="{{ route('admin.employees.edit', $userDetail->id) }}" class="btn-create-new">
                        <i data-feather="edit" style="width: 16px;"></i> {{ __('index.edit_detail') }}
                    </a>
                @endcan
                <a href="{{ route('admin.employees.index') }}" class="btn-custom btn-back-main">
                    <i data-feather="arrow-left" style="width: 16px;"></i> {{ __('index.back') }}
                </a>
            </div>
        </div>

        <div class="profile-banner-top d-md-flex align-items-center">
            @if (isset($userDetail->avatar) &&
                    $userDetail->avatar &&
                    file_exists(public_path(\App\Models\User::AVATAR_UPLOAD_PATH . $userDetail->avatar)))
                <img class="profile-avatar-circle rounded-circle"
                    src="{{ asset(\App\Models\User::AVATAR_UPLOAD_PATH . $userDetail->avatar) }}" alt="profile">
            @else
                <img class="profile-avatar-circle rounded-circle" src="{{ asset('assets/images/img.png') }}" alt="profile">
            @endif

            <div class="ms-md-4 mt-3 mt-md-0">
                <h2 class="fw-bold mb-1" style="font-size: 28px; letter-spacing: -0.5px;">{{ ucfirst($userDetail->name) }}
                </h2>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-white text-dark py-1 px-3 fw-bold"
                        style="font-size: 12px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); background-color: #fb8233 !important; color: #fff !important;">Code:
                        {{ $userDetail->employee_code }}</span>
                    <span class="small"><i data-feather="mail" style="width: 14px; vertical-align: middle;"></i>
                        {{ $userDetail->email }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="info-card-modern">
                    <div class="card-header">
                        <h6 class="card-title">{{ __('index.user_detail') }}</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="detail-item-row">
                            <div class="detail-split-col"><label class="label-muted">{{ __('index.username') }}</label>
                                <p class="value-dark">{{ $userDetail->username }}</p>
                            </div>
                            <div class="detail-split-col"><label class="label-muted">{{ __('index.gender') }}</label>
                                <p class="value-dark">{{ ucfirst($userDetail->gender) }}</p>
                            </div>
                        </div>
                        <div class="detail-item-row">
                            <div class="detail-split-col"><label
                                    class="label-muted">{{ __('index.marital_status') }}</label>
                                <p class="value-dark">{{ ucfirst($userDetail->marital_status) }}</p>
                            </div>
                            <div class="detail-split-col"><label class="label-muted">{{ __('index.address') }}</label>
                                <p class="value-dark">{{ ucfirst($userDetail->address) }}</p>
                            </div>
                        </div>
                        <div class="detail-item-row">
                            <div class="detail-split-col"><label class="label-muted">{{ __('index.phone_number') }}</label>
                                <p class="value-dark">{{ $userDetail->phone }}</p>
                            </div>
                            <div class="detail-split-col"><label
                                    class="label-muted">{{ __('index.date_of_birth') }}</label>
                                <p class="value-dark">
                                    {{ isset($userDetail->dob) ? \App\Helpers\AppHelper::formatDateForView($userDetail->dob) : '—' }}
                                </p>
                            </div>
                        </div>
                        <div class="detail-item-row">
                            <div class="detail-split-col"><label class="label-muted">{{ __('index.work_email') }}</label>
                                <p class="value-dark">{{ $userDetail->work_email }}</p>
                            </div>
                            <div class="detail-split-col"><label
                                    class="label-muted">{{ __('index.personal_email') }}</label>
                                <p class="value-dark">{{ $userDetail->email }}</p>
                            </div>
                        </div>
                        <div class="detail-item-row">
                            <div class="detail-split-col"><label class="label-muted">{{ __('index.nationality') }}</label>
                                <p class="value-dark">{{ $userDetail->nationality }}</p>
                            </div>
                            <div class="detail-split-col"><label
                                    class="label-muted">{{ __('index.place_of_birth') }}</label>
                                <p class="value-dark">
                                    {{ isset($userDetail->place_of_birth) ? $userDetail->place_of_birth : '—' }}</p>
                            </div>
                        </div>
                        <div class="detail-item-row">
                            <div class="detail-split-col"><label class="label-muted">{{ __('index.role') }}</label>
                                <p class="value-dark">
                                    {{ $userDetail->role ? ucfirst($userDetail->role->name) : __('index.not_applicable') }}
                                </p>
                            </div>
                            <div class="detail-split-col">
                                <label class="label-muted">{{ __('index.is_active') }}</label>
                                <p class="value-dark">
                                    @if ($userDetail->is_active == 1)
                                        <span class="text-success fw-bold">● {{ __('index.yes') }}</span>
                                    @else
                                        <span class="text-danger fw-bold">● {{ __('index.no') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="info-card-modern">
                    <div class="card-header">
                        <h6 class="card-title">{{ __('index.office_detail') }}</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="detail-item-row">
                            <div class="w-100"><label class="label-muted">{{ __('index.branch_name') }}</label>
                                <p class="value-dark">
                                    {{ $userDetail->branch ? ucfirst($userDetail->branch->name) : __('index.not_applicable') }}
                                </p>
                            </div>
                        </div>
                        <div class="detail-item-row">
                            <div class="w-100"><label class="label-muted">{{ __('index.department_name') }}</label>
                                <p class="value-dark">
                                    {{ $userDetail->department ? ucfirst($userDetail->department->dept_name) : __('index.not_applicable') }}
                                </p>
                            </div>
                        </div>
                        <div class="detail-item-row">
                            <div class="w-100"><label class="label-muted">{{ __('index.post_name') }}</label>
                                <p class="value-dark">
                                    {{ $userDetail->post ? ucfirst($userDetail->post->post_name) : __('index.not_applicable') }}
                                </p>
                            </div>
                        </div>
                        <div class="detail-item-row">
                            <div class="detail-split-col"><label
                                    class="label-muted">{{ __('index.employment_type') }}</label>
                                <p class="value-dark" style="color:#fb8233 !important;">
                                    {{ ucfirst($userDetail->employment_type) }}</p>
                            </div>
                            <div class="detail-split-col"><label class="label-muted">{{ __('index.workspace') }}</label>
                                <p class="value-dark">
                                    {{ $userDetail->workspace_type == 1 ? __('index.office') : __('index.home') }}</p>
                            </div>
                        </div>
                        <div class="detail-item-row">
                            <div class="w-100"><label class="label-muted">{{ __('index.joining_date') }}</label>
                                <p class="value-dark">
                                    {{ isset($userDetail->joining_date) ? \App\Helpers\AppHelper::formatDateForView($userDetail->joining_date) : __('index.not_applicable') }}
                                </p>
                            </div>
                        </div>
                        <!-- Office Time Details -->
                        @if (isset($userDetail->officeTime))
                            <div class="detail-item-row">
                                <div class="detail-split-col">
                                    <label class="label-muted">{{ __('index.opening_time') }}</label>
                                    <p class="value-dark">
                                        {{ \App\Helpers\AppHelper::convertLeaveTimeFormat($userDetail->officeTime->opening_time) }}
                                    </p>
                                </div>
                                <div class="detail-split-col">
                                    <label class="label-muted">{{ __('index.closing_time') }}</label>
                                    <p class="value-dark">
                                        {{ \App\Helpers\AppHelper::convertLeaveTimeFormat($userDetail->officeTime->closing_time) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Early Check-in -->
                            <div class="detail-item-row">
                                <div class="detail-split-col">
                                    <label class="label-muted">{{ __('index.early_check_in') }}</label>
                                    <p class="value-dark">
                                        @if ($userDetail->officeTime->is_early_check_in)
                                            <span class="text-success fw-bold">● {{ __('index.yes') }}</span>
                                            ({{ $userDetail->officeTime->checkin_before }} min)
                                        @else
                                            <span class="text-danger fw-bold">● {{ __('index.no') }}</span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Early Check-out -->
                                <div class="detail-split-col">
                                    <label class="label-muted">{{ __('index.early_check_out') }}</label>
                                    <p class="value-dark">
                                        @if ($userDetail->officeTime->is_early_check_out)
                                            <span class="text-success fw-bold">● {{ __('index.yes') }}</span>
                                            ({{ $userDetail->officeTime->checkout_before }} min)
                                        @else
                                            <span class="text-danger fw-bold">● {{ __('index.no') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Late Check-in -->
                            <div class="detail-item-row">
                                <div class="detail-split-col">
                                    <label class="label-muted">{{ __('index.late_check_in') }}</label>
                                    <p class="value-dark">
                                        @if ($userDetail->officeTime->is_late_check_in)
                                            <span class="text-success fw-bold">● {{ __('index.yes') }}</span>
                                            ({{ $userDetail->officeTime->checkin_after }} min)
                                        @else
                                            <span class="text-danger fw-bold">● {{ __('index.no') }}</span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Late Check-out -->
                                <div class="detail-split-col">
                                    <label class="label-muted">{{ __('index.late_check_out') }}</label>
                                    <p class="value-dark">
                                        @if ($userDetail->officeTime->is_late_check_out)
                                            <span class="text-success fw-bold">● {{ __('index.yes') }}</span>
                                            ({{ $userDetail->officeTime->checkout_after }} min)
                                        @else
                                            <span class="text-danger fw-bold">● {{ __('index.no') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="detail-item-row">
                                <div class="w-100">
                                    <label class="label-muted">{{ __('index.office_time') }}</label>
                                    <p class="value-dark">{{ __('index.not_applicable') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="info-card-modern">
                    <div class="card-header">
                        <h6 class="card-title">{{ __('index.account_detail') }}</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="detail-item-row">
                            <div class="w-100"><label class="label-muted">{{ __('index.bank_name') }}</label>
                                <p class="value-dark">
                                    {{ ucfirst($userDetail->accountDetail->bank_name ?? __('index.not_available')) }}</p>
                            </div>
                        </div>
                        <div class="detail-item-row">
                            <div class="w-100"><label class="label-muted">{{ __('index.account_number') }}</label>
                                <p class="value-dark">
                                    {{ $userDetail->accountDetail->bank_account_no ?? __('index.not_available') }}</p>
                            </div>
                        </div>
                        <div class="detail-item-row">
                            <div class="w-100"><label class="label-muted">{{ __('index.account_type') }}</label>
                                <p class="value-dark">
                                    {{ ucfirst($userDetail->accountDetail->bank_account_type ?? __('index.not_available')) }}
                                </p>
                            </div>
                        </div>
                        <div class="detail-item-row">
                            <div class="w-100"><label class="label-muted">{{ __('index.account_holder') }}</label>
                                <p class="value-dark">
                                    {{ ucfirst($userDetail->accountDetail->account_holder ?? __('index.not_available')) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Employee Documents & Contract Section -->
    <section class="content">
        <div class="detail-header-wrapper">
            <div class="header-text-box">
                <h3 style="color:#057db0;">Employee Documents & Contract</h3>
                <p>Identity proofs, employment files, and contract documents</p>
            </div>
        </div>
        
        <div class="row">
            <!-- Employee Documents Column -->
            <div class="col-lg-6 mb-4">
                <div class="info-card-modern">
                    <div class="card-header">
                        <h6 class="card-title">Employee Documents</h6>
                    </div>
                    <div class="card-body p-4">
                        @php
                            $documents = $userDetail->employeeDocuments->employee_document ?? [];
                            if (is_string($documents)) {
                                $documents = json_decode($documents, true);
                            }
                        @endphp
                        
                        @if (empty($documents))
                            <p class="text-muted text-center py-4">No documents uploaded</p>
                        @else
                            <div class="row">
                                @foreach ($documents as $doc)
                                    <div class="col-lg-6 col-md-4 col-sm-6 col-12 mb-3">
                                        <div class="document-card">
                                            @if (in_array(strtolower(pathinfo($doc, PATHINFO_EXTENSION)), ['pdf', 'doc', 'docx']))
                                                <div class="document-preview-container" style="height:120px;display:flex;align-items:center;justify-content:center;background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;">
                                                    <i class="fas fa-file-pdf" style="font-size:36px;color:#dc3545;"></i>
                                                </div>
                                            @else
                                                <img src="{{ asset('uploads/user/emp-documents/' . $doc) }}"
                                                    class="img-fluid rounded" style="height:120px;width:100%;object-fit:cover;border-radius:8px;" alt="Document">
                                            @endif
                                            
                                            <div class="document-actions mt-2">
                                                <a href="{{ asset('uploads/user/emp-documents/' . $doc) }}" download="{{ $doc }}" class="btn btn-sm btn-success w-100">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Employee Contract Column -->
            <div class="col-lg-6 mb-4">
                <div class="info-card-modern">
                    <div class="card-header">
                        <h6 class="card-title">Contract Document</h6>
                    </div>
                    <div class="card-body p-4">
                        @if (!empty($userDetail->employeeDocuments->employee_contract))
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <div class="document-card">
                                        @if (in_array(strtolower(pathinfo($userDetail->employeeDocuments->employee_contract, PATHINFO_EXTENSION)), ['pdf', 'doc', 'docx']))
                                            <div class="document-preview-container" style="height:120px;display:flex;align-items:center;justify-content:center;background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;">
                                                <i class="fas fa-file-contract" style="font-size:36px;color:#28a745;"></i>
                                            </div>
                                        @else
                                            <img src="{{ asset('uploads/user/emp-documents/' . $userDetail->employeeDocuments->employee_contract) }}"
                                                class="img-fluid rounded" style="height:120px;width:100%;object-fit:cover;border-radius:8px;" alt="Contract">
                                        @endif
                                        
                                        <div class="document-actions mt-2">
                                            <a href="{{ asset('uploads/user/emp-documents/' . $userDetail->employeeDocuments->employee_contract) }}" download="{{ $userDetail->employeeDocuments->employee_contract }}" class="btn btn-sm btn-success w-100">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted text-center py-4">No contract document uploaded</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
    .document-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        transition: transform 0.2s ease-in-out;
    }
    
    .document-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .document-actions {
        display: flex;
        gap: 8px;
        justify-content: center;
    }
    
    .document-actions .btn {
        font-size: 12px;
        padding: 6px 12px;
    }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection
