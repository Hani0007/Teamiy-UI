@extends('layouts.master')

@section('title', __('index.users'))

@section('action', __('index.detail'))

@section('button')
    <div class="d-md-flex">
        <a href="{{ route('admin.users.edit', $userDetail->id) }}">
            <button class="btn btn-sm btn-secondary me-2 shadow-sm" style="border-radius: 8px; border: none; padding: 8px 15px;">
                <i class="link-icon" data-feather="edit" style="width: 16px;"></i> {{ __('index.edit_detail') }}
            </button>
        </a>

        <a href="{{ route('admin.users.index') }}">
            <button class="btn btn-sm btn-primary shadow-sm" style="border-radius: 8px; background: #057db0; border: none; padding: 8px 15px;">
                <i class="link-icon" data-feather="arrow-left" style="width: 16px;"></i> {{ __('index.back') }}
            </button>
        </a>
    </div>
@endsection

@section('main-content')

<section class="content">

    @include('admin.section.flash_message')
    @include('admin.users.common.breadcrumb')

    {{-- Profile Header Card --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-0">
            {{-- Glossy Background Header --}}
            <div class="position-relative" style="background: linear-gradient(135deg, #057db0 0%, #046690 100%); height: 100px;">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1;"></div>
            </div>
            
            <div class="px-4 pb-4" style="margin-top: -50px;">
                <div class="d-md-flex align-items-end text-md-start text-center">
                    <div class="position-relative">
                        @php
                            $avatarPath = \App\Models\Admin::AVATAR_UPLOAD_PATH . $userDetail->avatar;
                            $avatarUrl = (isset($userDetail->avatar) && $userDetail->avatar && file_exists(public_path($avatarPath))) 
                                         ? asset($avatarPath) 
                                         : asset('assets/images/img.png');
                        @endphp
                        <img class="wd-100 ht-100 rounded-circle border border-4 border-white shadow" 
                             style="object-fit: cover; background: white;"
                             src="{{ $avatarUrl }}" alt="profile">
                    </div>
                    <div class="ms-md-3 mt-md-0 mt-2">
                        <h4 class="fw-bold mb-1 text-dark">{{ ucfirst($userDetail->name) }}</h4>
                        <p class="text-muted mb-0"><i data-feather="mail" style="width: 14px;"></i> {{ $userDetail->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row profile-body">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; overflow: hidden;">
                
                <div class="card-header border-0 bg-white pt-4 px-4">
                    <div class="d-flex align-items-center">
                        <div style="background: rgba(5, 125, 176, 0.1); width: 35px; height: 35px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                            <i data-feather="user" style="color: #057db0; width: 18px;"></i>
                        </div>
                        <h6 class="card-title mb-0 fw-bold text-dark">{{ __('index.user_detail') }}</h6>
                    </div>
                </div>

                <div class="card-body p-4">
                    {{-- Username Field --}}
                    <div class="mb-4">
                        <div class="p-3" style="background: #f8fafc; border-radius: 15px; border: 1px solid #e2e8f0;">
                            <label class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 0.5px; font-size: 10px;">{{ __('index.username') }}</label>
                            <p class="mb-0 fw-bold text-dark">{{ $userDetail->username }}</p>
                        </div>
                    </div>

                    {{-- Status Field --}}
                    <div>
                        <div class="p-3" style="background: #f8fafc; border-radius: 15px; border: 1px solid #e2e8f0;">
                            <label class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 0.5px; font-size: 10px;">{{ __('index.is_active') }}</label>
                            <div class="d-block">
                                @if($userDetail->is_active == 1)
                                    <span class="badge bg-success-subtle text-success px-3 py-2" style="border-radius: 8px;">
                                        <i data-feather="check-circle" style="width: 12px;"></i> {{ __('index.yes') }}
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2" style="border-radius: 8px;">
                                        <i data-feather="x-circle" style="width: 12px;"></i> {{ __('index.no') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>
@endsection