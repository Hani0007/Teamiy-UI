@php
    use App\Helpers\PMHelper;
    use App\Models\Task;
    use Illuminate\Support\Str;
    use App\Helpers\AppHelper;
@endphp

@extends('layouts.master')

@section('title', __('index.tasks'))

@section('main-content')

<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
    @include('admin.section.flash_message')

    {{-- Top Header --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.tasks') }}</h2>
            @include('admin.task.common.breadcrumb')
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px; margin-top: 5px;">
                <i data-feather="check-square" style="width: 14px; vertical-align: middle;"></i> Task Management System
            </p>
        </div>
        
        @can('create_task')
            <a href="{{ route('admin.tasks.create') }}" style="text-decoration: none;">
                <button class="btn btn-primary">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.create_tasks') }}</span>
                </button>
            </a>
        @endcan
    </div>

    {{-- Filter Panel --}}
    <div class="glass-filter-panel mb-5 shadow-sm border-0">
        <form action="{{ route('admin.tasks.index') }}" method="get" class="row g-3 align-items-end">
            @if(!isset(auth()->user()->branch_id))
            <div class="col-xl-3 col-md-6">
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
                <label class="form-label fw-bold text-muted small">PROJECT</label>
                <select class="form-select modern-select shadow-none" id="project" name="project_id">
                    <option value="" {{ !isset($filterParameters['project_id']) ? 'selected' : '' }}></option>
                </select>
            </div>

            <div class="col-xl-3 col-md-6">
                <label class="form-label fw-bold text-muted small">STATUS</label>
                <select class="form-select modern-select shadow-none" id="status" name="status">
                    <option value="">@lang('index.search_by_status')</option>
                    @foreach(Task::STATUS as $value)
                        <option value="{{$value}}" {{ $filterParameters['status'] == $value ? 'selected' : '' }}>
                            {{(PMHelper::STATUS[$value])}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-xl-3 col-md-6 d-flex gap-2">
                <button type="submit" class="btn-theme-primary w-100 border-0 shadow-sm" style="background-color:#057db0 !important; color: white;">@lang('index.filter')</button>
                <a href="{{ route('admin.tasks.index') }}" class="btn-theme-outline w-100 text-decoration-none">@lang('index.reset')</a>
            </div>
        </form>
    </div>

    @php
        $StatusBadge = [
            'in_progress' => '#fb8233', 
            'not_started' => '#94a3b8', 
            'on_hold' => '#0ea5e9',
            'cancelled' => '#e46f21',
            'completed' => '#0ea5e9',
        ];
    @endphp

    {{-- Tasks Grid --}}
    <div class="row g-4">
        @forelse($tasks as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-md-6">
                <div class="branch-master-card">
                    <div class="card-glossy-header">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="check-circle" class="text-white"></i>
                            </div>
                            
                            <span class="badge" style="background: {{ $StatusBadge[$value->status] ?? '#057db0' }}; font-size: 10px; border-radius: 6px; padding: 5px 10px;">
                                {{ PMHelper::STATUS[$value->status] }}
                            </span>
                        </div>
                        
                        <h4 class="branch-name-display text-truncate">
                            <a href="{{route('admin.tasks.show',$value->id)}}" class="text-white text-decoration-none">
                                {{ ucfirst(Str::limit($value->name, 35)) }}
                            </a>
                        </h4>
                        
                        <div class="mt-3 position-relative" style="z-index: 2;">
                            <span class="branch-ref-pill">
                                <i data-feather="clock" style="width: 10px;"></i> {{ $value->taskRemainingDaysToComplete() > 0 ? $value->taskRemainingDaysToComplete() : 0 }} @lang('index.days_left')
                            </span>
                        </div>
                    </div>

                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="layers"></i></div>
                                <div class="text-content w-100">
                                    <small>@lang('index.project')</small>
                                    <p class="text-truncate" style="color: #057db0;">{{ucfirst($value?->project?->name)}}</p>
                                </div>
                            </div>
                            
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="calendar"></i></div>
                                <div class="text-content">
                                    <small>TIMELINE</small>
                                    <p style="font-size: 11px;">
                                        {{AppHelper::formatDateForView($value->start_date)}} - 
                                        <span style="color:#FB8233">{{AppHelper::formatDateForView($value->end_date)}}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="stats-footer-box pt-3 border-top mt-auto">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="emp-group">
                                    <div class="avatar-stack"><i data-feather="user" style="color: #057db0; width: 14px;"></i></div>
                                    <span class="emp-label">{{ $value->assignedMembers->count() ?? 0 }} Assigned</span>
                                </div>
                                <div class="action-dock">
                                    @can('show_task_detail')
                                        <a href="{{route('admin.tasks.show',$value->id)}}" class="btn-action edit" title="View Detail"><i data-feather="eye" style="height:16px; width:16px"></i></a>
                                    @endcan
                                    @can('edit_task')
                                        <a href="{{route('admin.tasks.edit',$value->id)}}" class="btn-action edit ms-1" title="Edit Task"><i data-feather="edit-3"></i></a>
                                    @endcan
                                    @can('delete_task')
                                        <a href="javascript:void(0)" data-href="{{route('admin.tasks.delete',$value->id)}}" class="btn-action delete deleteProject ms-1" title="Delete Task"><i data-feather="trash-2"></i></a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No tasks found.</p>
            </div>
        @endforelse
    </div>
    <div class="mt-5 d-flex justify-content-center">{{ $tasks->appends($_GET)->links() }}</div>
</section>

{{-- Note: Styles are kept in the parent/master or previous block for consistent UI --}}

@endsection

@section('scripts')
    @include('admin.task.common.scripts')
    <script>
        $(document).ready(function () {
            feather.replace();

            $('.deleteProject').on('click', function (e) {
                e.preventDefault();
                let href = $(this).attr('data-href');
                Swal.fire({
                    title: 'Delete Task?',
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