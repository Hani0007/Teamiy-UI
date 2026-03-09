@extends('layouts.master')

@section('title', __('index.role'))

@section('action', __('index.lists'))

@section('button')
    @can('create_role')
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm px-4 py-2" style="border-radius: 10px; font-weight: 600;">
            <i data-feather="plus-circle" class="me-2" style="width: 18px;"></i> 
            @lang('index.add_role')
        </a>
    @endcan
@endsection

@section('main-content')
<section class="content">
    @include('admin.section.flash_message')
    @include('admin.role.common.breadcrumb')

    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: #fcfcfd;">
                <div class="card-body p-0">
                    
                    <div class="d-none d-md-grid role-header-grid px-4 py-3 text-muted fw-bold border-bottom" style="font-size: 12px; letter-spacing: 0.5px; background: #fafbfc;">
                        <div># ID</div>
                        <div>ROLE NAME</div>
                        <div class="text-center">PERMISSIONS</div>
                        <div class="text-end">ACTIONS</div>
                    </div>

                    <div class="role-list-container p-3">
                        @forelse($roles as $key => $value)
                            <div class="role-item-row mb-2">
                                <div class="d-grid role-header-grid align-items-center px-3 py-3">
                                    <div class="text-muted fw-medium">
                                        <span class="badge bg-light text-dark rounded-pill">#{{ ++$key }}</span>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <div class="theme-avatar me-3">
                                            {{ strtoupper(substr($value->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">{{ ucfirst($value->name) }}</h6>
                                            <small class="text-muted" style="font-size: 10px;">Security Level access</small>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <a href="{{ route('admin.roles.permission', $value->id) }}" class="btn-permission-link">
                                            <i data-feather="key" class="me-1"></i> @lang('index.assign_permissions')
                                        </a>
                                    </div>

                                    <div class="text-end">
                                        <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                            <a href="{{ route('admin.roles.edit', $value->id) }}" class="btn btn-white btn-sm px-3 border-end" title="@lang('index.edit')">
                                                <i data-feather="edit-2" class="text-primary" style="width: 15px;"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-white btn-sm px-3 deleteRole" data-href="{{ route('admin.roles.delete', $value->id) }}" title="@lang('index.delete')">
                                                <i data-feather="trash-2" class="text-danger" style="width: 15px;"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i data-feather="layers" class="text-muted opacity-25 mb-3" style="width: 50px; height: 50px;"></i>
                                <h6 class="text-muted">@lang('index.no_records_found')</h6>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* CSS Grid for perfect alignment */
    .role-header-grid {
        display: grid;
        grid-template-columns: 80px 1fr 1fr 180px;
    }

    /* Row Hover Effect */
    .role-item-row {
        background: #fff;
        border: 1px solid #edf2f9;
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .role-item-row:hover {
        transform: translateY(-2px);
        border-color: var(--bs-primary); /* Uses theme primary color */
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    /* Custom Theme Avatar */
    .theme-avatar {
        width: 38px;
        height: 38px;
        background-color: var(--bs-primary); /* Auto-picks your theme's blue/primary */
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Permission Link Style */
    .btn-permission-link {
        text-decoration: none;
        color: var(--bs-primary);
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;
        background: rgba(var(--bs-primary-rgb), 0.08);
        transition: 0.2s;
    }

    .btn-permission-link:hover {
        background: var(--bs-primary);
        color: #fff;
    }

    .btn-white {
        background: #fff;
        border: 1px solid #edf2f9;
    }
    
    .btn-white:hover {
        background: #f8f9fa;
    }

    @media (max-width: 768px) {
        .role-header-grid {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 10px;
        }
        .text-end { text-align: center !important; }
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        feather.replace();

        $('.deleteRole').on('click', function (e) {
            e.preventDefault();
            let url = $(this).data('href');
            Swal.fire({
                title: '@lang('index.confirm_role_deletion')',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#FB8233',
                confirmButtonText: '@lang('index.yes')',
                cancelButtonText: '@lang('index.no')',
                borderRadius: '15px'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>
@endsection