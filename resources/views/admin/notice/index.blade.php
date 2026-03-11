@extends('layouts.master')

@section('title', __('index.notices'))
@section('action', __('index.lists'))

@section('main-content')
<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
    @include('admin.section.flash_message')

    {{-- 1. Modern Breadcrumbs & Top Header (As per Sample) --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.notices') }}</h2>
            @include('admin.notice.common.breadcrumb')
        </div>
        <button onclick="window.location.href='{{ route('admin.notices.create')}}'" class="btn-premium-add shadow-sm" style="background: #057db0; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 600; border: none; display: flex; align-items: center; gap: 8px;">
            <i data-feather="plus" style="width: 20px;"></i>
            <span>{{ __('index.create_notice') }}</span>
        </button>
    </div>

    {{-- 2. Glass-morphism Filter Panel (As per Sample) --}}
    <div class="glass-filter-panel mb-5 shadow-sm border-0" style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-radius: 20px; padding: 25px; border: 1px solid #ffffff;">
        <form action="{{ route('admin.notices.index') }}" method="get" class="row g-3 align-items-end">
            
            @if(!isset(auth()->user()->branch_id))
                <div class="col-xxl-3 col-xl-3 col-md-6">
                    <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">{{ __('index.branch') }}</label>
                    <select class="form-select shadow-none modern-select" name="branch_id" id="branch_id" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
                        <option {{ !isset($filterParameters['branch_id']) || old('branch_id') ? 'selected': ''}} disabled>{{ __('index.select_branch') }}</option>
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

            <div class="col-xxl-3 col-xl-3 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">{{ __('index.from_date') }}</label>
                <div style="position: relative;">
                    <i data-feather="calendar" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                    <input type="date" name="publish_date_from" value="{{ request('publish_date_from') }}" class="form-control shadow-none" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; padding-left: 45px; font-size: 14px;">
                </div>
            </div>

            <div class="col-xxl-3 col-xl-3 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase;">{{ __('index.to_date') }}</label>
                <div style="position: relative;">
                    <i data-feather="calendar" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                    <input type="date" name="publish_date_to" value="{{ request('publish_date_to') }}" class="form-control shadow-none" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; padding-left: 45px; font-size: 14px;">
                </div>
            </div>

            <div class="col-xxl-3 col-xl-3 col-md-6">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn w-100" style="background: #057db0; color: white; height: 48px; border-radius: 12px; font-weight: 600; transition: all 0.3s ease;">
                        {{ __('index.filter') }}
                    </button>
                    
                    <a href="{{ route('admin.notices.index') }}" class="btn-theme-outline w-100 text-decoration-none d-flex align-items-center justify-content-center" 
                       style="height: 48px; border: 1px solid #e2e8f0; border-radius: 12px; color: #64748b; background: #fff; font-weight: 600;">
                        {{ __('index.reset') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Notice List (Keeping your existing card design as requested) --}}
    <div class="notice-list">
        @forelse ($notices as $key => $value)
            <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden transition-all hover-card">
                <div class="card-body p-0">
                    <div class="row g-0 align-items-center">
                        
                        <div class="col-auto p-4 text-center border-end bg-light" style="min-width: 120px;">
                            <span class="d-block small text-muted fw-bold mb-1">
                                #{{ (($notices->currentPage() - 1) * (\App\Models\Notice::RECORDS_PER_PAGE)) + (++$key) }}
                            </span>
                            <div class="date-display">
                                <h4 class="mb-0 fw-black" style="color: #057DB0;">{{ \Carbon\Carbon::parse($value->notice_publish_date)->format('d') }}</h4>
                                <span class="text-uppercase small fw-bold text-secondary">{{ \Carbon\Carbon::parse($value->notice_publish_date)->format('M Y') }}</span>
                            </div>
                        </div>

                        <div class="col p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">{{ ucfirst($value->title) }}</h5>
                                    <p class="text-muted small mb-0">
                                        <i data-feather="clock" class="icon-xs me-1"></i>
                                        @lang('index.publish_date'): {{ convertDateTimeFormat($value->notice_publish_date) ?? __('index.not_published_yet') }}
                                    </p>
                                </div>
                                
                                <div class="text-end">
                                    <span class="d-block small fw-bold text-muted mb-1 text-uppercase">@lang('index.status')</span>
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input toggleStatus cursor-pointer" type="checkbox" 
                                               href="{{ route('admin.notices.toggle-status', $value->id) }}"
                                               {{ $value->is_active ? 'checked' : '' }}
                                               style="border-color: #057DB0;">
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-3 opacity-10">

                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="small fw-bold text-muted"><i data-feather="users" class="icon-xs me-1"></i> @lang('index.notice_receiver'):</span>
                                @forelse ($value->noticeReceiversDetail as $receiver)
                                    <span class="badge border rounded-pill px-3 text-dark fw-normal bg-white shadow-xs">
                                        {{ $receiver->employee ? ucfirst($receiver->employee->name) : 'N/A' }}
                                    </span>
                                @empty
                                    <span class="text-muted small">@lang('index.no_records_found')</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="col-auto p-4 bg-light-subtle border-start">
                            <div class="d-flex flex-column gap-2">
                                <div class="btn-group shadow-sm bg-white rounded-3">
                                    @can('show_notice')
                                        <a href="javascript:void(0)" 
                                           class="btn btn-link text-info p-2 showNoticeDescription" 
                                           data-href="{{ route('admin.notices.show', $value->id) }}" 
                                           title="@lang('index.show_notice_content')">
                                            <i data-feather="eye" class="text-primary"></i>
                                        </a>
                                    @endcan
                                    @can('edit_notice')
                                        <a href="{{ route('admin.notices.edit', $value->id) }}" class="btn btn-link p-2" title="@lang('index.edit_notice')">
                                            <i data-feather="edit"></i>
                                        </a>
                                    @endcan
                                    @can('delete_notice')
                                        <button class="btn btn-link text-danger p-2 delete" 
                                                data-href="{{ route('admin.notices.delete', $value->id) }}" title="@lang('index.delete_notice_detail')">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    @endcan
                                </div>
                                
                                @can('send_notice')
                                    <button class="btn btn-sm text-white fw-bold rounded-3 sendNotice" 
                                            data-href="{{ route('admin.notices.send-notice', $value->id) }}"
                                            style="background-color: #057DB0;">
                                        <i data-feather="send" class="icon-xs me-1"></i> @lang('index.send_notice')
                                    </button>
                                @endcan
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4 text-center py-5 bg-white">
                <i data-feather="inbox" class="mx-auto mb-3" style="width: 50px; height: 50px; color: #057DB0; opacity: 0.3;"></i>
                <h5 class="text-muted fw-bold">@lang('index.no_records_found')</h5>
            </div>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $notices->appends($_GET)->links('pagination::bootstrap-5') }}
    </div>
</section>

@include('admin.notice.show')

@endsection

@section('scripts')
    @include('admin.notice.common.scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            feather.replace();

            // Toggle Status with SweetAlert
            $(document).on('change', '.toggleStatus', function (e) {
                e.preventDefault();
                let checkbox = $(this);
                let url = checkbox.attr('href');
                let isChecked = checkbox.is(':checked');
                checkbox.prop('checked', !isChecked); 

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to change the status of this notice?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#057DB0',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, change it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    } else {
                        checkbox.prop('checked', !isChecked);
                    }
                });
            });

            // Show Notice AJAX
            $(document).on('click', '.showNoticeDescription', function (e) {
                e.preventDefault();
                let url = $(this).data('href');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                        $('#addsliderLabel').text(response.data.title);
                        $('#description').html(response.data.description);
                        var myModal = new bootstrap.Modal(document.getElementById('addslider'));
                        myModal.show();
                    },
                    error: function (error) {
                        Swal.fire('Error', 'Data load nahi ho saka', 'error');
                    }
                });
            });

            // Delete Confirmation
            $(document).on('click', '.delete', function (e) {
                e.preventDefault();
                let url = $(this).data('href');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#057DB0',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });
    </script>
@endsection