@extends('layouts.master')
@section('title', __('index.post'))

@section('main-content')

<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
    @include('admin.section.flash_message')

    {{-- Breadcrumbs & Top Header --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.posts') }}</h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px; margin-top: 5px;">
                <i data-feather="layers" style="width: 14px; vertical-align: middle;"></i> Organization's Management System
            </p>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background: transparent; padding: 0; margin-bottom: 8px;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: #94a3b8; text-decoration: none; font-size: 12px;">{{ __('index.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}" style="color: #94a3b8; text-decoration: none; font-size: 12px;">{{ __('index.post_section') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #057db0; font-weight: 600;">{{ __('index.posts') }}</li>
                </ol>
            </nav>
        </div>

        @can('create_post')
            <a href="{{ route('admin.posts.create') }}" style="text-decoration: none;">
                <button class="btn btn-primary">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.add_post') }}</span>
                </button>
            </a>
        @endcan
    </div>

    {{-- Filter Panel (Premium Glass Style) --}}
    <div class="glass-filter-panel mb-5 shadow-sm border-0" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border-radius: 20px; padding: 25px;">
        <form action="{{route('admin.posts.index')}}" method="get" class="row g-3 align-items-end">
            @if(!isset(auth()->user()->branch_id))
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px;">BRANCH</label>
                    <select class="form-select modern-select shadow-none" id="branch_id" name="branch_id" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0;">
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

            <div class="col-xl-3 col-lg-3 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px;">DEPARTMENT</label>
                <select class="form-select modern-select shadow-none" name="department_id" id="department_id" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <option selected disabled>{{ __('index.select_department') }}</option>
                </select>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px;">SEARCH POST</label>
                <div class="modern-search-box" style="position: relative;">
                    <i data-feather="search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                    <input type="text" placeholder="{{ __('index.search_by_post_name') }}" name="name" value="{{ $filterParameters['name'] ?? '' }}" 
                           class="form-control shadow-none" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; padding-left: 45px;">
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 d-flex gap-2">
                <button type="submit" class="btn-theme-primary w-100 border-0" style="background-color:#057db0; color:#fff; height: 48px; border-radius: 12px; font-weight: 600; transition: all 0.3s ease;">
                    {{ __('index.filter') }}
                </button>
                <a href="{{route('admin.posts.index')}}" class="btn-theme-outline w-100 text-decoration-none d-flex align-items-center justify-content-center" 
                   style="height: 48px; border: 1px solid #e2e8f0; border-radius: 12px; color: #64748b; background: #fff; font-weight: 600;">
                    {{ __('index.reset') }}
                </a>
            </div>
        </form>
    </div>

    {{-- Posts Grid --}}
    <div class="row g-4 justify-content-start">
        @forelse($posts as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    <div class="card-glossy-header">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="award"></i>
                            </div>
                            <label class="switch-modern">
                                <input class="toggleStatus" href="{{ route('admin.posts.toggle-status', $value?->id) }}"
                                       type="checkbox" {{ ($value?->is_active == 1) ? 'checked' : '' }}>
                                <span class="slider-modern round"></span>
                            </label>
                        </div>
                        <h4 class="branch-name-display text-truncate">{{ ucfirst($value?->post_name) }}</h4>
                        <span class="branch-ref-pill">ID: #{{ $value->id }}</span>
                    </div>

                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="layers"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.department') }}</small>
                                    <p class="fw-bold" style="color: #475569;">{{ ucfirst($value?->department?->dept_name) ?: 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="stats-footer-box">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="emp-group">
                                    <div class="avatar-stack cursor-pointer" id="showEmployee" data-employee="{{ $value?->employees }}">
                                        <i data-feather="users" style="color: #057db0;"></i>
                                    </div>
                                    <span class="emp-label">{{ $value?->employees_count }} {{ __('index.total_employee') }}</span>
                                </div>
                                
                                <div class="action-dock">
                                    @can('edit_post')
                                        <a href="{{ route('admin.posts.edit', $value?->id) }}" class="btn-action edit" title="Edit">
                                            <i data-feather="edit-3"></i>
                                        </a>
                                    @endcan
                                    @can('delete_post')
                                        <a href="#" data-href="{{ route('admin.posts.delete', $value?->id) }}" class="btn-action delete deletePost" title="Delete">
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
                    <p class="text-muted mt-3"><b>{{ __('index.no_records_found') }}</b></p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $posts->appends($_GET)->links() }}
    </div>

    @include('admin.post.show')

</section>

@endsection

@section('scripts')
    @include('admin.post.common.scripts')
    <script>
        $(document).ready(function() {
            feather.replace();
        });
    </script>
@endsection