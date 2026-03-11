@php use App\Models\Asset; @endphp
@php use App\Helpers\AppHelper; @endphp
@extends('layouts.master')

@section('title', __('index.assets'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0;">{{ __('index.assets') }}</h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px;">
                <i data-feather="box" style="width: 14px; vertical-align: middle;"></i> Asset Inventory Management
            </p>
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

    {{-- Grid Section --}}
    <div class="row g-4 justify-content-start">
        @forelse($assetLists as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    <div class="card-glossy-header">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="package"></i>
                            </div>
                        </div>
                        <h4 class="branch-name-display">{{ucfirst($value->name)}}</h4>
                        <span class="branch-ref-pill">Asset ID: #{{$value->id}}</span>
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
                                    <small>{{ __('index.type') }}</small>
                                    <p>
                                        <a href="{{ route('admin.asset-types.show', $value->type->id) }}" style="color: #057db0; text-decoration: none; font-weight: 600;">
                                            {{ucfirst($value->type->name)}}
                                        </a>
                                    </p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="user"></i></div>
                                <div class="text-content">
                                    <small>ASSIGNED TO</small>
                                    <p>
                                        @if(isset($value->latestAssignment) && is_null($value->latestAssignment->returned_date))
                                            {{ $value?->latestAssignment?->user?->name }}
                                        @else
                                            <span class="text-muted">Unassigned</span>
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