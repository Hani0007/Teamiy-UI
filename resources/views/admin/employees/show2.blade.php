@extends('layouts.master')

@section('title', __('index.show_user_details'))

@section('action', __('index.detail'))

{{-- Buttons ko section se hata kar seedha content mein heading ke saath rakha hai --}}
@section('button')
@endsection

@section('main-content')

<section class="content">
    @include('admin.section.flash_message')

    <div class="detail-header-wrapper">
        <div class="header-text-box">
            <h3>Employee Details</h3>
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
        @if(isset($userDetail->avatar) && $userDetail->avatar && file_exists(public_path(\App\Models\User::AVATAR_UPLOAD_PATH.$userDetail->avatar)))
            <img class="profile-avatar-circle rounded-circle" src="{{ asset(\App\Models\User::AVATAR_UPLOAD_PATH . $userDetail->avatar) }}" alt="profile">
        @else
            <img class="profile-avatar-circle rounded-circle" src="{{ asset('assets/images/img.png') }}" alt="profile">
        @endif
        
        <div class="ms-md-4 mt-3 mt-md-0">
            <h2 class="fw-bold mb-1" style="font-size: 28px; letter-spacing: -0.5px;">{{ ucfirst($userDetail->name) }}</h2>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-white text-dark py-1 px-3 fw-bold" style="font-size: 12px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); background-color: #fb8233 !important; color: #fff !important;">Code: {{ $userDetail->employee_code }}</span>
                <span class="small"><i data-feather="mail" style="width: 14px; vertical-align: middle;"></i> {{ $userDetail->email }}</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="info-card-modern">
                <div class="card-header"><h6 class="card-title">{{ __('index.user_detail') }}</h6></div>
                <div class="card-body p-0">
                    <div class="detail-item-row">
                        <div class="detail-split-col"><label class="label-muted">{{ __('index.username') }}</label><p class="value-dark">{{ $userDetail->username }}</p></div>
                        <div class="detail-split-col"><label class="label-muted">{{ __('index.gender') }}</label><p class="value-dark">{{ ucfirst($userDetail->gender) }}</p></div>
                    </div>
                    <div class="detail-item-row">
                        <div class="detail-split-col"><label class="label-muted">{{ __('index.marital_status') }}</label><p class="value-dark">{{ ucfirst($userDetail->marital_status) }}</p></div>
                        <div class="detail-split-col"><label class="label-muted">{{ __('index.address') }}</label><p class="value-dark">{{ ucfirst($userDetail->address) }}</p></div>
                    </div>
                    <div class="detail-item-row">
                        <div class="detail-split-col"><label class="label-muted">{{ __('index.phone_number') }}</label><p class="value-dark">{{ $userDetail->phone }}</p></div>
                        <div class="detail-split-col"><label class="label-muted">{{ __('index.date_of_birth') }}</label><p class="value-dark">{{ isset($userDetail->dob) ? \App\Helpers\AppHelper::formatDateForView($userDetail->dob) : '—' }}</p></div>
                    </div>
                    <div class="detail-item-row">
                        <div class="detail-split-col"><label class="label-muted">{{ __('index.role') }}</label><p class="value-dark">{{ $userDetail->role ? ucfirst($userDetail->role->name) : __('index.not_applicable') }}</p></div>
                        <div class="detail-split-col">
                            <label class="label-muted">{{ __('index.is_active') }}</label>
                            <p class="value-dark">
                                @if($userDetail->is_active == 1)
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
                <div class="card-header"><h6 class="card-title">{{ __('index.office_detail') }}</h6></div>
                <div class="card-body p-0">
                    <div class="detail-item-row"><div class="w-100"><label class="label-muted">{{ __('index.branch_name') }}</label><p class="value-dark">{{ $userDetail->branch ? ucfirst($userDetail->branch->name) : __('index.not_applicable') }}</p></div></div>
                    <div class="detail-item-row"><div class="w-100"><label class="label-muted">{{ __('index.department_name') }}</label><p class="value-dark">{{ $userDetail->department ? ucfirst($userDetail->department->dept_name) : __('index.not_applicable') }}</p></div></div>
                    <div class="detail-item-row"><div class="w-100"><label class="label-muted">{{ __('index.post_name') }}</label><p class="value-dark">{{ $userDetail->post ? ucfirst($userDetail->post->post_name) : __('index.not_applicable') }}</p></div></div>
                    <div class="detail-item-row">
                        <div class="detail-split-col"><label class="label-muted">{{ __('index.employment_type') }}</label><p class="value-dark" style="color:#fb8233 !important;">{{ ucfirst($userDetail->employment_type) }}</p></div>
                        <div class="detail-split-col"><label class="label-muted">{{ __('index.workspace') }}</label><p class="value-dark">{{ $userDetail->workspace_type == 1 ? __('index.office') : __('index.home') }}</p></div>
                    </div>
                    <div class="detail-item-row"><div class="w-100"><label class="label-muted">{{ __('index.joining_date') }}</label><p class="value-dark">{{ isset($userDetail->joining_date) ? \App\Helpers\AppHelper::formatDateForView($userDetail->joining_date) : __('index.not_applicable') }}</p></div></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="info-card-modern">
                <div class="card-header"><h6 class="card-title">{{ __('index.account_detail') }}</h6></div>
                <div class="card-body p-0">
                    <div class="detail-item-row"><div class="w-100"><label class="label-muted">{{ __('index.bank_name') }}</label><p class="value-dark">{{ ucfirst($userDetail->accountDetail->bank_name ?? __('index.not_available')) }}</p></div></div>
                    <div class="detail-item-row"><div class="w-100"><label class="label-muted">{{ __('index.account_number') }}</label><p class="value-dark">{{ $userDetail->accountDetail->bank_account_no ?? __('index.not_available') }}</p></div></div>
                    <div class="detail-item-row"><div class="w-100"><label class="label-muted">{{ __('index.account_type') }}</label><p class="value-dark">{{ ucfirst($userDetail->accountDetail->bank_account_type ?? __('index.not_available')) }}</p></div></div>
                    <div class="detail-item-row"><div class="w-100"><label class="label-muted">{{ __('index.account_holder') }}</label><p class="value-dark">{{ ucfirst($userDetail->accountDetail->account_holder ?? __('index.not_available')) }}</p></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection