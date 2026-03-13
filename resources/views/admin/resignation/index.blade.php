@php use App\Enum\ResignationStatusEnum; @endphp
@extends('layouts.master')

@section('title', __('index.resignation'))

@section('main-content')
<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
    @include('admin.section.flash_message')

    {{-- 1. Modern Breadcrumbs & Top Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.resignation') }}</h2>
            @include('admin.resignation.common.breadcrumb')
        </div>
        
        @can('create_resignation')
            <a href="{{ route('admin.resignation.create') }}" style="text-decoration: none;">
                <button class="btn btn-primary">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.add_resignation') }}</span>
                </button>
            </a>
        @endcan
    </div>

    {{-- Cards Grid --}}
    <div class="row g-4 justify-content-start">
        @php
            $statusTheme = [
                ResignationStatusEnum::approved->value  => ['bg' => '#057db0', 'text' => '#fff'],
                ResignationStatusEnum::onReview->value  => ['bg' => '#057DB0', 'text' => '#fff'],
                ResignationStatusEnum::pending->value   => ['bg' => '#FB8233', 'text' => '#fff'],
                ResignationStatusEnum::cancelled->value => ['bg' => '#ef4444', 'text' => '#fff'],
            ];
        @endphp

        @forelse($resignationLists as $key => $value)
            @php
                $theme = $statusTheme[$value->status] ?? ['bg' => '#64748b', 'text' => '#fff'];
                $isExpired = (($value->status == ResignationStatusEnum::approved->value) && strtotime(date('Y-m-d')) > strtotime($value->last_working_day));
            @endphp
            
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    {{-- Card Header --}}
                    <div class="card-glossy-header" style="background: linear-gradient(135deg, #057db0 0%, #046691 100%);">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="user"></i>
                            </div>
                            
                            {{-- Status Badge --}}
                            @if($isExpired)
                                <span class="badge shadow-sm" style="background-color: {{ $theme['bg'] }}; color: {{ $theme['text'] }}; border: none;">
                                    {{ ucfirst($value->status) }}
                                </span>
                            @else
                                <a href="javascript:void(0)" class="resignationStatusUpdate text-decoration-none"
                                   data-href="{{ route('admin.resignation.update-status',$value->id) }}"
                                   data-status="{{$value->status}}" data-reason="{{$value->admin_remark}}" data-id="{{$value->id}}">
                                   <span class="badge shadow-sm" style="background-color: {{ $theme['bg'] }}; color: {{ $theme['text'] }}; border: none;">
                                       {{ ucfirst($value->status) }}
                                   </span>
                                </a>
                            @endif
                        </div>
                        <h4 class="branch-name-display">{{ $value->employee?->name }}</h4>
                        
                        <div class="d-flex justify-content-between align-items-center mt-2 position-relative" style="z-index:2;">
                            <span class="branch-ref-pill">Resignation ID: #{{$value->id}}</span>
                            @can('show_resignation')
                                <a href="{{route('admin.resignation.show',$value->id)}}" 
                                   style="background: #FFFF; color: #057DB0; padding: 4px 8px; border-radius: 20%; backdrop-filter: blur(5px);" 
                                   title="View Detail">
                                    <i data-feather="eye" style="width: 16px; height: 16px;"></i>
                                </a>
                            @endcan
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="calendar"></i></div>
                                <div class="text-content">
                                    <small>RESIGNATION DATE</small>
                                    <p>{{ \App\Helpers\AppHelper::formatDateForView($value->resignation_date) }}</p>
                                </div>
                            </div>
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="alert-circle"></i></div>
                                <div class="text-content">
                                    <small>LAST WORKING DAY</small>
                                    <p>{{ \App\Helpers\AppHelper::formatDateForView($value->last_working_day) }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons at bottom --}}
                        @if(strtotime(date('Y-m-d')) <= strtotime($value->last_working_day))
                            <div class="pt-3 border-top d-flex justify-content-end gap-2">
                                @can('update_resignation')
                                    <a href="{{route('admin.resignation.edit',$value->id)}}" class="btn-action edit" title="Edit">
                                        <i data-feather="edit-3"></i>
                                    </a>
                                @endcan
                                
                                @can('delete_resignation')
                                    <a data-href="{{route('admin.resignation.delete',$value->id)}}" class="btn-action delete deleteBranch cursor-pointer" title="Delete">
                                        <i data-feather="trash-2"></i>
                                    </a>
                                @endcan
                            </div>
                        @endif
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

    <div class="mt-5 d-flex justify-content-center">
        {{ $resignationLists->appends($_GET)->links() }}
    </div>
</section>

@include('admin.resignation.common.status_update')
@endsection

@section('scripts')
    @include('admin.resignation.common.scripts')
    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection