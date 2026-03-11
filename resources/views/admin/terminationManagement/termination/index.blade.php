{{--
@extends('layouts.master')

@section('title',__('index.termination'))

@section('action',__('index.lists'))

@section('button')

    <div class="float-end">

        @can('create_termination')
            <a href="{{ route('admin.termination.create')}}">
                <button class="btn btn-primary">
                    <i class="link-icon" data-feather="plus"></i>{{ __('index.add_termination') }}
                </button>
            </a>
        @endcan
        <!-- @can('termination_type_list')
            <a href="{{ route('admin.termination-types.index')}}">
                <button class="btn btn-primary">
                    <i class="link-icon" data-feather="list"></i>{{ __('index.termination_types') }}
                </button>
            </a>
        @endcan -->
    </div>

@endsection

@section('main-content')
    <section class="content">
        @include('admin.section.flash_message')

        @include('admin.terminationManagement.termination.common.breadcrumb')
        

        <div class="card support-main">
            <div class="card-header">
                <h6 class="card-title mb-0">{{ __('index.termination_list') }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('index.employee') }}</th>
                            <!-- <th>{{ __('index.termination_type') }}</th> -->
                            <th class="text-center">{{ __('index.notice_date') }}</th>
                            <th class="text-center">{{ __('index.termination_date') }}</th>
                            <th class="text-center">{{ __('index.status') }}</th>
                            @canany(['show_termination','delete_termination','update_termination'])
                                <th class="text-center">{{ __('index.action') }}</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $color = [
                                \App\Enum\TerminationStatusEnum::approved->value => 'success',
                                \App\Enum\TerminationStatusEnum::onReview->value => 'info',
                                \App\Enum\TerminationStatusEnum::pending->value => 'secondary',
                                \App\Enum\TerminationStatusEnum::cancelled->value => 'warning',
                            ];


                            ?>
                            @forelse($terminationLists as $key => $value)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{ $value->employee?->name }}</td>
                                    <!-- <td>{{ $value->terminationType?->title }}</td> -->
                                    <td class="text-center">{{ \App\Helpers\AppHelper::formatDateForView($value->notice_date) }}</td>
                                    <td class="text-center">{{ \App\Helpers\AppHelper::formatDateForView($value->termination_date) }}</td>
                                    <td class="text-center">
                                        <a href=""
                                           class="terminationStatusUpdate"
                                           data-href="{{route('admin.termination.update-status',$value->id)}}"
                                           data-status="{{$value->status}}"
                                           data-reason="{{$value->admin_remark}}"
                                           data-id="{{$value->id}}"
                                        >
                                            <button class="btn btn-{{ $color[$value->status] }} btn-xs">
                                                {{ ucfirst($value->status) }}
                                            </button>
                                        </a>

                                    </td>
                                    <td class="text-center">
                                        <ul class="d-flex list-unstyled mb-0 justify-content-center">
                                            @can('update_termination')
                                                <li class="me-2">
                                                    <a href="{{route('admin.termination.edit',$value->id)}}" title="{{ __('index.edit') }}">
                                                        <i class="link-icon" data-feather="edit"></i>
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('show_termination')
                                                <li class="me-2">
                                                    <a href="{{route('admin.termination.show',$value->id)}}" title="{{ __('index.show_detail') }}">
                                                        <i class="link-icon" data-feather="eye"></i>
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('delete_termination')
                                                <li>
                                                    <a class="delete"
                                                       data-title="{{$value->name}} Detail"
                                                       data-href="{{route('admin.termination.delete',$value->id)}}"
                                                       title="{{ __('index.delete') }}">
                                                        <i class="link-icon"  data-feather="delete"></i>
                                                    </a>
                                                </li>
                                            @endcan
                                          </ul>
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
            {{ $terminationLists->appends($_GET)->links() }}
        </div>
    </section>
    @include('admin.terminationManagement.termination.common.status_update')

@endsection

@section('scripts')
    @include('admin.terminationManagement.termination.common.scripts')
@endsection

--}}
@extends('layouts.master')

@section('title', __('index.termination'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0;">
                {{ __('index.termination') }}
            </h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px;">
                <i data-feather="user-minus" style="width: 14px; vertical-align: middle;"></i> Employee Exit Management
            </p>
        </div>
        
        @can('create_termination')
            <a href="{{ route('admin.termination.create') }}" style="text-decoration: none;">
                <button class="btn btn-primary">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.add_termination') }}</span>
                </button>
            </a>
        @endcan
    </div>

    {{-- Cards Grid --}}
    <div class="row g-4 justify-content-start">
        @php
            // Status Theme Colors
            $statusTheme = [
                'approved'  => ['bg' => '#057db0', 'text' => '#fff'],
                'accepted'  => ['bg' => '#057db0', 'text' => '#fff'],
                'rejected'  => ['bg' => '#ef4444', 'text' => '#fff'],
                'cancelled' => ['bg' => '#ef4444', 'text' => '#fff'],
                'pending'   => ['bg' => '#FB8233', 'text' => '#fff'],
                'on_review' => ['bg' => '#F8FAFC', 'text' => '#fff'],
            ];
        @endphp

        @forelse($terminationLists as $key => $value)
            @php
                $currentStatus = strtolower($value->status);
                $theme = $statusTheme[$currentStatus] ?? ['bg' => '#64748b', 'text' => '#fff'];
            @endphp
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    <div class="card-glossy-header" style="background: linear-gradient(135deg, #057db0 0%, #046691 100%);">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="user-x"></i>
                            </div>
                            
                            {{-- Clickable Status Badge --}}
                            <a href="javascript:void(0)" 
                               class="terminationStatusUpdate text-decoration-none"
                               data-href="{{route('admin.termination.update-status',$value->id)}}"
                               data-status="{{$value->status}}"
                               data-reason="{{$value->admin_remark}}"
                               data-id="{{$value->id}}">
                               <span class="badge shadow-sm" style="background-color: {{ $theme['bg'] }}; color: {{ $theme['text'] }}; border: none;">
                                   {{ ucfirst($value->status) }}
                               </span>
                            </a>
                        </div>
                        <h4 class="branch-name-display">{{ $value->employee?->name }}</h4>
                        {{-- Termination ID at Top --}}
                        <span class="branch-ref-pill">Termination ID: #{{$value->id}}</span>
                    </div>

                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="calendar"></i></div>
                                <div class="text-content">
                                    <small>TERMINATION DATE</small>
                                    <p>{{ \App\Helpers\AppHelper::formatDateForView($value->termination_date) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="stats-footer-box">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="emp-group">
                                    {{-- Notice Date at Footer --}}
                                    <div class="avatar-stack">
                                        <i data-feather="clock" style="width: 14px; color: #64748b;"></i>
                                    </div>
                                    <span class="emp-label text-muted" style="font-size: 11px; font-weight: 600;">Notice: {{ \App\Helpers\AppHelper::formatDateForView($value->notice_date) }}</span>
                                </div>
                                <div class="action-dock">
                                    @can('show_termination')
                                        <a href="{{route('admin.termination.show',$value->id)}}" class="btn-action edit" title="View Detail" style="background: #e0f2fe; color: #0369a1;">
                                            <i data-feather="eye" style="width:16px; height:16px;"></i>
                                        </a>
                                    @endcan

                                    @can('update_termination')
                                        <a href="{{route('admin.termination.edit',$value->id)}}" class="btn-action edit" title="Edit">
                                            <i data-feather="edit-3"></i>
                                        </a>
                                    @endcan
                                    
                                    @can('delete_termination')
                                        <a data-href="{{route('admin.termination.delete',$value->id)}}" class="btn-action delete deleteBranch cursor-pointer" title="Delete">
                                            <i data-feather="trash-2"></i>
                                        </a>
                                    @endcan
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

    <div class="mt-5 d-flex justify-content-center">
        {{ $terminationLists->appends($_GET)->links() }}
    </div>
</section>

@include('admin.terminationManagement.termination.common.status_update')
@endsection

@section('scripts')
    @include('admin.terminationManagement.termination.common.scripts')
    <script>
        $(document).ready(function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection