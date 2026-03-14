@php use App\Models\Asset; @endphp
@php use App\Helpers\AppHelper; @endphp
@extends('layouts.master')

@section('title', __('index.assets'))

@section('main-content')
<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
    @include('admin.section.flash_message')

    {{-- 1. Modern Breadcrumbs & Top Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="page-identity">
            <h2 style="color: #057db0;">{{ __('index.assets') }}</h2>
            @include('admin.assetManagement.assetDetail.common.breadcrumb')
        </div>

        @can('create_assets')
            <a href="{{ route('admin.assets.create')}}" style="text-decoration: none;">
                <button class="btn btn-primary" style="background-color: #057db0; border: none; border-radius: 8px; padding: 10px 20px;">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.add_asset') }}</span>
                </button>
            </a>
        @endcan
    </div>

    {{-- 2. Glass-morphism Filter Panel --}}
    <div class="glass-filter-panel mb-5 shadow-sm border-0" style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-radius: 20px; padding: 25px; border: 1px solid #ffffff;">
        <form action="{{route('admin.assets.index')}}" method="get" class="row g-3 align-items-end">
            
            @if(!isset(auth()->user()->branch_id))
                <div class="col-xxl-3 col-xl-4 col-md-6">
                    <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">{{ __('index.branch') }}</label>
                    <select class="form-select shadow-none modern-select" name="branch_id" id="branch_id" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                        <option selected disabled>{{ __('index.select_branch') }}</option>
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

            <div class="col-xxl-3 col-xl-4 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">Asset Name</label>
                <div style="position: relative;">
                    <i data-feather="box" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                    <input type="text" name="name" value="{{$filterParameters['name']}}" class="form-control shadow-none" placeholder="Search asset..." style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; padding-left: 45px; font-size: 14px;">
                </div>
            </div>

            <div class="col-xxl-3 col-xl-4 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">{{ __('index.type') }}</label>
                <select class="form-select shadow-none modern-select" name="type_id" id="type" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                    <option value="" {{!isset($filterParameters['type']) ? 'selected': ''}} >{{ __('index.all') }} </option>
                    {{-- Assuming $assetTypes is available from controller --}}
                    @if(isset($assetTypes))
                        @foreach($assetTypes as $type)
                            <option value="{{ $type->id }}" {{ (isset($filterParameters['type_id']) && $filterParameters['type_id'] == $type->id) ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-xxl-3 col-xl-4 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">Working Status</label>
                <select class="form-select shadow-none modern-select" name="is_working" id="is_working" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                    <option value="" {{!isset($filterParameters['is_working']) ? 'selected': ''}} >{{ __('index.all') }}</option>
                    @foreach(Asset::IS_WORKING as $value)
                        <option value="{{$value}}" {{ isset($filterParameters['is_working']) && $filterParameters['is_working'] == $value ? 'selected': '' }}>
                            {{ucfirst($value)}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-xxl-3 col-xl-4 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">Availability</label>
                <select class="form-select shadow-none modern-select" name="is_available" id="is_available" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                    <option value="" {{!isset($filterParameters['is_available']) ? 'selected': ''}} >{{ __('index.all') }}</option>
                    <option value="1" {{isset($filterParameters['is_available']) && $filterParameters['is_available'] == 1 ? 'selected': ''}} >{{ __('index.yes_available') }}</option>
                    <option value="0" {{isset($filterParameters['is_available']) && $filterParameters['is_available'] == 0 ? 'selected': ''}} >{{ __('index.notavailable') }}</option>
                </select>
            </div>

            <div class="col-xxl-3 col-xl-4 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">Purchased From</label>
                <input type="date" value="{{$filterParameters['purchased_from']}}" name="purchased_from" class="form-control shadow-none" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
            </div>

            <div class="col-xxl-3 col-xl-4 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">Purchased To</label>
                <input type="date" value="{{$filterParameters['purchased_to']}}" name="purchased_to" class="form-control shadow-none" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
            </div>

            <div class="col-xxl-3 col-xl-4 col-md-12">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn w-100" style="background: #057db0; color: white; height: 48px; border-radius: 12px; font-weight: 600; transition: all 0.3s ease;">
                        {{ __('index.filter') }}
                    </button>
                    <a href="{{route('admin.assets.index')}}" class="btn w-100 text-decoration-none d-flex align-items-center justify-content-center" 
                       style="height: 48px; border: 1px solid #e2e8f0; border-radius: 12px; color: #64748b; background: #fff; font-weight: 600;">
                        {{ __('index.reset') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- 3. Cards Grid --}}
    <div class="row g-4 justify-content-start">
        @forelse($assetLists as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card shadow-sm border-0" style="border-radius: 20px; overflow: hidden; background: white; transition: transform 0.3s ease;">
                    <div class="card-glossy-header" style="position: relative; padding: 20px; background: linear-gradient(135deg, #057db0 0%, #046690 100%); color: white;">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square" style="background: rgba(255,255,255,0.2); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
                                <i data-feather="package" style="width: 20px;"></i>
                            </div>
                        </div>
                        <h4 class="branch-name-display mt-3 mb-1" style="font-weight: 700; letter-spacing: 0.5px;">{{ucfirst($value->name)}}</h4>
                        <span class="branch-ref-pill" style="background: rgba(255,255,255,0.15); padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: 600;">Asset ID: #{{$value->id}}</span>
                    </div>

                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="check-circle"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.is_available') }}</small>
                                    <p>{{($value->is_available) == 1 ? __('index.yes_available'): __('index.notavailable')}}</p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="tag"></i></div>
                                <div class="text-content">
                                    <small class="text-muted d-block" style="font-size: 11px; font-weight: 600;">{{ __('index.type') }}</small>
                                    <p class="mb-0 fw-bold" style="font-size: 13px;">
                                        <a href="{{ route('admin.asset-types.show', $value->type->id) }}" style="color: #057db0; text-decoration: none;">
                                            {{ucfirst($value->type->name)}}
                                        </a>
                                    </p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="user"></i></div>
                                <div class="text-content">
                                    <small class="text-muted d-block" style="font-size: 11px; font-weight: 600;">ASSIGNED TO</small>
                                    <p class="mb-0 fw-bold" style="font-size: 13px; color: #334155;">
                                        @if(isset($value->latestAssignment) && is_null($value->latestAssignment->returned_date))
                                            {{ $value?->latestAssignment?->user?->name }}
                                        @else
                                            <span class="text-muted" style="font-weight: 400;">Unassigned</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="stats-footer-box">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="emp-group">
                                    @if(isset($value->latestAssignment) && $value->latestAssignment->status === 'assigned')
                                        {{-- Return Button - Theme Orange #FB8233 --}}
                                        <button class="btn btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#return_{{ $key }}" 
                                                style="border-radius: 20px; font-size: 12px; color: #FB8233; border: 1px solid #FB8233; background: transparent; padding: 4px 15px; font-weight: 600; transition: all 0.3s ease;"
                                                onmouseover="this.style.backgroundColor='#FB8233'; this.style.color='#ffffff';" 
                                                onmouseout="this.style.backgroundColor='transparent'; this.style.color='#FB8233';">
                                            Return
                                        </button>
                                    @else
                                        {{-- Assign Button - Theme Blue #057DB0 --}}
                                        <button class="btn btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#exampleModal_{{ $key }}" 
                                                style="border-radius: 20px; font-size: 12px; color: #057DB0; border: 1px solid #057DB0; background: transparent; padding: 4px 15px; font-weight: 600; transition: all 0.3s ease;"
                                                onmouseover="this.style.backgroundColor='#057DB0'; this.style.color='#ffffff';" 
                                                onmouseout="this.style.backgroundColor='transparent'; this.style.color='#057DB0';">
                                            Assign
                                        </button>
                                    @endif
                                </div>

                                <div class="action-dock">
                                    @can('show_asset')
                                        <a href="javascript:void(0)" class="btn-action edit" onclick="showAssetDetails('{{ route('admin.assets.show',$value->id) }}')">
                                            <i data-feather="eye" style="width:16px; height:16px;"></i>
                                        </a>
                                    @endcan
                                    @can('edit_assets')
                                        <a href="{{route('admin.assets.edit',$value->id)}}" class="btn-action edit" style="color: #1887B6 !important;">
                                            <i data-feather="edit-3"></i>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Assign Asset Modal --}}
            <div class="modal fade" id="exampleModal_{{ $key }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="border-radius: 15px;">
                        <div class="modal-header" style="background-color: #057db0; color: white; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                            <h5 class="modal-title text-white">Assign Asset</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.assign.asset') }}">
                            @csrf
                            <div class="modal-body">
                                {{-- Form fields... --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Assign Date</label>
                                        <input type="date" name="assigned_date" class="form-control" style="border-radius: 8px;" />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Close</button>
                                <button type="submit" class="btn" style="background-color: #057DB0; color: white; border-radius: 8px; padding: 8px 20px;">Assign</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Return Asset Modal --}}
            <div class="modal fade" id="return_{{ $key }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="border-radius: 15px;">
                        <div class="modal-header" style="background-color: #FB8233; color: white; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                            <h5 class="modal-title text-white">Return Asset</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.return.asset') }}">
                            @csrf
                            <div class="modal-body">
                                {{-- Return form fields... --}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Close</button>
                                <button type="submit" class="btn" style="background-color: #FB8233; color: white; border-radius: 8px; padding: 8px 20px;">Return</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            {{-- No records state --}}
        @endforelse
    </div>
</section>
@endsection