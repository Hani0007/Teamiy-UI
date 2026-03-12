@extends('layouts.master')

@section('title', __('index.title_branch'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0;">
                {{ __('index.branch') }}
            </h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px;">
                <i data-feather="layers" style="width: 14px; vertical-align: middle;"></i> Organization Units
            </p>
        </div>
        
        @can('create_branch')
            <a href="{{ route('admin.branch.create') }}" style="text-decoration: none;">
                <button class="btn btn-primary">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.add_branch') }}</span>
                </button>
            </a>
        @endcan
    </div>

    <!--<div class="glass-filter-panel mb-5">
        <form action="{{ route('admin.branch.index') }}" method="get" class="row g-3 align-items-center">
            <div class="col-xl-6 col-lg-5">
                <div class="modern-search-box">
                    <i data-feather="search"></i>
                    <input type="text" name="name" value="{{($filterParameters['name'])}}" class="form-control shadow-none" placeholder="{{ __('index.search_by_branch_name') }}...">
                </div>
            </div>
            <div class="col-xl-2 col-lg-3 col-6">
                <select class="form-select modern-select" name="per_page">
                    <option value="10" {{($filterParameters['per_page']) == 10 ? 'selected': ''}}>10 Entries</option>
                    <option value="25" {{($filterParameters['per_page']) == 25 ? 'selected': ''}}>25 Entries</option>
                </select>
            </div>
            <div class="col-xl-4 col-lg-4 col-6 d-flex gap-2">
                <button type="submit" class="btn-theme-primary w-100">{{ __('index.filter') }}</button>
                <a href="{{route('admin.branch.index')}}" class="btn-theme-outline w-100 text-decoration-none">{{ __('index.reset') }}</a>
            </div>
        </form>
    </div>-->

    <div class="row g-4 justify-content-start">
        @forelse($branches as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    <div class="card-glossy-header">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="home"></i>
                            </div>
                            <label class="switch-modern">
                                <input class="toggleStatus" href="{{route('admin.branch.toggle-status',$value->id)}}"
                                       type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                <span class="slider-modern round"></span>
                            </label>
                        </div>
                        <h4 class="branch-name-display">{{ucfirst($value->name)}}</h4>
                        <span class="branch-ref-pill">Branch ID: #{{$value->id}}</span>
                    </div>

                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="map-pin"></i></div>
                                <div class="text-content">
                                    <small>LOCATION</small>
                                    <p>{{$value->address ?: 'N/A'}}</p>
                                </div>
                            </div>
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="phone"></i></div>
                                <div class="text-content">
                                    <small>CONTACT</small>
                                    <p>{{$value->phone ?: 'N/A'}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="stats-footer-box">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="emp-group">
                                    <div class="avatar-stack">
                                        <i data-feather="users"></i>
                                    </div>
                                    <span class="emp-label">{{$value->employees_count }} Staff Members</span>
                                </div>
                                <div class="action-dock">
                                    @can('edit_branch')
                                        <a href="{{route('admin.branch.edit',$value->id)}}" class="btn-action edit" title="Edit Branch">
                                            <i data-feather="edit-3"></i>
                                        </a>
                                    @endcan
                                    @can('delete_branch')
                                        <a data-href="{{route('admin.branch.delete',$value->id)}}" class="btn-action delete deleteBranch cursor-pointer" title="Delete Branch">
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
                    <p class="text-muted mt-3">No branch records available.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{$branches->appends($_GET)->links()}}
    </div>
</section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            feather.replace();

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            $('.toggleStatus').change(function (event) {
                event.preventDefault();
                var status = $(this).prop('checked') === true ? 1 : 0;
                var href = $(this).attr('href');
                Swal.fire({
                    title: 'Update Status?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    confirmButtonColor: '#057db0'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    } else {
                        $(this).prop('checked', !status);
                    }
                })
            })

            $('.deleteBranch').click(function (event) {
                event.preventDefault();
                let href = $(this).data('href');
                Swal.fire({
                    title: 'Delete Branch?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    confirmButtonColor: '#ef4444'
                }).then((result) => {
                    if (result.isConfirmed) window.location.href = href;
                })
            })
        });
    </script>
@endsection