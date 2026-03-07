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

@section('button')
    <a href="{{ route('admin.users.create')}}" class="btn shadow-sm px-4 fw-bold rounded-3 text-white" style="background-color: #057DB0;">
        <i class="me-1" data-feather="plus-circle"></i> {{ __('index.add_user') }}
    </a>
@endsection

@section('main-content')
<section class="content-wrapper p-4" style="background: #f0f2f5; min-height: 100vh;">
    @include('admin.section.flash_message')
    @include('admin.users.common.breadcrumb')

    <div class="user-list">
        @forelse($admins as $key => $value)
            <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden transition-all hover-card">
                <div class="card-body p-0">
                    <div class="row g-0 align-items-center">
                        
                        <div class="col-auto p-4 text-center border-end bg-light d-flex align-items-center justify-content-center" style="min-width: 100px;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                 style="width: 55px; height: 55px; background: #057DB0; color: white; font-size: 1.2rem; font-weight: bold;">
                                {{ strtoupper(substr($value->name, 0, 1)) }}
                            </div>
                        </div>

                        <div class="col p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">{{ ucfirst($value->name) }}</h5>
                                    <p class="text-muted small mb-0">
                                        <i data-feather="mail" class="icon-xs me-1"></i> {{ $value->email }}
                                    </p>
                                </div>
                                
                                <div class="text-end">
                                    <span class="d-block small fw-bold text-muted mb-1 text-uppercase">@lang('index.status')</span>
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input toggleStatus cursor-pointer" type="checkbox" 
                                               href="{{ route('admin.users.toggle-status', $value->id) }}"
                                               {{ $value->is_active == 1 ? 'checked' : '' }}
                                               style="border-color: #057DB0;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-auto p-4 bg-light-subtle border-start">
                            <div class="d-flex flex-column gap-2">
                                <div class="btn-group shadow-sm bg-white rounded-3">
                                    <a href="{{ route('admin.users.show', $value->id) }}" class="btn btn-link text-primary p-2" title="{{ __('index.view') }}">
                                        <i data-feather="eye" style="width:18px;height:18px;"></i>
                                    </a>

                                    @if($value->id == auth('admin')->user()->id)
                                        <a href="{{ route('admin.users.edit', $value->id) }}" class="btn btn-link text-info p-2" title="{{ __('index.edit_detail') }}">
                                            <i data-feather="edit"></i>
                                        </a>
                                    @endif

                                    @if($value->id != auth('admin')->user()->id && $value->id != 1)
                                        <button class="btn btn-link text-danger p-2 deleteEmployee" 
                                                data-href="{{ route('admin.users.delete', $value->id) }}" title="{{ __('index.delete_user') }}">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    @endif
                                </div>
                                
                                @if($value->id == auth('admin')->user()->id)
                                    <button class="btn btn-sm text-white fw-bold rounded-3 changePassword" 
                                            data-href="{{ route('admin.users.change-password', $value->id) }}"
                                            style="background-color: #057DB0;">
                                        <i data-feather="lock" class="icon-xs me-1"></i> {{ __('index.change_password') }}
                                    </button>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4 text-center py-5 bg-white">
                <i data-feather="users" class="mx-auto mb-3" style="width: 50px; height: 50px; color: #057DB0; opacity: 0.3;"></i>
                <h5 class="text-muted fw-bold">{{ __('index.no_records_found') }}</h5>
            </div>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $admins->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</section>

@include('admin.users.common.password')

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .hover-card { transition: all 0.3s ease; border-left: 5px solid transparent !important; }
    .hover-card:hover { 
        transform: translateX(5px); 
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; 
        border-left-color: #057DB0 !important;
    }
    
    .form-switch .form-check-input:checked {
        background-color: #057DB0;
        border-color: #057DB0;
    }

    .btn-link { text-decoration: none; border: none; transition: 0.2s; }
    .btn-link:hover { opacity: 0.7; transform: scale(1.1); }
    
    .icon-xs { width: 14px; height: 14px; stroke-width: 2.5px; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
</style>
@endsection

@section('scripts')
    @include('admin.users.common.scripts')
    <script>
        $(document).ready(function() {
            feather.replace();
        });
    </script>
@endsection
