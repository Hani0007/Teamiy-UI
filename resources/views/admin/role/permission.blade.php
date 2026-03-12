@extends('layouts.master')

@section('title', __('index.permission_setting'))

@section('main-content')
<section class="content">
    @include('admin.section.flash_message')

    {{-- Blue Header (Teamy Top Header) --}}
    <div class="teamy-top-header">
        <div>
            <h2>@lang('index.permission_setting')</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">@lang('index.assign')</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-key"></i> Managing permissions for: <strong>{{ str_replace('_', ' ', ucwords($role->name)) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="teamy-main-card">
        {{-- Role Selection Tabs/Buttons --}}
        <div class="card-header bg-transparent border-0 pt-3">
            <div class="d-flex flex-wrap gap-2">
                @foreach($allRoles as $value)
                    <a href="{{ route('admin.roles.permission', $value->id) }}" class="text-decoration-none">
                        <button class="btn btn-{{ $value->id == $role->id ? 'primary' : 'outline-secondary' }} btn-sm rounded-pill px-4">
                            {{ str_replace('_', ' ', ucwords($value->name)) }}
                        </button>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="card-body">
            <form class="forms-sample" action="{{ route('admin.role.assign-permissions', $role->id) }}" method="POST">
                @method('PUT')
                @csrf
                
                {{-- Permission Groups include --}}
                @include('admin.role.common.permission')

                {{-- Footer Actions --}}
                <div class="branch-footer-actions mt-5">
                    <a href="{{ route('admin.roles.index') }}" class="branch-back-btn">
                        <i class="fa fa-arrow-left"></i> @lang('index.back')
                    </a>
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="fa fa-sync-alt me-1"></i> Update Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // Check All Logic
        $('.js-check-all').on('change', function() {
            let isChecked = $(this).prop('checked');
            $(this).closest('.group-checkbox').find('.module_checkbox').prop('checked', isChecked);
        });

        // Sync individual checkboxes with "Check All"
        $('.module_checkbox').on('change', function() {
            let group = $(this).closest('.group-checkbox');
            let total = group.find('.module_checkbox').length;
            let checked = group.find('.module_checkbox:checked').length;
            group.find('.js-check-all').prop('checked', total === checked);
        });
    });
</script>
@endsection