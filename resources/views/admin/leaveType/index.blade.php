@extends('layouts.master')

@section('title', __('index.leave_type'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0;">
                {{ __('index.leave_type') }}
            </h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px;">
                <i data-feather="calendar" style="width: 14px; vertical-align: middle;"></i> Leave Configuration
            </p>
        </div>
        
        @canany(['leave_type_create','access_admin_leave'])
            <button class="btn-premium-add create-leaveType">
                <i data-feather="plus" style="width: 20px;"></i>
                <span>{{ __('index.add_leave_type') }}</span>
            </button>
        @endcanany
    </div>

    {{-- Cards Grid --}}
    <div class="row g-4 justify-content-start">
        @forelse($leaveTypes as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    <div class="card-glossy-header">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="file-text"></i>
                            </div>
                            <label class="switch-modern">
                                <input class="toggleStatus" href="{{route('admin.leaves.toggle-status',$value->id)}}"
                                       type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                <span class="slider-modern round"></span>
                            </label>
                        </div>
                        
                        <h4 class="branch-name-display">{{ucfirst($value->name)}}</h4>

                        {{-- ID and Action Icons in the same line --}}
                        <div class="d-flex justify-content-between align-items-center mt-2 position-relative" style="z-index: 2;">
                            <span class="branch-ref-pill">Leave ID: #{{$value->id}}</span>
                            
                            <div class="d-flex gap-2">
                                @canany(['leave_type_edit','access_admin_leave'])
                                    <a href="javascript:void(0)" class="edit-leaveType text-white" 
                                       data-id="{{ $value->id }}" 
                                       data-href="{{ route('admin.leaves.edit', $value->id) }}" 
                                       title="Edit">
                                        <i data-feather="edit-3" style="width: 15px; height: 15px;"></i>
                                    </a>
                                @endcanany
                                
                                @canany(['leave_type_delete','access_admin_leave'])
                                    <a href="javascript:void(0)" 
                                       data-href="{{route('admin.leaves.delete',$value->id)}}" 
                                       class="deleteLeaveType cursor-pointer text-white" 
                                       title="Delete">
                                        <i data-feather="trash-2" style="width: 15px; height: 15px;"></i>
                                    </a>
                                @endcanany
                            </div>
                        </div>
                    </div>

                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="credit-card"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.is_paid') }}</small>
                                    <p>{{($value->leave_allocated) ? __('index.yes'):__('index.no')}}</p>
                                </div>
                            </div>
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="clock"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.allocated_days') }}</small>
                                    <p>{{($value->leave_allocated) ?? '-'}} Days</p>
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
                    <p class="text-muted mt-3">{{ __('index.no_records_found') }}</p>
                </div>
            </div>
        @endforelse
    </div>
</section>

{{-- Modal Structure (Same as before) --}}
<!-- <div class="modal fade" id="leaveTypeModal" tabindex="-1" aria-labelledby="leaveTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"> 
        <div class="modal-content teamy-main-card border-0">
            <div class="modal-header border-bottom-0 pb-0">
                <div class="section-title-wrapper">
                    <div class="section-icon" style="background: rgba(5, 125, 176, 0.1); color: #057db0;">
                        <i class="fa fa-calendar-plus"></i>
                    </div>
                    <div class="section-heading-text">
                        <h4 class="modal-title" id="leaveTypeModalLabel" style="color: #057db0;">{{ __('index.add_leave_type') }}</h4>
                        <p class="mb-0 small text-muted">Configure your company's leave policies</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form id="leaveTypeForm" class="forms-sample" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="row">
                        @if(!isset(auth()->user()->branch_id))
                            <div class="col-lg-6 mb-4">
                                <label class="form-label fw-bold small">{{ __('index.branch') }} <span class="text-danger">*</span></label>
                                <select class="form-select modern-select select2-modal" id="branch_id" name="branch_id">
                                    <option selected disabled>{{ __('index.select_branch') }}</option>
                                    @if(isset($companyDetail))
                                        @foreach($companyDetail->branches()->get() as $branch)
                                            <option value="{{$branch->id}}">{{ucfirst($branch->name)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        @endif

                        <div class="col-lg-6 mb-4">
                            <label class="form-label fw-bold small">{{ __('index.leave_type_name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="{{ __('index.leave_type_placeholder') }}">
                        </div>

                        <div class="col-lg-6 mb-4">
                            <label class="form-label fw-bold small">{{ __('index.is_paid_leave') }} <span class="text-danger">*</span></label>
                            <select class="form-select modern-select" id="leave_paid" required name="leave_paid">
                                <option selected disabled>Select Status</option>
                                <option value="1">{{ __('index.yes') }}</option>
                                <option value="0">{{ __('index.no') }}</option>
                            </select>
                        </div>

                        <div class="col-lg-6 mb-4 leaveAllocated">
                            <label class="form-label fw-bold small">{{ __('index.leave_allocated_days') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" min="1" class="form-control" id="leave_allocated" name="leave_allocated" placeholder="0">
                                <span class="input-group-text bg-light small">Days</span>
                            </div>
                        </div>
                    </div>

                    <div class="section-divider mt-2"></div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 8px;">
                            {{ __('index.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary px-4" style="background: #057db0; border: none; border-radius: 8px; min-width: 120px;">
                            <i class="fa fa-save me-1"></i> <span id="submitButtonText">{{ __('index.save') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->
<div class="modal fade" id="leaveTypeModal" tabindex="-1" aria-labelledby="leaveTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"> 
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            
            {{-- Modern Glossy Header --}}
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal" style="border-radius: 12px; color: #64748b; border: 1px solid #e2e8f0;">
                            {{ __('index.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold" style="background: linear-gradient(135deg, #057db0 0%, #046690 100%); border: none; border-radius: 12px; min-width: 140px; box-shadow: 0 4px 15px rgba(5, 125, 176, 0.2);">
                            <i data-feather="save" style="width: 16px; margin-right: 5px; vertical-align: middle;"></i> 
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