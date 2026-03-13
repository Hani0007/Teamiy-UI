@extends('layouts.master')

@section('title', __('index.department'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0;">
                {{ __('index.department') }}
            </h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px;">
                <i data-feather="layers" style="width: 14px; vertical-align: middle;"></i> {{ __('index.department_lists') }} overview
            </p>
        </div>
        
        @can('create_department')
            <a href="{{ route('admin.departments.create')}}" style="text-decoration: none;">
                <button class="btn-premium-add">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.add_department') }}</span>
                </button>
            </a>
        @endcan
    </div>

    <!--<div class="glass-filter-panel mb-5">
        <form class="row g-3 align-items-center" action="{{route('admin.departments.index')}}" method="get">
            @if(!isset(auth()->user()->branch_id))
            <div class="col-xl-3 col-lg-4">
                <select class="form-select modern-select" id="branch_id" name="branch">
                    <option {{ !isset($filterParameters['branch']) ? 'selected' : '' }} disabled>{{ __('index.select_branch') }}</option>
                    @foreach($branch as $key => $value)
                        <option value="{{ $value->id }}" {{ (isset($filterParameters['branch']) && $value->id == $filterParameters['branch'] ) ? 'selected' : '' }}>
                            {{ ucfirst($value->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            
            <div class="col-xl-4 col-lg-4">
                <div class="modern-search-box">
                    <i data-feather="search"></i>
                    <input type="text" placeholder="{{ __('index.search_by_department_name') }}" name="name" value="{{$filterParameters['name']}}" class="form-control shadow-none">
                </div>
            </div>

            <div class="col-xl-2 col-lg-2 col-6">
                <select class="form-select modern-select" name="per_page">
                    <option value="10" {{ ($filterParameters['per_page']) == 10 ? 'selected' : '' }}>10 Rows</option>
                    <option value="25" {{ ($filterParameters['per_page']) == 25 ? 'selected' : '' }}>25 Rows</option>
                    <option value="50" {{ ($filterParameters['per_page']) == 50 ? 'selected' : '' }}>50 Rows</option>
                </select>
            </div>

            <div class="col-xl-3 col-lg-2 col-6 d-flex gap-2">
                <button type="submit" class="btn-theme-primary w-100">{{ __('index.filter') }}</button>
                <a class="btn-theme-outline w-100 text-decoration-none" href="{{ route('admin.departments.index') }}">{{ __('index.reset') }}</a>
            </div>
        </form>
    </div>-->

    <div class="row g-4 justify-content-start">
        @forelse($departments as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    <div class="card-glossy-header">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="briefcase"></i>
                            </div>
                            <label class="switch-modern">
                                <input class="toggleStatus" href="{{ route('admin.departments.toggle-status', $value->id) }}"
                                       type="checkbox" {{ ($value->is_active) == 1 ? 'checked' : '' }}>
                                <span class="slider-modern round"></span>
                            </label>
                        </div>
                        <h4 class="branch-name-display">{{ ucfirst($value->dept_name) }}</h4>
                        <span class="branch-ref-pill">{{ $value->branch->name }}</span>
                    </div>

                    <div class="card-white-body">
                        <div class="info-listing">
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="user"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.department_head') }}</small>
                                    <p>{{ isset($value->departmentHead) ? $value->departmentHead->name : __('index.not_available') }}</p>
                                </div>
                            </div>
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="server"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.branch') }}</small>
                                    <p>{{ $value->branch->name ?: 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="phone"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.phone') }}</small>
                                    <p>{{ $value->phone ?: 'N/A' }}</p>
                                </div>
                            </div> --}}
                        </div>

                        <div class="stats-footer-box">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="emp-group">
                                    <div class="avatar-stack">
                                        <i data-feather="users"></i>
                                    </div>
                                    <span class="emp-label">{{ $value->employees_count }} Members</span>
                                </div>
                                <div class="action-dock">
                                    @can('edit_department')
                                        <a href="{{ route('admin.departments.edit', $value->id) }}" class="btn-action edit" title="Edit">
                                            <i data-feather="edit-3"></i>
                                        </a>
                                    @endcan
                                    @can('delete_department')
                                        <a data-href="{{ route('admin.departments.delete', $value->id) }}" class="btn-action delete deleteBranch cursor-pointer" title="Delete">
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
        {{ $departments->appends($_GET)->links() }}
    </div>
</section>


@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // Backend Select2 logic
        $("#branch_id").select2({
            placeholder: "Select Branch"
        });

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Status Toggle Logic
        $('.toggleStatus').change(function (event) {
            event.preventDefault();
            var status = $(this).prop('checked') === true ? 1 : 0;
            var href = $(this).attr('href');
            Swal.fire({
                title: '{{ __('index.are_you_sure_change_status') }}',
                showDenyButton: true,
                confirmButtonText: `{{ __('index.yes') }}`,
                denyButtonText: `{{ __('index.no') }}`,
                confirmButtonColor: '#057db0',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                } else {
                    $(this).prop('checked', !status);
                }
            })
        });

        // Delete Logic
        $('.deleteBranch').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: '{{ __('index.are_you_sure_delete_department') }}',
                showDenyButton: true,
                confirmButtonText: `{{ __('index.yes') }}`,
                denyButtonText: `{{ __('index.no') }}`,
                confirmButtonColor: '#fb8233',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            })
        });

        feather.replace();
    });
</script>
@endsection