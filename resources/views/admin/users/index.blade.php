{{--@extends('layouts.master')

@section('title', __('index.users'))

@section('action', __('index.lists'))

@section('button')

    <a href="{{ route('admin.users.create')}}">
        <button class="btn btn-primary d-flex align-items-center gap-2">
            <i class="link-icon" data-feather="plus"></i>{{ __('index.add_user') }}
        </button>
    </a>


@endsection

@section('main-content')

    <section class="content">
        @include('admin.section.flash_message')

        @include('admin.users.common.breadcrumb')


        <div class="card">
        <div class="card-header">
                <h6 class="card-title mb-0">{{ __('index.user_list') }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>{{ __('index.full_name') }}</th>
                            <th class="text-center">{{ __('index.email') }}</th>
                            <th class="text-center">{{ __('index.is_active') }}</th>
                            <th class="text-center">{{ __('index.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <?php
                            $changeColor = [
                                0 => 'success',
                                1 => 'primary',
                            ]
                            ?>
                        @forelse($admins as $key => $value)
                            <tr>
                                <td class="text-center">
                                    <a href="{{ route('admin.users.show', $value->id) }}" id="showOfficeTimeDetail" >
                                        <i class="link-icon" data-feather="eye" ></i>
                                    </a>
                                </td>
                                <td>{{ ucfirst($value->name) }} </td>
                                <td class="text-center">{{ $value->email }}</td>

                                    <td class="text-center">
                                        <label class="switch">
                                            <input class="toggleStatus"
                                                   href="{{ route('admin.users.toggle-status', $value->id) }}"
                                                   type="checkbox" {{ $value->is_active == 1 ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>


                                        <td class="text-center">
                                            <a class="nav-link dropdown-toggle p-0" href="#" id="profileDropdown"
                                               role="button"
                                               data-bs-toggle="dropdown"
                                               aria-haspopup="true"
                                               aria-expanded="false"
                                               title="{{ __('index.action') }}"
                                            >
                                            </a>

                                            <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                                                <ul class="list-unstyled p-1 mb-0">

                                                    @if($value->id == auth('admin')->user()->id)
                                                        <li class="dropdown-item py-2">
                                                            <a href="{{ route('admin.users.edit', $value->id) }}">
                                                                <button class="btn btn-primary btn-xs">{{ __('index.edit_detail') }}</button>
                                                            </a>
                                                        </li>

                                                    @endif


                                                        @if($value->id != auth('admin')->user()->id || $value->id != 1)
                                                            <li class="dropdown-item py-2">
                                                                <a class="deleteEmployee"
                                                                   data-href="{{ route('admin.users.delete', $value->id) }}">
                                                                    <button class="btn btn-primary btn-xs">{{ __('index.delete_user') }}</button>
                                                                </a>
                                                            </li>
                                                        @endif


                                                    @if($value->id == auth('admin')->user()->id)
                                                        <li class="dropdown-item py-2">
                                                            <a class="changePassword"
                                                               data-href="{{ route('admin.users.change-password', $value->id) }}">
                                                                <button class="btn btn-primary btn-xs">{{ __('index.change_password') }}</button>
                                                            </a>
                                                        </li>
                                                    @endif


                                                </ul>
                                            </div>
                                        </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%">
                                    <p class="text-center"><b>{{ __('index.no_records_found') }}</b></p>
                                </td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="dataTables_paginate mt-3">
            {{ $admins->appends($_GET)->links() }}
        </div>

    </section>
    @include('admin.users.common.password')
@endsection

@section('scripts')
    @include('admin.users.common.scripts')
@endsection
--}}


@extends('layouts.master')

@section('title', __('index.users'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    {{-- Header Section: Exact Copy of Master Style --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 600;">
                {{ __('index.users') }}
            </h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px; margin-top: 5px;">
                <i data-feather="users" style="width: 14px; vertical-align: middle;"></i> 
                User & Role Management
            </p>
        </div>
        
        {{-- Create Button: Exact Copy of Master Style --}}
        @can('create_user')
            <a href="{{ route('admin.users.create') }}" style="text-decoration: none;">
                <button class="btn btn-primary d-flex align-items-center gap-2" >
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.add_user') }}</span>
                </button>
            </a>
        @endcan
    </div>

    @include('admin.users.common.breadcrumb')

    <div class="user-list mt-4">
        @forelse($admins as $key => $value)
            <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden transition-all hover-card" style="background: #fff;">
                <div class="card-body p-0">
                    <div class="row g-0 align-items-center">
                        
                        <div class="col-auto p-4 text-center border-end bg-light d-flex align-items-center justify-content-center" style="min-width: 100px;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                 style="width: 55px; height: 55px; background: #057DB0; color: white; font-size: 1.2rem; font-weight: bold;">
                                {{ strtoupper(substr($value->name, 0, 1)) }}
                            </div>
                        </div>

                        <div class="col p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">{{ ucfirst($value->name) }}</h5>
                                    <p class="text-muted small mb-0">
                                        <i data-feather="mail" style="width: 14px;"></i> {{ $value->email }}
                                    </p>
                                </div>
                                
                                <div class="text-end">
                                    <span class="d-block small fw-bold text-muted mb-1 text-uppercase" style="font-size: 10px;">@lang('index.status')</span>
                                    <div class="form-check form-switch d-inline-block">
                                        {{-- Updated Toggle Color to #FB8233 --}}
                                        <input class="form-check-input toggleStatus cursor-pointer custom-orange-toggle" type="checkbox" 
                                               href="{{ route('admin.users.toggle-status', $value->id) }}"
                                               {{ $value->is_active == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Icon Dock: Replicated Master Style --}}
                        <div class="col-auto p-4 bg-light-subtle border-start">
                            <div class="d-flex align-items-center gap-2">
                                
                                <a href="{{ route('admin.users.show', $value->id) }}" 
                                   style="background: #e0f2fe; color: #0369a1; padding: 8px; border-radius: 8px; display: inline-flex;" 
                                   title="{{ __('index.view') }}">
                                    <i data-feather="eye" style="width:16px; height:16px;"></i>
                                </a>

                                @if($value->id == auth('admin')->user()->id)
                                    <a href="{{ route('admin.users.edit', $value->id) }}" 
                                       style="background: #f1f5f9; color: #475569; padding: 8px; border-radius: 8px; display: inline-flex;" 
                                       title="{{ __('index.edit_detail') }}">
                                        <i data-feather="edit-3" style="width:16px; height:16px;"></i>
                                    </a>
                                @endif

                                @if($value->id != auth('admin')->user()->id && $value->id != 1)
                                    <a href="javascript:void(0)" 
                                       class="deleteEmployee"
                                       data-href="{{ route('admin.users.delete', $value->id) }}" 
                                       style="background: #fee2e2; color: #dc2626; padding: 8px; border-radius: 8px; display: inline-flex; cursor: pointer;" 
                                       title="{{ __('index.delete_user') }}">
                                        <i data-feather="trash-2" style="width:16px; height:16px;"></i>
                                    </a>
                                @endif

                                @if($value->id == auth('admin')->user()->id)
                                    <button class="btn btn-sm text-white fw-bold rounded-3 changePassword" 
                                            data-href="{{ route('admin.users.change-password', $value->id) }}"
                                            style="background-color: #057DB0; font-size: 11px; padding: 8px 12px;">
                                        <i data-feather="lock" style="width: 12px; margin-right: 4px;"></i> {{ __('index.pass') }}
                                    </button>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i data-feather="users" style="width: 50px; color: #cbd5e1;"></i>
                <p class="text-muted mt-3">{{ __('index.no_records_found') }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $admins->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</section>

@include('admin.users.common.password')

<style>
     .swal2-deny {
    border-color: transparent !important;
   }
    .hover-card:hover { 
        transform: translateX(5px); 
        box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important; 
        border-left: 5px solid #057DB0 !important;
    }

    /* Target Toggle Styling */
    .custom-orange-toggle:checked {
        background-color: #FB8233 !important;
        border-color: #FB8233 !important;
    }
    
    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(251, 130, 51, 0.25);
        border-color: #FB8233;
    }
</style>
@endsection

@section('scripts')
    @include('admin.users.common.scripts')
    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection