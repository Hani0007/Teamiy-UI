@extends('layouts.master')

@section('title', __('index.users'))
@section('action', __('index.detail'))

@section('main-content')
<style>
    /* Premium Glassmorphism Theme */
    .user-detail-card {
        background: #ffffff;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .user-header {
        background: linear-gradient(135deg, #057db0 0%, #046690 100%);
        padding: 40px;
        position: relative;
    }

    .user-header::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: url('https://www.transparenttextures.com/patterns/cubes.png');
        opacity: 0.1;
    }

    /* Profile Image Styling */
    .profile-img-container {
        width: 100px;
        height: 100px;
        border-radius: 25px;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        background: #fff;
        object-fit: cover;
    }

    /* Clickable Edit Image */
    .edit-icon-img {
        width: 38px;
        height: 38px;
        transition: all 0.3s ease;
        cursor: pointer;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));
    }
    .edit-icon-img:hover {
        transform: scale(1.1) rotate(8deg);
    }

    /* Info Tiles */
    .info-tile {
        background: #f8fafc;
        border-radius: 15px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        transition: 0.3s;
        height: 100%;
    }
    .info-tile:hover {
        border-color: #057db0;
        background: #fff;
        box-shadow: 0 5px 15px rgba(5, 125, 176, 0.05);
    }

    .label-text {
        font-size: 10px;
        text-transform: uppercase;
        color: #94a3b8;
        font-weight: 800;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: block;
    }

    .value-text {
        font-size: 15px;
        color: #1e293b;
        font-weight: 700;
    }

    /* Back Button - Top Right */
    .btn-theme-back {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.4);
        padding: 8px 20px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
        backdrop-filter: blur(5px);
        text-decoration: none;
    }
    .btn-theme-back:hover {
        background: white;
        color: #057db0;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        background: rgba(5, 125, 176, 0.1);
        color: #057db0;
        border: 1px solid rgba(5, 125, 176, 0.2);
    }

    .section-heading {
        font-size: 16px;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }
</style>

<section class="content">
    @include('admin.section.flash_message')

    <div class="user-detail-card shadow-sm">
        {{-- Header Section --}}
        <div class="user-header">
            <div class="d-flex align-items-center justify-content-between position-relative" style="z-index: 1;">
                <div class="d-flex align-items-center">
                    @php
                        $avatarPath = \App\Models\Admin::AVATAR_UPLOAD_PATH . $userDetail->avatar;
                        $avatarUrl = (isset($userDetail->avatar) && $userDetail->avatar && file_exists(public_path($avatarPath))) 
                                     ? asset($avatarPath) : asset('assets/images/img.png');
                    @endphp
                    <img src="{{ $avatarUrl }}" class="profile-img-container me-4" alt="User">
                    
                    <div class="text-white">
                        <h2 class="fw-bold mb-1">{{ ucfirst($userDetail->name) }}</h2>
                        <p class="mb-0 opacity-75"><i data-feather="mail" style="width: 14px;"></i> {{ $userDetail->email }}</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    
                    
                    {{-- Back Button (Right Side) --}}
                    
                </div>
            </div>
        </div>

        <div class="card-body p-4 p-md-5">
            {{-- User Detail Heading --}}
            <div class="section-heading">
                <div style="width: 4px; height: 20px; background: #057db0; border-radius: 10px;"></div>
                {{ __('index.user_detail') }}
            </div>

            <div class="row g-4">
                {{-- Username Field --}}
                <div class="col-md-6">
                    <div class="info-tile">
                        <span class="label-text">{{ __('index.username') }}</span>
                        <div class="value-text">
                            <i data-feather="user" class="me-2 text-primary" style="width: 16px;"></i>
                            {{ $userDetail->username }}
                        </div>
                    </div>
                </div>

                {{-- Status Field (Blue theme, No Green) --}}
                <div class="col-md-6">
                    <div class="info-tile">
                        <span class="label-text">{{ __('index.is_active') }}</span>
                        <div>
                            @if($userDetail->is_active == 1)
                                <span class="status-badge">
                                    <i data-feather="shield" class="me-1" style="width: 14px;"></i> Active Account
                                </span>
                            @else
                                <span class="badge bg-light text-muted border px-3 py-2" style="border-radius: 8px;">
                                    <i data-feather="slash" class="me-1" style="width: 14px;"></i> Disabled
                                </span>
                            @endif
                        </div>
                        
                    </div>
                </div>
                
            </div>
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('admin.users.index') }}" class="branch-back-btn">
                    <i data-feather="arrow-left" style="width: 16px;"></i> {{ __('index.back') }}
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection