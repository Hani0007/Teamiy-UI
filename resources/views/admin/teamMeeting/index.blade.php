@extends('layouts.master')

@section('title', __('index.team_meeting'))
@section('action',__('index.lists'))

@section('main-content')
<section class="content" style="padding: 10px 20px; background-color: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif;">
    @include('admin.section.flash_message')

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: 700; margin: 0;">{{ __('index.team_meeting') }}</h2>
            @include('admin.teamMeeting.common.breadcrumb')
        </div>

        @can('create_team_meeting')
            <a href="{{ route('admin.team-meetings.create') }}" style="text-decoration: none;">
                <button class="btn-premium-add shadow-sm" style="background: #FB8233; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 600; border: none; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.create_team_meeting') }}</span>
                </button>
            </a>
        @endcan
    </div>

    <div class="glass-filter-panel mb-5 shadow-sm border-0" style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-radius: 20px; padding: 25px; border: 1px solid #ffffff;">
        <form action="{{route('admin.team-meetings.index')}}" method="get" class="row g-3 align-items-end">
            
            @if(!isset(auth()->user()->branch_id))
                <div class="col-xxl-3 col-xl-4 col-md-6">
                    <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-uppercase">{{ __('index.branch') }}</label>
                    <select class="form-select shadow-none modern-select" name="branch_id" id="branch_id" style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
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

            <div class="col-xxl-3 col-xl-4 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase !important;">{{ __('index.department') }}</label>
                <select class="form-control shadow-none modern-select" id="department_id" multiple name="department_id[]" style="border-radius: 12px; border: 1px solid #e2e8f0;">
                </select>
            </div>

            <div class="col-xxl-3 col-xl-4 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase !important;">{{ __('index.participator') }}</label>
                <select class="form-select shadow-none modern-select" multiple name="participator[]" id="team_meeting" style="border-radius: 12px; border: 1px solid #e2e8f0;">
                </select>
            </div>

            <div class="col-xxl-2 col-xl-3 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase !important;">{{ __('index.from_date') }}</label>
                <input type="{{ \App\Helpers\AppHelper::ifDateInBsEnabled() ? 'text' : 'date' }}" 
                       name="meeting_from" 
                       value="{{$filterParameters['meeting_from']}}" 
                       class="form-control shadow-none {{ \App\Helpers\AppHelper::ifDateInBsEnabled() ? 'meetingDate' : 'fromDate' }}" 
                       style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
            </div>

            <div class="col-xxl-2 col-xl-3 col-md-6">
                <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px; text-transform: uppercase !important;">{{ __('index.to_date') }}</label>
                <input type="{{ \App\Helpers\AppHelper::ifDateInBsEnabled() ? 'text' : 'date' }}" 
                       name="meeting_to" 
                       value="{{$filterParameters['meeting_to']}}" 
                       class="form-control shadow-none {{ \App\Helpers\AppHelper::ifDateInBsEnabled() ? 'meetingDate' : 'toDate' }}" 
                       style="height: 48px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px;">
            </div>

            <div class="col-xxl-2 col-xl-3 col-md-12">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn w-100" style="background: #057db0; color: white; height: 48px; border-radius: 12px; font-weight: 600; transition: all 0.3s ease;">
                        {{ __('index.filter') }}
                    </button>
                    <a href="{{route('admin.team-meetings.index')}}" class="btn-theme-outline w-100 text-decoration-none d-flex align-items-center justify-content-center" 
                       style="height: 48px; border: 1px solid #e2e8f0; border-radius: 12px; color: #64748b; background: #fff; font-weight: 600;">
                        {{ __('index.reset') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="row g-4 justify-content-start">
        @forelse($teamMeetings as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card" style="background: white; border-radius: 20px; overflow: hidden; transition: 0.3s; box-shadow: 0 8px 25px rgba(0,0,0,0.05); height: 100%; border: 1px solid #f1f5f9;">
                    
                    <div class="card-glossy-header" style="background-color: #057db0; padding: 20px; color: white;">
                        <div class="branch-icon-square" style="width: 38px; height: 38px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                            <i data-feather="video" style="color: white; width: 20px;"></i>
                        </div>
                        
                        <h4 style="font-size: 16px; font-weight: 700; margin: 0; margin-bottom: 12px;">{{ ucfirst($value->title) }}</h4>

                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-size: 10px; background: rgba(255,255,255,0.15); padding: 4px 12px; border-radius: 15px; font-weight: 500;">
                                MEETING ID: #{{ $value->id }}
                            </span>

                            @can('edit_team_meeting')
                                <a href="{{ route('admin.team-meetings.edit', $value->id) }}" title="Edit Meeting" style="color: white; background: rgba(255,255,255,0.2); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: 0.3s;">
                                    <i data-feather="edit-3" style="width: 13px;"></i>
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="card-white-body" style="padding: 20px;">
                        <div class="info-listing">
                            <div class="info-item-box d-flex align-items-center mb-3">
                                <div class="icon-circle" style="width: 32px; height: 32px; background: #fff5ef; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i data-feather="calendar" style="color: #FB8233; width: 14px;"></i>
                                </div>
                                <div class="text-content">
                                    <small style="display: block; font-size: 9px; color: #94a3b8; text-transform: uppercase; font-weight: 600;">MEETING DATE</small>
                                    <p style="margin: 0; font-size: 13px; font-weight: 600; color: #2d3748;">{{ \App\Helpers\AppHelper::formatDateForView($value->meeting_date) }}</p>
                                </div>
                            </div>

                            <div class="info-item-box d-flex align-items-center mb-3">
                                <div class="icon-circle" style="width: 32px; height: 32px; background: #fff5ef; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i data-feather="clock" style="color: #FB8233; width: 14px;"></i>
                                </div>
                                <div class="text-content">
                                    <small style="display: block; font-size: 9px; color: #94a3b8; text-transform: uppercase; font-weight: 600;">START TIME</small>
                                    <p style="margin: 0; font-size: 13px; font-weight: 600; color: #2d3748;">{{ \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceView($value->meeting_start_time) }}</p>
                                </div>
                            </div>

                            <div class="info-item-box d-flex align-items-start">
                                <div class="icon-circle" style="width: 32px; height: 32px; background: #fff5ef; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i data-feather="user-check" style="color: #FB8233; width: 14px;"></i>
                                </div>
                                <div class="text-content w-100">
                                    <small style="display: block; font-size: 9px; color: #94a3b8; text-transform: uppercase; font-weight: 600;">PARTICIPATORS</small>
                                    <div style="max-height: 80px; overflow-y: auto; margin-top: 6px; padding-right: 5px;" class="custom-scroll">
                                        @foreach($value->teamMeetingParticipator as $datum)
                                            <span class="badge" style="background: #f1f5f9; color: #4a5568; font-size: 10px; margin-right: 4px; margin-bottom: 4px; border: 1px solid #e2e8f0; font-weight: 500; padding: 4px 8px; border-radius: 6px;">
                                                {{ $datum->participator ? ucfirst($datum->participator->name) : 'N/A' }}
                                            </span>
                                        @endforeach
                                    </div>
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
                    <p class="text-muted mt-3 fw-medium">No meeting records found.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $teamMeetings->appends($_GET)->links() }}
    </div>
</section>
@endsection

<!--@section('scripts')
<script>
    $(document).ready(function() {
        feather.replace();
    });
</script>-->

@section('scripts')
    @include('admin.teamMeeting.common.scripts')

@endsection