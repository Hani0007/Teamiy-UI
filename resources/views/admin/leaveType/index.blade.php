@extends('layouts.master')
@section('styles')
<style>
    .swal2-deny {
    border-color: transparent !important;
   }
</style>
@endsection
@section('title', __('index.leave_type'))

@section('main-content')
<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
    @include('admin.section.flash_message')

    {{-- 1. Modern Breadcrumbs & Top Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.leave_type') }}</h2>
            @include('admin.leaveType.common.breadcrumb')
            <!--<nav aria-label="breadcrumb" class="mt-1">
                <ol class="breadcrumb" style="background: transparent; padding: 0; margin: 0;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: #94a3b8; text-decoration: none; font-size: 12px;">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #057db0; font-weight: 600; font-size: 12px;">Leave Configuration</li>
                </ol>
            </nav>-->
        </div>

        @canany(['leave_type_create','access_admin_leave'])
            <button class="btn btn-primary create-leaveType">
                <i data-feather="plus" style="width: 20px;"></i>
                <span>{{ __('index.add_leave_type') }}</span>
            </button>
        @endcanany
    </div>

    {{-- 2. Glass-morphism Filter Panel --}}
    <div class="glass-filter-panel mb-5 shadow-sm border-0" style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-radius: 20px; padding: 25px; border: 1px solid #ffffff;">
        <form action="{{ route('admin.leaves.index') }}" method="get" class="row g-3 align-items-end">
            
            @if(!isset(auth()->user()->branch_id))
                <div class="col-xxl-4 col-xl-4 col-md-6">
                    <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-uppercase">{{ __('index.branch') }}</label>
                    <select class="form-select shadow-none modern-select" name="branch_id" id="branch" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                        <option {{ !isset($filterParameters['branch_id']) || old('branch_id') ? 'selected': ''}} disabled>{{ __('index.select_branch') }}</option>
                        @if(isset($companyDetail))
                            @foreach($companyDetail->branches()->get() as $key => $branch)
                                <option value="{{$branch->id}}" {{ (isset($filterParameters['branch_id']) && $filterParameters['branch_id'] == $branch->id) ? 'selected': '' }}>
                                    {{ucfirst($branch->name)}}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @endif

            <div class="col-xxl-4 col-xl-4 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-uppercase">{{ __('index.leave_type') }}</label>
                <div style="position: relative;">
                    <i data-feather="file-text" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                    <input type="text" name="type" value="{{ $filterParameters['type'] }}" class="form-control shadow-none" placeholder="Search leave type..." style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; padding-left: 45px; font-size: 14px;">
                </div>
            </div>

            <div class="col-xxl-4 col-xl-4 col-md-12">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn w-100" style="background: #057db0; color: white; height: 48px; border-radius: 12px; font-weight: 600; transition: all 0.3s ease;">
                        {{ __('index.filter') }}
                    </button>
                    
                    <a href="{{route('admin.posts.index')}}" class="btn-theme-outline w-100 text-decoration-none d-flex align-items-center justify-content-center" 
                   style="height: 48px; border: 1px solid #e2e8f0; border-radius: 12px; color: #64748b; background: #fff; font-weight: 600;">
                    {{ __('index.reset') }}
                </a>
                </div>
            </div>
        </form>
    </div>

    {{-- 3. Cards Grid --}}
    <div class="row g-4 justify-content-start">
        @forelse($leaveTypes as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card shadow-sm border-0" style="border-radius: 20px; overflow: hidden; background: white; transition: transform 0.3s ease;">
                    <div class="card-glossy-header" style="position: relative; padding: 20px; background: linear-gradient(135deg, #057db0 0%, #046690 100%); color: white;">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square" style="background: rgba(255,255,255,0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
                                <i data-feather="file-text" style="width: 20px;"></i>
                            </div>
                            <label class="switch-modern">
                                <input class="toggleStatus" href="{{route('admin.leaves.toggle-status',$value->id)}}"
                                       type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                <span class="slider-modern round"></span>
                            </label>
                        </div>
                        
                        <h4 class="branch-name-display mt-3 mb-1" style="font-weight: 700; letter-spacing: 0.5px;">{{ucfirst($value->name)}}</h4>

                        <div class="d-flex justify-content-between align-items-center mt-2 position-relative" style="z-index: 2;">
                            <span class="branch-ref-pill" style="background: rgba(255,255,255,0.15); padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: 600;">ID: #{{$value->id}}</span>
                            
                            <div class="d-flex gap-3">
                                @canany(['leave_type_edit','access_admin_leave'])
                                    <a href="javascript:void(0)" class="edit-leaveType p-1" 
                                       data-id="{{ $value->id }}" 
                                       data-href="{{ route('admin.leaves.edit', $value->id) }}" 
                                       title="Edit"
                                       style="background:#FFFF;color:#057DB0;border-radius:20%">
                                        <i data-feather="edit-3" style="width: 15px; height: 15px;"></i>
                                    </a>
                                @endcanany
                                
                                @canany(['leave_type_delete','access_admin_leave'])
                                    <a href="javascript:void(0)" 
                                       data-href="{{route('admin.leaves.delete',$value->id)}}" 
                                       class="deleteLeaveType cursor-pointer p-1" 
                                       title="Delete"
                                       style="background:#FFFF;color:#FB8233;border-radius:20%">
                                        <i data-feather="trash-2" style="width: 15px; height: 15px;"></i>
                                    </a>
                                @endcanany
                            </div>
                        </div>
                    </div>

                    <div class="card-white-body" style="padding: 20px;">
                        <div class="info-listing d-flex flex-column gap-3">
                            <div class="info-item-box d-flex align-items-center gap-3">
                                <div class="icon-circle" style="background: #f1f5f9; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #057db0;">
                                    <i data-feather="credit-card" style="width: 14px;"></i>
                                </div>
                                <div class="text-content">
                                    <small class="text-muted d-block" style="font-size: 11px; font-weight: 600;">{{ __('index.is_paid') }}</small>
                                    <p class="mb-0 fw-bold" style="font-size: 13px; color: #334155;">{{($value->leave_allocated) ? __('index.yes'):__('index.no')}}</p>
                                </div>
                            </div>
                            <div class="info-item-box d-flex align-items-center gap-3">
                                <div class="icon-circle" style="background: #f1f5f9; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #057db0;">
                                    <i data-feather="clock" style="width: 14px;"></i>
                                </div>
                                <div class="text-content">
                                    <small class="text-muted d-block" style="font-size: 11px; font-weight: 600;">{{ __('index.allocated_days') }}</small>
                                    <p class="mb-0 fw-bold" style="font-size: 13px; color: #334155;">{{($value->leave_allocated) ?? '-'}} Days</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <i data-feather="info" style="width: 50px; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3 fw-medium">{{ __('index.no_records_found') }}</p>
                </div>
            </div>
        @endforelse
    </div>
</section>

{{-- Modern Modal Structure --}}
<div class="modal fade" id="leaveTypeModal" tabindex="-1" aria-labelledby="leaveTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"> 
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            
            <div class="modal-header border-0 position-relative" style="background: linear-gradient(135deg, #057db0 0%, #046690 100%); padding: 25px;">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1;"></div>
                <div class="d-flex align-items-center position-relative" style="z-index: 2;">
                    <div style="background: rgba(255,255,255,0.2); width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px; backdrop-filter: blur(5px);">
                        <i data-feather="calendar" style="color: white; width: 22px;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-0" id="leaveTypeModalLabel">{{ __('index.add_leave_type') }}</h5>
                        <p class="text-white-50 mb-0 small" style="font-size: 11px;">Configure your company's leave policies</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-1" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" style="background: #f8fafc;">
                <form id="leaveTypeForm" class="forms-sample" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <div class="row g-4">
                        @if(!isset(auth()->user()->branch_id))
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold small text-muted text-uppercase" style="letter-spacing: 0.5px;">{{ __('index.branch') }} <span class="text-danger">*</span></label>
                                    <select class="form-select modern-select select2-modal" id="branch_id" name="branch_id" style="border-radius: 10px; padding: 10px; border: 1px solid #e2e8f0;">
                                        <option selected disabled>{{ __('index.select_branch') }}</option>
                                        @if(isset($companyDetail))
                                            @foreach($companyDetail->branches()->get() as $branch)
                                                <option value="{{$branch->id}}">{{ucfirst($branch->name)}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label fw-bold small text-muted text-uppercase" style="letter-spacing: 0.5px;">{{ __('index.leave_type_name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="{{ __('index.leave_type_placeholder') }}" style="border-radius: 10px; padding: 10px; border: 1px solid #e2e8f0;">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label fw-bold small text-muted text-uppercase" style="letter-spacing: 0.5px;">{{ __('index.is_paid_leave') }} <span class="text-danger">*</span></label>
                                <select class="form-select modern-select" id="leave_paid" required name="leave_paid" style="border-radius: 10px; padding: 10px; border: 1px solid #e2e8f0;">
                                    <option selected disabled>Select Status</option>
                                    <option value="1">{{ __('index.yes') }}</option>
                                    <option value="0">{{ __('index.no') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 leaveAllocated">
                            <div class="form-group">
                                <label class="form-label fw-bold small text-muted text-uppercase" style="letter-spacing: 0.5px;">{{ __('index.leave_allocated_days') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" min="1" class="form-control" id="leave_allocated" name="leave_allocated" placeholder="0" style="border-radius: 10px 0 0 10px; padding: 10px; border: 1px solid #e2e8f0;">
                                    <span class="input-group-text bg-light small fw-bold" style="border-radius: 0 10px 10px 0; border: 1px solid #e2e8f0; border-left: 0;">Days</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary px-4 fw-bold text-white" data-bs-dismiss="modal" style="border-radius: 12px; color: #64748b; border: 1px solid #e2e8f0;">
                            {{ __('index.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold" style="background: linear-gradient(135deg, #057db0 0%, #046690 100%); border: none; border-radius: 12px; min-width: 140px; box-shadow: 0 4px 15px rgba(5, 125, 176, 0.2);">
                            <span id="submitButtonText">{{ __('index.save') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            feather.replace();
        });
    </script>
    @include('admin.leaveType.common.scripts')
@endsection