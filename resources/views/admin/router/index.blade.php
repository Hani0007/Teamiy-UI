{{--@extends('layouts.master')

@section('title',__('index.router'))

@section('action',__('index.lists'))

@section('button')
    @can('create_router')
        <a href="{{ route('admin.routers.create')}}">
            <button class="btn btn-primary">
                <i class="link-icon" data-feather="plus"></i> @lang('index.add_router')
            </button>
        </a>
    @endcan
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.router') }}</h2>
            @include('admin.router.common.breadcrumb')
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">@lang('index.router_filter')</h6>
            </div>
            <form class="forms-sample card-body pb-0" action="{{ route('admin.routers.index') }}" method="get">

                <div class="row align-items-center">
                    @if(!isset(auth()->user()->branch_id))
                        <div class="col-lg-3 col-md-6 mb-4">
                            <select class="form-select" id="branch_id" name="branch_id">
                                <option  {{ !isset($filterParameters['branch_id']) || old('branch_id') ? 'selected': ''}}  disabled>{{ __('index.select_branch') }}
                                </option>
                                @if(isset($companyDetail))
                                    @foreach($companyDetail->branches()->get() as $key => $branch)
                                        <option value="{{$branch->id}}"
                                            {{ (isset($filterParameters['branch_id']) && $filterParameters['branch_id'] == $branch->id) ? 'selected': '' }}>
                                            {{ucfirst($branch->name)}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @endif

                    <div class="col-lg-2 col-md-6 mb-4">
                        <div class="d-flex">
                            <button type="submit" class="btn btn-block btn-success me-2" >@lang('index.filter')</button>
                            <a class="btn btn-block btn-primary" href="{{ route('admin.routers.index') }}">@lang('index.reset')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">{{ __('index.router_lists') }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('index.router_bssid')</th>
                            <th>@lang('index.branch') </th>
                            <th>@lang('index.company')</th>
                            <th class="text-center">@lang('index.status')</th>
                            @canany(['edit_router','delete_router'])
                                <th class="text-center">@lang('index.action')</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                        @forelse($routers as $key => $value)
                            <tr>
                                <td>{{(($routers->currentPage()- 1 ) * (\App\Models\Router::RECORDS_PER_PAGE) + (++$key))}}</td>
                                <td>{{($value->router_ssid)}}</td>
                                <td>{{ucfirst($value->branch->name)}}</td>
                                <td>{{ucfirst($value->company->name)}}</td>
                                <td class="text-center">
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.routers.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                @canany(['edit_router','delete_router'])
                                    <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center align-items-center">
                                        @can('edit_router')
                                            <li class="me-2">
                                                <a href="{{route('admin.routers.edit',$value->id)}}" title="{{ __('index.edit') }}">
                                                    <i class="link-icon" data-feather="edit" ></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete_router')
                                            <li>
                                                <a class="deleteRouter"
                                                   data-href="{{route('admin.routers.delete',$value->id)}}" title="{{ __('index.delete') }}">
                                                    <i class="link-icon"  data-feather="delete"></i>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </td>
                                @endcanany
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%">
                                    <p class="text-center"><b>@lang('index.no_records_found')</b></p>
                                </td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="dataTables_paginate">
            {{$routers->appends($_GET)->links()}}
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.toggleStatus').change(function (event) {
                event.preventDefault();
                var status = $(this).prop('checked') === true ? 1 : 0;
                var href = $(this).attr('href');
                Swal.fire({
                    title: 'Are you sure you want to change status ?',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    padding:'10px 50px 10px 50px',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }else if (result.isDenied) {
                        (status === 0)? $(this).prop('checked', true) :  $(this).prop('checked', false)
                    }
                })
            })

            $('.deleteRouter').click(function (event) {
                event.preventDefault();
                let href = $(this).data('href');
                Swal.fire({
                    title: 'Are you sure you want to Delete Router Detail ?',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    padding:'10px 50px 10px 50px',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                })
            })

        });
    </script>
@endsection

--}}
@extends('layouts.master')

@section('title', __('index.router'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 600;">
                {{ __('index.router') }}
            </h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px; margin-top: 5px;">
                <i data-feather="wifi" style="width: 14px; vertical-align: middle;"></i> 
                Network Connectivity Management
            </p>
        </div>
        
        @can('create_router')
            <a href="{{ route('admin.routers.create') }}" style="text-decoration: none;">
                <button class="btn btn-primary d-flex align-items-center gap-2" style=" border-radius: 14px;">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>@lang('index.add_router')</span>
                </button>
            </a>
        @endcan
    </div>

    @include('admin.router.common.breadcrumb')

    {{-- Filter Card with 14px Border Radius Buttons --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #fff;">
        <div class="card-body p-3">
            <form action="{{ route('admin.routers.index') }}" method="get" class="row g-2 align-items-center">
                @if(!isset(auth()->user()->branch_id))
                    <div class="col-lg-4 col-md-6">
                        <select class="form-select border-0 bg-light rounded-3" id="branch_id" name="branch_id" style="height: 45px;">
                            <option {{ !isset($filterParameters['branch_id']) || old('branch_id') ? 'selected': ''}} disabled>
                                {{ __('index.select_branch') }}
                            </option>
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
                
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn text-white px-4 shadow-sm d-flex align-items-center gap-2" 
                            style="background-color: #057DB0; height: 45px; border: none; border-radius: 14px;">
                        <i data-feather="filter" style="width: 16px;"></i> @lang('index.filter')
                    </button>
                    
                    <a class="btn bg-white px-4 shadow-sm border d-flex align-items-center gap-2" 
                       href="{{ route('admin.routers.index') }}" 
                       style="color: #057DB0; border-color: #057DB0 !important; height: 45px; border-radius: 14px;">
                        <i data-feather="refresh-cw" style="width: 16px;"></i> @lang('index.reset')
                    </a>
                </div>
            </form>
        </div>
    </div>    

    <div class="router-list">
        @forelse($routers as $key => $value)
            <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden transition-all hover-card" style="background: #fff;">
                <div class="card-body p-0">
                    <div class="row g-0 align-items-center">
                        
                        <div class="col-auto p-4 text-center border-end bg-light d-flex align-items-center justify-content-center" style="min-width: 100px;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm bg-white" 
                                 style="width: 55px; height: 55px; color: #057DB0;">
                                <i data-feather="wifi" style="width: 28px; height: 28px;"></i>
                            </div>
                        </div>

                        <div class="col p-4">
                            <div class="row align-items-center">
                                <div class="col-md-5">
                                    <h5 class="fw-bold text-dark mb-1">{{ $value->router_ssid }}</h5>
                                    <p class="text-muted small mb-0">
                                        <i data-feather="map-pin" style="width: 14px;"></i> {{ ucfirst($value->branch->name) }} 
                                        <span class="mx-2" style="color: #cbd5e0;">|</span>
                                        <i data-feather="briefcase" style="width: 14px;"></i> {{ ucfirst($value->company->name) }}
                                    </p>
                                </div>
                                
                                <div class="col-md-3 text-center">
                                    <span class="d-block small fw-bold text-muted mb-1 text-uppercase" style="font-size: 10px;">@lang('index.status')</span>
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input toggleStatus cursor-pointer custom-orange-toggle" type="checkbox" 
                                               href="{{route('admin.routers.toggle-status',$value->id)}}"
                                               {{($value->is_active) == 1 ? 'checked' : ''}}>
                                    </div>
                                </div>

                                {{-- Action Buttons: Separate Colored Squares --}}
                                <div class="col-md-4 text-end">
                                    <div class="d-flex justify-content-end gap-2 px-3">
                                        @can('edit_router')
                                            <a href="{{route('admin.routers.edit',$value->id)}}" 
                                               style="background: #f1f5f9; color: #057DB0; padding: 10px; border-radius: 10px; display: inline-flex;" 
                                               title="{{ __('index.edit') }}">
                                                <i data-feather="edit-3" style="width:18px; height:18px;"></i>
                                            </a>
                                        @endcan

                                        @can('delete_router')
                                            <a href="javascript:void(0)" 
                                               class="deleteRouter"
                                               data-href="{{route('admin.routers.delete',$value->id)}}" 
                                               style="background: #fee2e2; color: #dc2626; padding: 10px; border-radius: 10px; display: inline-flex; cursor: pointer;" 
                                               title="{{ __('index.delete') }}">
                                                <i data-feather="trash-2" style="width:18px; height:18px;"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i data-feather="rss" style="width: 50px; color: #cbd5e1;"></i>
                <p class="text-muted mt-3">@lang('index.no_records_found')</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $routers->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</section>

<style>
   .swal2-deny {
       border-color: transparent !important;
    }
    /* Card Hover Style */
    .hover-card:hover { 
        transform: translateY(-3px); 
        box-shadow: 0 12px 30px rgba(0,0,0,0.05) !important; 
        border-left: 5px solid #057DB0 !important;
    }
    
    /* Toggle Color #FB8233 */
    .custom-orange-toggle:checked {
        background-color: #FB8233 !important;
        border-color: #FB8233 !important;
    }

    .form-switch .form-check-input { width: 2.5em; height: 1.25em; }

    /* Custom Transitions */
    .hover-card { transition: all 0.3s ease; border-left: 5px solid transparent; }
</style>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Toggle Status
        $('.toggleStatus').change(function (event) {
            event.preventDefault();
            var status = $(this).prop('checked') === true ? 1 : 0;
            var href = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure you want to change status?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10px 50px 10px 50px',
                allowOutsideClick: false,
                confirmButtonColor: '#057db0', 
                denyButtonColor: '#FB8233',
                icon:'warning'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                } else if (result.isDenied) {
                    $(this).prop('checked', !status);
                }
            });
        });

        // Delete Router
        $('.deleteRouter').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: 'Are you sure you want to delete this router?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10px 50px 10px 50px',
                confirmButtonColor: '#057db0', 
                denyButtonColor: '#FB8233',
                icon:'warning',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
</script>
@endsection