@extends('layouts.master')

@section('title', 'Permission')

@section('action', __('index.lists'))

@section('button')
    @php $guardName = ($guard === 'admin') ? 'a' : 'e'; @endphp
    <a href="{{ route('admin.permissions.create', ['slug' => $guardName]) }}">
        <button class="btn btn-primary d-flex align-items-center shadow-sm px-4 py-2" style="border-radius: 10px; font-weight: 600;">
            <i data-feather="plus" class="me-2" style="width: 18px;"></i> Add Permission
        </button>
    </a>
@endsection

@section('main-content')
<section class="content">
    @include('admin.section.flash_message')
    @include('admin.permission.common.breadcrumb')

    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: #fcfcfd;">
                <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 15px 15px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Permission List</h5>
                        <span class="badge bg-soft-primary text-primary px-3">{{ $permissions->total() }} Total</span>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="d-none d-md-grid permission-grid px-4 py-3 text-muted fw-bold border-bottom" style="font-size: 12px; letter-spacing: 0.5px; background: #fafbfc;">
                        <div># ID</div>
                        <div>PERMISSION NAME</div>
                        <div>GUARD TYPE</div>
                        <div class="text-end">MANAGEMENT</div>
                    </div>

                    <div class="permission-list p-3">
                        @php $count = ($permissions->currentPage() - 1) * $permissions->perPage() + 1; @endphp
                        @forelse($permissions as $key => $value)
                            <div class="permission-item-row mb-2 shadow-sm-hover">
                                <div class="d-grid permission-grid align-items-center px-3 py-3">
                                    
                                    <div class="text-muted fw-medium">
                                        <span class="badge bg-light text-dark rounded-pill" style="min-width: 35px;">#{{ $count++ }}</span>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <div class="perm-icon me-3">
                                            <i data-feather="lock" style="width: 14px;"></i>
                                        </div>
                                        <span class="fw-bold text-dark">{{ $value->name }}</span>
                                    </div>

                                    <div>
                                        <span class="badge rounded-pill {{ $value->guard_name == 'admin' ? 'bg-soft-info text-info' : 'bg-soft-success text-success' }}" style="font-size: 10px;">
                                            {{ strtoupper($value->guard_name) }}
                                        </span>
                                    </div>

                                    <div class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{route('admin.permissions.edit',$value->id)}}" 
                                               class="btn-action-circle edit" 
                                               title="@lang('index.edit')">
                                                <i data-feather="edit-3"></i>
                                            </a>

                                            <form action="{{ route('admin.permissions.destroy', $value->id) }}" method="POST" class="d-inline deleteForm">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="guard" value="{{ $guardName }}" />
                                                <button type="button" class="btn-action-circle delete deleteRole" title="@lang('index.delete')">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i data-feather="shield-off" class="text-muted opacity-25 mb-3" style="width: 60px; height: 60px;"></i>
                                <h6 class="text-muted">@lang('index.no_records_found')</h6>
                            </div>
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top bg-light-alt" style="border-radius: 0 0 15px 15px;">
                        <small class="text-muted">Showing {{ $permissions->firstItem() }} to {{ $permissions->lastItem() }} of {{ $permissions->total() }}</small>
                        <div class="pagination-modern">
                            {{ $permissions->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Grid Layout */
    .permission-grid {
        display: grid;
        grid-template-columns: 80px 1fr 150px 150px;
    }

    /* Row Styling */
    .permission-item-row {
        background: #fff;
        border: 1px solid #edf2f9;
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .permission-item-row:hover {
        transform: translateY(-2px);
        border-color: var(--bs-primary);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    /* Icon Placeholder */
    .perm-icon {
        width: 32px;
        height: 32px;
        background: rgba(var(--bs-primary-rgb), 0.1);
        color: var(--bs-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    /* Action Buttons */
    .btn-action-circle {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: 0.2s;
        text-decoration: none;
    }

    .edit { background: rgba(var(--bs-primary-rgb), 0.08); color: var(--bs-primary); }
    .edit:hover { background: var(--bs-primary); color: #fff; }

    .delete { background: #fff5f5; color: #ff4747; }
    .delete:hover { background: #ff4747; color: #fff; }

    /* Badges */
    .bg-soft-primary { background: rgba(var(--bs-primary-rgb), 0.1); }
    .bg-soft-info { background: #e0f7fa; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-light-alt { background: #fafbfc; }

    /* Custom Pagination Fix */
    .pagination-modern .pagination { margin-bottom: 0; }
    .pagination-modern .page-link { border-radius: 8px; margin: 0 2px; border: none; font-size: 13px; }

    @media (max-width: 768px) {
        .permission-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
        .permission-grid div:first-child { display: none; }
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        feather.replace();

        $(document).on('click', '.deleteRole', function (event) {
            event.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Delete Permission?',
                text: "This might affect user access across the system.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                confirmButtonColor: '#ff4747',
                cancelButtonColor: '#FB8233',
                borderRadius: '15px'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection