{{--@extends('layouts.master')
@section('title',__('index.asset_types'))
@section('action',__('index.lists'))

@section('button')
    @can('create_type')
        <button class="btn btn-primary create-assetType mb-3">
            <i class="link-icon" data-feather="plus"></i> {{ __('index.add_asset_types') }}
        </button>
    @endcan
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.dataTables.min.css') }}">
@endsection
@section('main-content')

    <section class="content">
        @include('admin.section.flash_message')
        @include('admin.assetManagement.types.common.breadcrumb')
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">@lang('index.asset_type_filter')</h6>
            </div>
            <form class="forms-sample card-body pb-0" action="{{ route('admin.asset-types.index') }}" method="get">
                <div class="row align-items-center">
                    @if(!isset(auth()->user()->branch_id))
                        <div class="col-lg-3 col-md-6 mb-4">
                            <select class="form-select" id="branch" name="branch_id">
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

                    <div class="col-lg-3 col-md-6 mb-4">
                        <input type="text" class="form-control" placeholder="@lang('index.type')" name="type" id="title" value="{{ $filterParameters['type'] }}">
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="d-flex">
                            <button type="submit" class="btn btn-block btn-success me-2">@lang('index.filter')</button>
                            <a class="btn btn-block btn-primary" href="{{ route('admin.asset-types.index') }}">@lang('index.reset')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">{{ __('index.asset_type_list') }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('index.name') }}</th>
                            <th class="text-center">{{ __('index.asset_item_count') }}</th>
                            <th class="text-center">{{ __('index.status') }}</th>
                            @canany(['edit_type','delete_type'])
                                <th class="text-center">{{ __('index.action') }}</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($assetTypeLists as $key => $value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ucfirst($value->name)}}</td>
                                <td class="text-center">
                                    <a href="{{route('admin.asset-types.show',$value->id)}}"> {{$value->assets_count}}</a>
                                </td>
                                <td class="text-center">
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.asset-types.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                        @can('edit_type')
                                            <li class="me-2">

                                                <a class="edit-assetType"  data-id="{{ $value->id }}" data-href="{{ route('admin.asset-types.edit', $value->id) }}">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete_type')
                                            <li>
                                                <a class="delete"
                                                   data-href="{{route('admin.asset-types.delete',$value->id)}}" title="{{ __('index.delete') }}">
                                                    <i class="link-icon"  data-feather="delete"></i>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </td>

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
    </section>
    <!-- asset type create/edit modal -->
    <div class="modal fade" id="assetTypeModal" tabindex="-1" aria-labelledby="assetTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title" id="assetTypeModalLabel">{{ __('index.add_asset_types') }}</h5>
                </div>
                <div class="modal-body pb-0">
                    <form id="assetTypeForm" class="forms-sample" enctype="multipart/form-data" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">


                        <div class="row align-items-center">
                            @if(!isset(auth()->user()->branch_id))
                                <div class="col-lg-6 mb-4">
                                    <label for="branch_id" class="form-label">{{ __('index.branch') }} <span style="color: red">*</span></label>
                                    <select class="form-select" id="branch_id" name="branch_id">
                                        <option selected disabled>{{ __('index.select_branch') }}</option>
                                        @if(isset($companyDetail))
                                            @foreach($companyDetail->branches()->get() as $key => $branch)
                                                <option value="{{$branch->id}}">{{ucfirst($branch->name)}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            @endif

                            <div class="col-lg-6 mb-3">
                                <label for="name" class="form-label">{{ __('index.name') }}<span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="name"
                                       required
                                       name="name"
                                       value="{{ old('name') }}"
                                       autocomplete="off"
                                       placeholder="Enter Assets Name"
                                >
                            </div>
                            <div class="col-lg-6 mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <!-- <i class="link-icon" data-feather="plus"></i> <span id="submitButtonText">{{ __('index.save') }}</span> -->
                                     <span id="submitButtonText">{{ __('index.save') }}</span>
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('index.cancel') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script src="{{ asset('assets/js/dataTables.min.js') }}"></script>
    <script>
        @if($assetTypeLists->isNotEmpty())
            let table = new DataTable('#dataTableExample', {
                pageLength: @json(getRecordPerPage()),
                searching: false,
                paging: true,
            });
        @endif

    </script>
    @include('admin.assetManagement.types.common.scripts')
@endsection

--}}




{{--@extends('layouts.master')
@section('title',__('index.asset_types'))
@section('action',__('index.lists'))

@section('button')
    @can('create_type')
        <button class="btn btn-primary create-assetType mb-3">
            <i class="link-icon" data-feather="plus"></i> {{ __('index.add_asset_types') }}
        </button>
    @endcan
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.dataTables.min.css') }}">
@endsection
@section('main-content')

    <section class="content">
        @include('admin.section.flash_message')
        @include('admin.assetManagement.types.common.breadcrumb')
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">@lang('index.asset_type_filter')</h6>
            </div>
            <form class="forms-sample card-body pb-0" action="{{ route('admin.asset-types.index') }}" method="get">
                <div class="row align-items-center">
                    @if(!isset(auth()->user()->branch_id))
                        <div class="col-lg-3 col-md-6 mb-4">
                            <select class="form-select" id="branch" name="branch_id">
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

                    <div class="col-lg-3 col-md-6 mb-4">
                        <input type="text" class="form-control" placeholder="@lang('index.type')" name="type" id="title" value="{{ $filterParameters['type'] }}">
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="d-flex">
                            <button type="submit" class="btn btn-block btn-success me-2">@lang('index.filter')</button>
                            <a class="btn btn-block btn-primary" href="{{ route('admin.asset-types.index') }}">@lang('index.reset')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">{{ __('index.asset_type_list') }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('index.name') }}</th>
                            <th class="text-center">{{ __('index.asset_item_count') }}</th>
                            <th class="text-center">{{ __('index.status') }}</th>
                            @canany(['edit_type','delete_type'])
                                <th class="text-center">{{ __('index.action') }}</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($assetTypeLists as $key => $value)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{ucfirst($value->name)}}</td>
                                <td class="text-center">
                                    <a href="{{route('admin.asset-types.show',$value->id)}}"> {{$value->assets_count}}</a>
                                </td>
                                <td class="text-center">
                                    <label class="switch">
                                        <input class="toggleStatus" href="{{route('admin.asset-types.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td class="text-center">
                                    <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                        @can('edit_type')
                                            <li class="me-2">

                                                <a class="edit-assetType"  data-id="{{ $value->id }}" data-href="{{ route('admin.asset-types.edit', $value->id) }}">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>
                                        @endcan

                                        @can('delete_type')
                                            <li>
                                                <a class="delete"
                                                   data-href="{{route('admin.asset-types.delete',$value->id)}}" title="{{ __('index.delete') }}">
                                                    <i class="link-icon"  data-feather="delete"></i>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </td>

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
    </section>
    <!-- asset type create/edit modal -->
    <div class="modal fade" id="assetTypeModal" tabindex="-1" aria-labelledby="assetTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title" id="assetTypeModalLabel">{{ __('index.add_asset_types') }}</h5>
                </div>
                <div class="modal-body pb-0">
                    <form id="assetTypeForm" class="forms-sample" enctype="multipart/form-data" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">


                        <div class="row align-items-center">
                            @if(!isset(auth()->user()->branch_id))
                                <div class="col-lg-6 mb-4">
                                    <label for="branch_id" class="form-label">{{ __('index.branch') }} <span style="color: red">*</span></label>
                                    <select class="form-select" id="branch_id" name="branch_id">
                                        <option selected disabled>{{ __('index.select_branch') }}</option>
                                        @if(isset($companyDetail))
                                            @foreach($companyDetail->branches()->get() as $key => $branch)
                                                <option value="{{$branch->id}}">{{ucfirst($branch->name)}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            @endif

                            <div class="col-lg-6 mb-3">
                                <label for="name" class="form-label">{{ __('index.name') }}<span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="name"
                                       required
                                       name="name"
                                       value="{{ old('name') }}"
                                       autocomplete="off"
                                       placeholder="Enter Assets Name"
                                >
                            </div>
                            <div class="col-lg-6 mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <!-- <i class="link-icon" data-feather="plus"></i> <span id="submitButtonText">{{ __('index.save') }}</span> -->
                                     <span id="submitButtonText">{{ __('index.save') }}</span>
                                </button>
                                <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">{{ __('index.cancel') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script src="{{ asset('assets/js/dataTables.min.js') }}"></script>
    <script>
        @if($assetTypeLists->isNotEmpty())
            let table = new DataTable('#dataTableExample', {
                pageLength: @json(getRecordPerPage()),
                searching: false,
                paging: true,
            });
        @endif

    </script>
    @include('admin.assetManagement.types.common.scripts')
@endsection


--}}
@extends('layouts.master')

@section('title', __('index.asset_types'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0;">{{ __('index.asset_types') }}</h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px;">
                <i data-feather="box" style="width: 14px; vertical-align: middle;"></i> Asset Categorization & Management
            </p>
        </div>
        
        <div class="d-flex gap-2">
            @can('create_type')
                <button class="btn btn-primary create-assetType d-flex align-items-center gap-2" 
                        style=" color: white; border: none; border-radius: 12px; padding: 10px 20px; font-weight: 600; transition: all 0.3s ease;">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.add_asset_types') }}</span>
                </button>
            @endcan
        </div>
    </div>

    {{-- Cards Grid --}}
    <div class="row g-4 justify-content-start">
        @forelse($assetTypeLists as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    <div class="card-glossy-header">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="package"></i>
                            </div>
                            <label class="switch-modern">
                                <input class="toggleStatus" href="{{route('admin.asset-types.toggle-status',$value->id)}}"
                                       type="checkbox" {{($value->is_active) == 1 ? 'checked' : ''}}>
                                <span class="slider-modern round"></span>
                            </label>
                        </div>
                        
                        <h4 class="branch-name-display text-truncate" title="{{ $value->name }}">{{ ucfirst($value->name) }}</h4>
                        
                        <div class="d-flex align-items-center justify-content-between position-relative mt-2" style="z-index: 2;">
                            <span class="branch-ref-pill">Type ID: #{{ $value->id }}</span>
                            <div class="d-flex gap-1">
                                @can('edit_type')
                                    <a href="javascript:void(0)" class="btn-header-action edit-assetType" 
                                       data-id="{{ $value->id }}" 
                                       data-href="{{ route('admin.asset-types.edit', $value->id) }}"
                                       style="color: ##057DB0 !important;">
                                        <i data-feather="edit-3"></i>
                                    </a>
                                @endcan
                                @can('delete_type')
                                    <a href="javascript:void(0)" data-href="{{route('admin.asset-types.delete',$value->id)}}" 
                                       class="btn-header-action deleteAssetType" 
                                       style="color: #ef4444 !important;">
                                        <i data-feather="trash-2"></i>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-white-body">
                        <div class="info-listing mb-0">
                            <div class="info-item-box border-0 pb-0">
                                <div class="icon-circle"><i data-feather="layers"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.asset_item_count') }}</small>
                                    <p class="mb-0">
                                        <a href="{{route('admin.asset-types.show',$value->id)}}" style="color: #057db0; font-weight: 700; text-decoration: none;">
                                            {{$value->assets_count}} Items
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted"><b>{{ __('index.no_records_found') }}</b></p>
            </div>
        @endforelse
    </div>
</section>

{{-- Create/Edit Modal --}}
<div class="modal fade" id="assetTypeModal" tabindex="-1" aria-labelledby="assetTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.1);">
            <div class="modal-header d-block" style="background: #057db0; color: white; border: none; padding: 25px;">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="modal-identity d-flex align-items-center gap-3">
                        <div class="header-icon-box" style="background: rgba(255,255,255,0.2); padding: 10px; border-radius: 12px;">
                            <i data-feather="box" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold" id="assetTypeModalLabel">{{ __('index.add_asset_types') }}</h5>
                            <p class="mb-0 small text-white-50">Manage your asset categorization below.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <div class="modal-body p-4">
                <form id="assetTypeForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="row">
                        @if(!isset(auth()->user()->branch_id))
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold small text-muted">{{ __('index.branch') }} <span class="text-danger">*</span></label>
                                <select class="form-select" id="branch_id" name="branch_id" style="border-radius: 10px; padding: 12px; background-color: #f8fafc;">
                                    <option selected disabled>{{ __('index.select_branch') }}</option>
                                    @if(isset($companyDetail))
                                        @foreach($companyDetail->branches()->get() as $branch)
                                            <option value="{{$branch->id}}">{{ucfirst($branch->name)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        @endif

                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold small text-muted">{{ __('index.name') }}<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="Enter Assets Name" style="border-radius: 10px; padding: 12px; background-color: #f8fafc;">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-2">
                         <button type="submit" class="btn btn-primary px-4 py-2" style="background: #057db0; border: none; border-radius: 10px; font-weight: 600;">
                            <span id="submitButtonText">{{ __('index.save') }}</span>
                        </button>
                        <button type="button" class="btn px-4 py-2" data-bs-dismiss="modal" 
                                style="background: #ff851b; color: white; border: none; border-radius: 10px; font-weight: 600;">
                            {{ __('index.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .create-assetType:hover { background: #e67616 !important; transform: translateY(-2px); }
    .btn-header-action { 
        background: rgba(255, 255, 255, 0.95); /* High opacity for icon contrast */
        width: 32px; 
        height: 32px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        border-radius: 8px; 
        border: 1px solid rgba(0, 0, 0, 0.05); 
        transition: all 0.2s ease;
    }
    .btn-header-action:hover {
        background: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .slider-modern { background-color: rgba(255,255,255,0.3); }
    input:checked + .slider-modern { background-color: #ff851b; }
</style>
@endsection

@section('scripts')
    @include('admin.assetManagement.types.common.scripts')
    <script>
        $(document).ready(function() {
            feather.replace();

            $('.toggleStatus').change(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                var isChecked = $(this).prop('checked');
                Swal.fire({
                    title: 'Update Status?',
                    text: 'Change the visibility of this asset type.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Update!',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#057db0',
                    cancelButtonColor: '#ff851b',
                }).then((result) => {
                    if (result.isConfirmed) window.location.href = href;
                    else $(this).prop('checked', !isChecked);
                });
            });

            $('.deleteAssetType').click(function (event) {
                event.preventDefault();
                let href = $(this).data('href');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#ff851b' 
                }).then((result) => {
                    if (result.isConfirmed) window.location.href = href;
                });
            });
        });
    </script>
@endsection