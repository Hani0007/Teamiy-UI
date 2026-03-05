@php
    use App\Helpers\AppHelper;
@endphp

@extends('layouts.master')

@section('title', __('index.project'))

@section('main-content')

<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
    @include('admin.section.flash_message')

    {{-- Top Header --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.project') }}</h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px; margin-top: 5px;">
                <i data-feather="grid" style="width: 14px; vertical-align: middle;"></i> Organization Projects
            </p>
        </div>
        
        @if (AppHelper::canAccess('create_project'))
            <a href="{{ route('admin.projects.create') }}" style="text-decoration: none;">
                <button class="btn-premium-add">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.create_project') }}</span>
                </button>
            </a>
        @endif
    </div>

    {{-- Filter Panel (Premium Glass Style) --}}
    <div class="glass-filter-panel mb-5 shadow-sm border-0">
        <form action="{{ route('admin.projects.index') }}" method="get" class="row g-3 align-items-end">
            @if(!isset(auth()->user()->branch_id))
            <div class="col-xl-2 col-md-6">
                <label class="form-label fw-bold text-muted small">BRANCH</label>
                <select class="form-select modern-select shadow-none" id="branch_id" name="branch_id">
                    <option selected disabled>{{ __('index.select_branch') }}</option>
                    @if(isset($companyDetail))
                        @foreach($companyDetail->branches()->get() as $key => $branch)
                            <option value="{{$branch->id}}" {{ $filterParameters['branch_id'] == $branch->id ? 'selected': '' }}>
                                {{ucfirst($branch->name)}}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            @endif

            <div class="col-xl-3 col-md-6">
                <label class="form-label fw-bold text-muted small">PROJECT NAME</label>
                <select class="form-select modern-select shadow-none" id="project_name" name="project_name">
                    <option value="" {{ !isset($filterParameters['project_name']) ? 'selected' : '' }}>{{ __('index.search_by_project') }}</option>
                </select>
            </div>

            <div class="col-xl-2 col-md-6">
                <label class="form-label fw-bold text-muted small">STATUS</label>
                <select class="form-select modern-select shadow-none" id="status" name="status">
                    <option value="">{{ __('index.search_by_status') }}</option>
                    @foreach(\App\Models\Project::STATUS as $value)
                        <option value="{{ $value }}" {{ $filterParameters['status'] == $value ? 'selected' : '' }}>
                            {{ \App\Helpers\PMHelper::STATUS[$value] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-xl-2 col-md-6">
                <label class="form-label fw-bold text-muted small">MEMBERS</label>
                <select class="form-select modern-select shadow-none" id="filter" name="members[]" multiple></select>
            </div>

            <div class="col-xl-3 col-md-6 d-flex gap-2">
                <button type="submit" class="btn-theme-primary w-100 border-0" style="background-color:#057db0; color:#fff;">{{ __('index.filter') }}</button>
                <a href="{{ route('admin.projects.index') }}" class="btn-theme-outline w-100 text-decoration-none">
                    {{ __('index.reset') }}
                </a>
            </div>
        </form>
    </div>

    @php
        $StatusBadge = [
            'in_progress' => '#fb8233', // Theme Orange
            'not_started' => '#94a3b8', 
            'on_hold' => '#0ea5e9',
            'cancelled' => '#ef4444',
            'completed' => '#10b981',
        ];
    @endphp

    {{-- Projects Grid --}}
    <div class="row g-4">
        @forelse($projects as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-md-6">
                <div class="branch-master-card">
                    {{-- Blue Glossy Header --}}
                    <div class="card-glossy-header">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                @if(!empty($value->cover_pic) && file_exists(public_path(\App\Models\Project::UPLOAD_PATH . $value->cover_pic)))
                                    <img src="{{ asset(\App\Models\Project::UPLOAD_PATH . $value->cover_pic) }}" class="w-100 h-100" style="object-fit: cover;">
                                @else
                                    <i data-feather="briefcase"></i>
                                @endif
                            </div>
                            
                            <label class="switch-modern">
                                <input class="toggleStatus" data-href="{{ route('admin.projects.toggle-status', $value->id) }}"
                                       type="checkbox" {{($value->is_active) == 1 ? 'checked' : ''}}>
                                <span class="slider-modern round"></span>
                            </label>
                        </div>
                        
                        <h4 class="branch-name-display text-truncate">
                            <a href="{{ route('admin.projects.show', $value->id) }}" class="text-white text-decoration-none">{{ ucfirst($value->name) }}</a>
                        </h4>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3 position-relative" style="z-index: 2;">
                            <span class="branch-ref-pill">Project ID: #{{ $value->id }}</span>
                            <span class="badge" style="background: {{ $StatusBadge[$value->status] ?? '#057db0' }}; font-size: 10px; border-radius: 6px;">
                                {{ \App\Helpers\PMHelper::STATUS[$value->status] }}
                            </span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="calendar"></i></div>
                                <div class="text-content">
                                    <small>DEADLINE</small>
                                    <p class="text-danger fw-bold" style="color: #ff8233 !important;">{{ \App\Helpers\AppHelper::formatDateForView($value->deadline) }}</p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="trending-up"></i></div>
                                <div class="text-content w-100">
                                    <small>PROGRESS ({{ $value->getProjectProgressInPercentage() }}%)</small>
                                    <div class="progress mt-1" style="height: 6px; border-radius: 10px; background: #f1f5f9;">
                                        <div class="progress-bar" style="width: {{ $value->getProjectProgressInPercentage() }}%; background: #057db0; border-radius: 10px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="stats-footer-box">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="emp-group">
                                    <div class="avatar-stack">
                                        <i data-feather="users" style="color: #057db0;"></i>
                                    </div>
                                    <span class="emp-label">{{ $value->assignedMembers->count() }} Members</span>
                                </div>

                                <div class="action-dock">
                                    @can('show_project_detail')
                                        <a href="{{ route('admin.projects.show', $value->id) }}" class="btn-action view" title="View Detail">
                                            <i data-feather="eye"></i>
                                        </a>
                                    @endcan
                                    @can('edit_project')
                                        <a href="{{ route('admin.projects.edit', $value->id) }}" class="btn-action edit" title="Edit Project">
                                            <i data-feather="edit-3"></i>
                                        </a>
                                    @endcan
                                    @can('delete_project')
                                        <a data-href="{{ route('admin.projects.delete', $value->id) }}" class="btn-action delete deleteProject cursor-pointer" title="Delete Project">
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
                    <p class="text-muted mt-3">No project records found.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $projects->appends($_GET)->links() }}
    </div>
</section>

@endsection

@section('scripts')
    @include('admin.project.common.scripts')
    <script>
        $(document).ready(function () {
            feather.replace();

            // Status Toggle Confirmation
            $('.toggleStatus').change(function (e) {
                e.preventDefault();
                let status = $(this).prop('checked') === true ? 1 : 0;
                let href = $(this).data('href');
                Swal.fire({
                    title: 'Update Status?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, update it!',
                    confirmButtonColor: '#057db0'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    } else {
                        $(this).prop('checked', !status);
                    }
                });
            });

            // Delete Confirmation
            $('.deleteProject').click(function (e) {
                e.preventDefault();
                let href = $(this).data('href');
                Swal.fire({
                    title: 'Delete Project?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    confirmButtonColor: '#ef4444'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        });
    </script>
@endsection