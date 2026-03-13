@php use App\Models\Tada; @endphp
@php use Illuminate\Support\Str; @endphp
@php use App\Helpers\AppHelper; @endphp
@extends('layouts.master')
@section('action',__('index.tada_listing'))
@section('title', __('index.tada'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    <div id="showFlashMessageResponse">
        <div class="alert alert-danger error d-none">
            <p class="errorMessageDelete"></p>
        </div>
    </div>

    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0;">{{ __('index.tada') }}</h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px;">
                <i data-feather="file-text" style="width: 14px; vertical-align: middle;"></i> 
                {{ __('index.tada_listing') }}
            </p>
        </div>
        
        @can('create_tada')
            <a href="{{ route('admin.tadas.create') }}" style="text-decoration: none;">
                <button class="btn btn-primary" >
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.create_tada') }}</span>
                </button>
            </a>
        @endcan
    </div>

    @include('admin.tada.common.breadcrumb')

    {{-- Filter Panel --}}
    <div class="glass-filter-panel mb-5 p-4" style="background: #fff; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
        <form action="{{ route('admin.tadas.index') }}" method="get" class="row g-3 align-items-center">
            @if(!isset(auth()->user()->branch_id))
                <div class="col-lg-3 col-md-6">
                    <select class="form-select modern-select" id="branch_id" name="branch_id">
                        <option selected disabled>{{ __('index.select_branch') }}</option>
                        @if(isset($companyDetail))
                            @foreach($companyDetail->branches()->get() as $branch)
                                <option value="{{$branch->id}}" {{ (isset($filterParameters['branch_id']) && $filterParameters['branch_id'] == $branch->id) ? 'selected': '' }}>
                                    {{ucfirst($branch->name)}}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @endif
            <div class="col-lg-3 col-md-6">
                <select class="form-select modern-select" name="department_id" id="department_id">
                    <option selected disabled>{{ __('index.select_department') }}</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <select class="form-select modern-select" name="employee_id" id="employee_id">
                    <option selected disabled>{{ __('index.select_employee') }}</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <select class="form-select modern-select" id="status" name="status">
                    <option value="">{{ __('index.search_by_status') }}</option>
                    @foreach(Tada::STATUS as $statusVal)
                        <option value="{{$statusVal}}" {{ isset($filterParameters['status']) && $filterParameters['status'] == $statusVal ? 'selected':''}}>
                            {{ucfirst($statusVal)}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100" style="background: #057db0; border:none;border-radius: 11px;">{{ __('index.filter') }}</button>
                <a href="{{route('admin.tadas.index')}}" class="btn branch-back-btn w-100" style="border-radius: 11px;">{{ __('index.reset') }}</a>
            </div>
        </form>
    </div>

    {{-- Grid Layout --}}
    <div class="row g-4 justify-content-start">
        @forelse($tadaLists as $key => $value)
            @if($value->employeeDetail)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    {{-- Header --}}
                    <div class="card-glossy-header" style="background: linear-gradient(135deg, #057db0 0%, #046691 100%); position: relative; overflow: hidden; padding: 20px; border-radius: 15px 15px 0 0;">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square" style="background: rgba(255,255,255,0.2); width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                                <i data-feather="dollar-sign" style="color: #fff;"></i>
                            </div>
                            <div class="status-indicator">
                                @php
                                    $themeStatusColor = ($value->status == 'accepted') ? '#057db0' : '#FB8233';
                                @endphp
                                <span class="badge {{ $value->status != 'accepted' ? 'cursor-pointer' : '' }}" 
                                      style="background: {{ $themeStatusColor }}; border: 1px solid rgba(255,255,255,0.3);"
                                      @if($value->status != 'accepted')
                                          id="updateStatus"
                                          data-id="{{ $value->id }}"
                                          data-status="{{($value->status)}}"
                                          data-title="{{ ucfirst($value->title) }}"
                                          data-reason="{{($value->remark)}}"
                                          data-action="{{route('admin.tadas.update-status',$value->id)}}"
                                      @endif>
                                    {{ucfirst($value->status)}} 
                                    @if($value->status != 'accepted') <i data-feather="refresh-cw" style="width:10px; margin-left: 4px;"></i> @endif
                                </span>
                            </div>
                        </div>
                        <h4 class="branch-name-display text-white mt-3 mb-2" style="font-weight: 600;">{{ ucfirst(Str::limit($value->title, 22)) }}</h4>
                        <span style="background: rgba(255,255,255,0.15); color: #fff; padding: 3px 12px; border-radius: 6px; font-size: 11px; font-weight: 600; letter-spacing: 0.5px;">
                            ID: #{{$value->id}}
                        </span>
                    </div>

                    {{-- Body --}}
                    <div class="card-white-body" style="background: #fff; padding: 20px; border-radius: 0 0 15px 15px; border: 1px solid #f1f5f9; border-top: none;">
                        <div class="info-listing">
                            <div class="info-item-box d-flex align-items-center mb-3">
                                <div class="icon-circle" style="color: #057db0; background: #f0f7fa; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i data-feather="user" style="width: 16px;"></i>
                                </div>
                                <div class="text-content">
                                    <small style="color: #94a3b8; font-size: 10px; display: block; text-transform: uppercase;">Employee</small>
                                    <p style="font-weight: 600; color: #475569; margin: 0;">{{ $value->employeeDetail->name }}</p>
                                </div>
                            </div>

                            <div class="info-item-box d-flex align-items-center mb-3">
                                <div class="icon-circle" style="color: #FB8233; background: #fff5ed; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i data-feather="trending-up" style="width: 16px;"></i>
                                </div>
                                <div class="text-content">
                                    <small style="color: #94a3b8; font-size: 10px; display: block; text-transform: uppercase;">Expense (Rs)</small>
                                    <p style="font-weight: 700; color: #FB8233; margin: 0;">{{$currency}} {{number_format($value->total_expense)}}</p>
                                </div>
                            </div>

                            <div class="info-item-box d-flex align-items-center">
                                <div class="icon-circle" style="color: #057db0; background: #f0f7fa; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i data-feather="calendar" style="width: 16px;"></i>
                                </div>
                                <div class="text-content">
                                    <small style="color: #94a3b8; font-size: 10px; display: block; text-transform: uppercase;">Submitted Date</small>
                                    <p style="margin: 0; color: #475569; font-weight: 500;">{{ AppHelper::formatDateForView($value->created_at)}}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="stats-footer-box mt-4 pt-3" style="border-top: 1px solid #f1f5f9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="settlement-status">
                                    <span class="badge" style="font-size: 10px; background: {{ $value->is_settled ? '#057db0' : '#FB8233' }}; color: white; padding: 5px 10px;">
                                        {{ $value->is_settled == 1 ? 'PAID' : 'NOT PAID' }}
                                    </span>
                                </div>
                                <div class="action-dock d-flex gap-2">
                                    @can('show_tada_detail')
                                        <a href="{{route('admin.tadas.show',$value->id)}}" class="btn-action" style="background: #e6f2f7; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #057db0;" title="View">
                                            <i data-feather="eye" style="width: 16px;"></i>
                                        </a>
                                    @endcan
                                    
                                    @can('edit_tada')
                                        @if(AppHelper::checkSuperAdmin() || ($value->is_settled == 0))
                                            <a href="{{route('admin.tadas.edit',$value->id)}}" class="btn-action" style="background: #e6f2f7; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #057db0;" title="Edit">
                                                <i data-feather="edit-3" style="width: 16px;"></i>
                                            </a>
                                        @endif
                                    @endcan

                                    @can('delete_tada')
                                        @if(AppHelper::checkSuperAdmin() || ($value->is_settled == 0))
                                            <a data-href="{{route('admin.tadas.delete',$value->id)}}" class="btn-action cursor-pointer deleteTada" style="background: #fff0e6; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #FB8233;" title="Delete">
                                                <i data-feather="trash-2" style="width: 16px;"></i>
                                            </a>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <i data-feather="info" style="width: 50px; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3">{{ __('index.no_records_found') }}</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{$tadaLists->appends($_GET)->links()}}
    </div>
</section>

@include('admin.tada.update_status_form')
@endsection

@section('scripts')
    @include('admin.tada.common.scripts')
    <script>
        $(document).ready(function() {
            feather.replace();
        });
        $(document).on('click', '.deleteTada', function (e) {
    e.preventDefault();
    let url = $(this).data('href');
    
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#057db0', // Theme Blue
        cancelButtonColor: '#FB8233',  // Theme Orange
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    })
});
    </script>
@endsection