@extends('layouts.master')

@section('title', __('index.event'))


@section('styles')
<style>
    .teamy-body-wrapper { padding: 1.5rem; background-color: #f9fafb; min-height: 100vh; }
    .teamy-top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .teamy-top-header h2 { font-weight: 700; color: #FFFFFF; font-size: 1.5rem; }
    .header-info-row { display: flex; align-items: center; gap: 10px; margin-top: 5px; }
    
    .teamy-main-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 2rem;
    }
    .section-title-wrapper { display: flex; align-items: center; gap: 12px; margin-bottom: 2rem; }
    .section-icon { width: 40px; height: 40px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; border-radius: 8px; }
    .section-divider { height: 1px; background: #f3f4f6; margin: 1.5rem 0; }
</style>
@endsection

@section('main-content')
<div class="teamy-body-wrapper">
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.event') }}</h2>
            <div class="header-info-row">
                <span class="status-badge"  style="background: #eef2ff; color: #6366f1;">{{ __('index.create') }}</span>
                <small style="color:#C1DFEC"><i class="fa fa-calendar-alt"></i> Event Management</small>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')
    @include('admin.event.common.breadcrumb')

    <div class="teamy-main-card">
        <div class="section-title-wrapper">
            <div class="section-icon"><i class="fa fa-edit"></i></div>
            <div>
                <h4 class="mb-0">Event Information</h4>
                <p class="text-muted small mb-0">Fill in the details to schedule your event.</p>
            </div>
        </div>

        <form id="notification" class="forms-sample" action="{{ route('admin.event.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            @include('admin.event.common.form')
        </form>
    </div>
</div>
@endsection

@section('scripts')
    @include('admin.event.common.scripts')
@endsection