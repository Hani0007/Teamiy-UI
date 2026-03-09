@extends('layouts.master')

@section('title', __('company_profile'))

@section('main-content')

<section class="content">
    @include('admin.section.flash_message')

    <div class="profile-header-strip">
        <div class="company-main-info">
            <div class="company-logo-circle">
                @if($companyDetail->logo)
                    <img src="{{ asset('uploads/company/logo/'.$companyDetail->logo) }}" style="width:100%; height:100%; object-fit:cover;">
                @else
                    <i data-feather="briefcase" class="text-muted" style="width:30px;"></i>
                @endif
            </div>
            <div>
                <h3 class="mb-1" style="font-weight: 800; color: #1e293b;">{{ $companyDetail->name ?? 'N/A' }}</h3>
                <div class="d-flex align-items-center gap-2">
                    <span class="status-pill-active">
                        <i data-feather="check-circle" style="width:12px; margin-top:-2px;"></i> {{ $companyDetail->is_active ? 'Verified Account' : 'Inactive' }}
                    </span>
                    <span class="text-muted small">|</span>
                    <span class="text-muted small">{{ $companyDetail->industry->name ?? 'General Industry' }}</span>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.company.edit', $companyDetail->id) }}">
            <button class="btn btn-edit-glow d-flex align-items-center gap-2">
                <i data-feather="edit-2" style="width: 16px;"></i> {{ __('edit') }}
            </button>
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="info-card-modern shadow-sm">
                <div class="card-header-custom">
                    <i data-feather="info" class="text-primary" style="width:18px;"></i>
                    <h5>{{ __('company_information') }}</h5>
                </div>
                <div class="info-grid row">
                    <div class="col-md-6 info-item">
                        <div class="info-icon"><i data-feather="mail" style="width:16px;"></i></div>
                        <div class="info-content">
                            <label>{{ __('email') }}</label>
                            <span>{{ $companyDetail->admin->email ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 info-item">
                        <div class="info-icon"><i data-feather="phone" style="width:16px;"></i></div>
                        <div class="info-content">
                            <label>{{ __('contact_number') }}</label>
                            <span>+{{ $companyDetail->country_code }} {{ $companyDetail->contact_number }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 info-item">
                        <div class="info-icon"><i data-feather="globe" style="width:16px;"></i></div>
                        <div class="info-content">
                            <label>{{ __('website_url') }}</label>
                            <a href="{{ $companyDetail->website_url }}" target="_blank" class="text-primary">{{ $companyDetail->website_url ?? 'N/A' }}</a>
                        </div>
                    </div>
                    <div class="col-md-6 info-item">
                        <div class="info-icon"><i data-feather="users" style="width:16px;"></i></div>
                        <div class="info-content">
                            <label>{{ __('employees') }}</label>
                            <span>{{ $companyDetail->no_of_employees ?? '0' }} {{ __('employees') }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-header-custom" style="border-top: 1px solid #f1f5f9;">
                    <i data-feather="map-pin" class="text-primary" style="width:18px;"></i>
                    <h5>{{ __('address_information') }}</h5>
                </div>
                <div class="info-grid row">
                    <div class="col-md-12 info-item">
                        <div class="info-content">
                            <label>{{ __('address') }}</label>
                            <span>{{ $companyDetail->address ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-content">
                            <label>{{ __('city') }}</label>
                            <span>{{ $companyDetail->city }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-content">
                            <label>{{ __('state') }}</label>
                            <span>{{ $companyDetail->province }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-content">
                            <label>{{ __('country') }}</label>
                            <span>{{ $companyDetail->countries->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="info-card-modern shadow-sm">
                <div class="card-header-custom">
                    <i data-feather="settings" class="text-primary" style="width:18px;"></i>
                    <h5>{{ __('operational_settings') }}</h5>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-icon" style="background: rgba(251, 130, 51, 0.1); color: #fb8233;"><i data-feather="calendar" style="width:16px;"></i></div>
                        <div class="info-content">
                            <label>{{ __('weekend') }}</label>
                            <div class="mt-1">
                                @if($companyDetail->weekend && is_array($companyDetail->weekend))
                                    @foreach($companyDetail->weekend as $day)
                                        @php $days = [0=>__('sunday'), 1=>__('monday'), 2=>__('tuesday'), 3=>__('wednesday'), 4=>__('thursday'), 5=>__('friday'), 6=>__('saturday')]; @endphp
                                        <span class="day-pill">{{ $days[$day] }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i data-feather="dollar-sign" style="width:16px;"></i></div>
                        <div class="info-content">
                            <label>{{ __('currency') }}</label>
                            <span>{{ $companyDetail->currency->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="info-item mb-0">
                        <div class="info-icon"><i data-feather="hash" style="width:16px;"></i></div>
                        <div class="info-content">
                            <label>{{ __('postal_code') }}</label>
                            <span>{{ $companyDetail->postal_code ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 mt-2" style="background: #f8fafc; border-top: 1px solid #f1f5f9;">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted small">{{ __('last_updated_profile') }}</span>
                        <span class="badge bg-white text-dark border fw-normal">{{ date('d M, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });
</script>
@endsection