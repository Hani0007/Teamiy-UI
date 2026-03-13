@extends('layouts.master')
@section('title', __('index.notifications'))

@section('main-content')

<section class="content content-wrapper">
    @include('admin.section.flash_message')

    {{-- Breadcrumb & Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 style="color: #057db0; font-weight: 700; margin-bottom: 10px;">{{ __('index.notifications') }}</h2>
            @include('admin.notification.common.breadcrumb')
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="filter-card">
        <form action="{{route('admin.notifications.index')}}" method="get" class="row g-3 align-items-center">
            <div class="col-lg-4 col-md-6">
                <label class="form-label fw-bold small text-muted">@lang('index.type')</label>
                <select class="form-select shadow-none" name="type" id="type" style="height:45px; border-radius:10px;">
                    <option value="" {{!isset($filterParameters['type']) ? 'selected': ''}}>@lang('index.all_types')</option>
                    @foreach(\App\Models\Notification::TYPES as $value)
                        <option value="{{$value}}" {{ (isset($filterParameters['type']) && $value == $filterParameters['type'] ) ?'selected':'' }}>
                            {{ucfirst($value)}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-4 mt-lg-auto">
                <button type="submit" class="btn btn-primary w-100 fw-bold" style="height:45px; border-radius:10px; background:#057db0;">@lang('index.filter')</button>
            </div>
        </form>
    </div>

    {{-- Notification Cards Grid --}}
    <div class="notification-grid">
        @forelse($notifications as $key => $value)
            <div class="n-card">
                {{-- Status Toggle --}}
                <div class="status-switch">
                    <label class="switch">
                        <input class="toggleStatus" href="{{route('admin.notifications.toggle-status',$value->id)}}"
                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                        <span class="slider round"></span>
                    </label>
                </div>

                <div>
                    <span class="type-pill">{{ $value->type }}</span>
                    <h3 class="n-title">{{ removeSpecialChars($value->title) }}</h3>
                    <div class="n-date">
                        <i data-feather="calendar" style="width: 14px;"></i>
                        {{ convertDateTimeFormat($value->notification_publish_date) ?? 'Not published yet' }}
                    </div>
                </div>

                <div class="n-footer">
                    <div class="d-flex gap-2">
                        @can('notification')
                        <a href="javascript:void(0)" 
                           id="showNotificationDescription" 
                           data-href="{{route('admin.notifications.show',$value->id)}}"
                           data-id="{{ $value->id }}" 
                           class="btn-circle-lite" title="@lang('index.show_detail')">
                            <i data-feather="eye" style="width: 18px;"></i>
                        </a>
                        @endcan

                        @can('notification')
                            @if($value->type == 'general')
                            <a href="{{route('admin.notifications.edit',$value->id)}}" class="btn-circle-lite" title="@lang('index.edit')">
                                <i data-feather="edit-2" style="width: 18px;"></i>
                            </a>
                            @endif
                        @endcan
                    </div>

                    @can('notification')
                    <a href="javascript:void(0)" 
                       class="btn-circle-lite btn-delete deleteNotification"
                       data-href="{{route('admin.notifications.delete',$value->id)}}" 
                       title="@lang('index.delete')">
                        <i data-feather="trash-2" style="width: 18px;"></i>
                    </a>
                    @endcan
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="text-muted">
                    <i data-feather="bell-off" style="width: 48px; height: 48px; opacity: 0.2;"></i>
                    <p class="mt-3 fw-bold">@lang('index.no_records_found')</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{$notifications->appends($_GET)->links()}}
    </div>

</section>

@include('admin.notification.show')

@endsection

@section('scripts')
    @include('admin.notification.common.scripts')
    <script>
        $(document).ready(function() {
            if(typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection