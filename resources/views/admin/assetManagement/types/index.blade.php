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
<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
    @include('admin.section.flash_message')

    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-4 mt-3">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin-bottom: 5px;">{{ __('index.asset_types') }}</h2>
            @include('admin.assetManagement.types.common.breadcrumb')
        </div>
        
        @can('create_type')
            <button class="btn btn-primary create-assetType d-flex align-items-center gap-2" 
                    style="background: #057db0; color: white; border: none; border-radius: 12px; padding: 10px 22px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(5, 125, 176, 0.2);">
                <i data-feather="plus" style="width: 20px;"></i>
                <span>{{ __('index.add_asset_types') }}</span>
            </button>
        @endcan
    </div>

    {{-- Modern Filter Section --}}
    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <!--<div class="card-header bg-white border-0 pt-4 px-4">
            <h6 class="card-title mb-0 fw-bold" style="color: #475569;">
                <i data-feather="filter" style="width: 16px; margin-right: 5px;"></i> @lang('index.asset_type_filter')
            </h6>
        </div>-->
        <form class="forms-sample card-body p-4" action="{{ route('admin.asset-types.index') }}" method="get">
            <div class="row align-items-end">
                @if(!isset(auth()->user()->branch_id))
                    <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                        <label class="form-label small fw-bold text-muted text-uppercase">@lang('index.branch')</label>
                        <select class="form-select shadow-none" id="branch" name="branch_id" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0;">
                            <option {{ !isset($filterParameters['branch_id']) || old('branch_id') ? 'selected': ''}} disabled>{{ __('index.select_branch') }}</option>
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

                <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                    <label class="form-label small fw-bold text-muted text-uppercase">@lang('index.type')</label>
                    <input type="text" class="form-control shadow-none" placeholder="Search asset type..." name="type" id="title" 
                           value="{{ $filterParameters['type'] ?? '' }}" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0;">
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-grow-1 fw-bold" style="height: 48px; border-radius: 12px; background: #fb8233; border: none;">
                            @lang('index.filter')
                        </button>
                        <a class="btn btn-primary flex-grow-1 fw-bold d-flex align-items-center justify-content-center" 
                           href="{{ route('admin.asset-types.index') }}" style="height: 48px; border-radius: 12px; background: #057db0; border: none;">
                            @lang('index.reset')
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Asset Cards Grid --}}
    <div class="row g-4 justify-content-start">
        @isset($assetTypeLists)
            @forelse($assetTypeLists as $key => $value)
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                    <div class="branch-master-card" style="background: #fff; border-radius: 20px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); overflow: hidden; transition: 0.3s; border: 1px solid #f1f5f9;">
                        <div class="card-glossy-header" style="padding: 20px;  position: relative;">
                            <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                                <div class="branch-icon-square" style="background: #057db0; color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i data-feather="package" style="width: 20px;"></i>
                                </div>
                                <label class="switch-modern">
                                    <input class="toggleStatus" href="{{route('admin.asset-types.toggle-status',$value->id)}}"
                                           type="checkbox" {{($value->is_active) == 1 ? 'checked' : ''}}>
                                    <span class="slider-modern round"></span>
                                </label>
                            </div>
                            
                            <h4 class=" mt-3 mb-1 branch-name-display" >{{ ucfirst($value->name) }}</h4>
                            <span class="branch-ref-pill" style=" border-radius: 6px; font-size: 11px;">Type ID: #{{ $value->id }}</span>
                        </div>

                        <div class="card-white-body" style="padding: 20px; border-top: 1px solid #f1f5f9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="background: #f1f5f9; padding: 8px; border-radius: 8px;">
                                        <i data-feather="layers" style="width: 16px; color: #64748b;"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 10px; text-transform: uppercase;">{{ __('index.asset_item_count') }}</small>
                                        <a href="{{route('admin.asset-types.show',$value->id)}}" style="color: #057db0; font-weight: 700; text-decoration: none; font-size: 14px;">
                                            {{$value->assets_count ?? 0}} Items
                                        </a>
                                    </div>
                                </div>
                                <div class="d-flex gap-1">
                                    @can('edit_type')
                                        <a href="javascript:void(0)" class="action-btn edit-assetType" data-id="{{ $value->id }}" data-href="{{ route('admin.asset-types.edit', $value->id) }}">
                                            <i data-feather="edit-3" style="width: 16px; color: #057db0;"></i>
                                        </a>
                                    @endcan
                                    @can('delete_type')
                                        <a href="javascript:void(0)" data-href="{{route('admin.asset-types.delete',$value->id)}}" class="action-btn deleteAssetType">
                                            <i data-feather="trash-2" style="width: 16px; color: #fb8233;"></i>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="opacity-25 mb-3"><i data-feather="database" style="width: 48px; height: 48px;"></i></div>
                    <p class="text-muted"><b>{{ __('index.no_records_found') }}</b></p>
                </div>
            @endforelse
        @endisset
    </div>
</section>

{{-- Create/Edit Modal --}}
<div class="modal fade" id="assetTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border-radius: 24px; border: none;">
            <div class="modal-header d-block" style="background: #057db0; color: white; border: none; padding: 25px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div style="background: rgba(255,255,255,0.2); padding: 10px; border-radius: 12px;">
                            <i data-feather="box" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold" id="assetTypeModalLabel">{{ __('index.add_asset_types') }}</h5>
                            <p class="mb-0 small text-white-50">Manage asset categorization</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <form id="assetTypeForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    @if(!isset(auth()->user()->branch_id))
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">@lang('index.branch') <span class="text-danger">*</span></label>
                            <select class="form-select" id="branch_id" name="branch_id" style="border-radius: 12px; padding: 12px; background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                <option selected disabled>{{ __('index.select_branch') }}</option>
                                @if(isset($companyDetail))
                                    @foreach($companyDetail->branches()->get() as $branch)
                                        <option value="{{$branch->id}}">{{ucfirst($branch->name)}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @endif
                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted text-uppercase">@lang('index.name') <span class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-none" id="name" name="name" required placeholder="Enter asset type name" style="height: 50px; border-radius: 12px; background-color: #f8fafc; border: 1px solid #e2e8f0;">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal" style="height: 48px; border-radius: 12px; flex: 1;">{{ __('index.cancel') }}</button>
                    <button type="submit" class="btn btn-primary fw-bold" style="background: #057db0; height: 48px; border-radius: 12px; border: none; flex: 1;">
                        <span id="submitButtonText">{{ __('index.save') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
     .swal2-deny {
    border-color: transparent !important;
   }
    .branch-master-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1) !important; }
    .action-btn { 
        width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; 
        background: #f8fafc; border-radius: 8px; transition: 0.2s; text-decoration: none; border: 1px solid #f1f5f9;
    }
    .action-btn:hover { background: #fff; transform: scale(1.1); box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    
    .switch-modern { position: relative; display: inline-block; width: 38px; height: 20px; }
    .switch-modern input { opacity: 0; width: 0; height: 0; }
    .slider-modern { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
    .slider-modern:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider-modern { background-color: #fb8233; }
    input:checked + .slider-modern:before { transform: translateX(18px); }
</style>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') { feather.replace(); }

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
                confirmButtonColor: '#057db0', 
                denyButtonColor: '#FB8233',
            }).then((result) => {
                if (result.isConfirmed) window.location.href = href;
            });
        });
    });
</script>
@include('admin.assetManagement.types.common.scripts')
@endsection