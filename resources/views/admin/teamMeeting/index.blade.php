@extends('layouts.master')

@section('title', __('index.team_meeting'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    {{-- Blue Heading & Orange Premium Create Button --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0; font-weight: bold; margin-bottom: 0;">
                {{ __('index.team_meeting') }}
            </h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px; margin-top: 5px;">
                <i data-feather="users" style="width: 14px; vertical-align: middle;"></i> Organization Units
            </p>
        </div>
        
        @can('create_team_meeting')
            <a href="{{ route('admin.team-meetings.create') }}" style="text-decoration: none;">
                <button class="btn-premium-add" style="background: #FB8233; color: white; border: none; padding: 10px 24px; border-radius: 12px; display: flex; align-items: center; gap: 8px; font-weight: 600; box-shadow: 0 4px 15px rgba(251, 130, 51, 0.3);">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.create_team_meeting') }}</span>
                </button>
            </a>
        @endcan
    </div>

    {{-- Cards Grid --}}
    <div class="row g-4 justify-content-start">
        @forelse($teamMeetings as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card" style="background: white; border-radius: 20px; overflow: hidden; transition: 0.3s; box-shadow: 0 8px 25px rgba(0,0,0,0.05); height: 100%; border: 1px solid #f1f5f9;">
                    
                    {{-- Solid Blue Header --}}
                    <div class="card-glossy-header" style="background-color: #057db0; padding: 20px; color: white;">
                        <div class="branch-icon-square" style="width: 38px; height: 38px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                            <i data-feather="video" style="color: white; width: 20px;"></i>
                        </div>
                        
                        <h4 style="font-size: 16px; font-weight: 700; margin: 0; margin-bottom: 12px;">{{ ucfirst($value->title) }}</h4>

                        {{-- ID and Edit Icon in same Row (Opposite sides) --}}
                        <div class="d-flex justify-content-between align-items-center">
                            {{-- Updated ID Label --}}
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

                    {{-- Card Body --}}
                    <div class="card-white-body" style="padding: 20px;">
                        <div class="info-listing">
                            {{-- Meeting Date --}}
                            <div class="info-item-box d-flex align-items-center mb-3">
                                <div class="icon-circle" style="width: 32px; height: 32px; background: #fff5ef; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i data-feather="calendar" style="color: #FB8233; width: 14px;"></i>
                                </div>
                                <div class="text-content">
                                    <small style="display: block; font-size: 9px; color: #94a3b8; text-transform: uppercase; font-weight: 600;">MEETING DATE</small>
                                    <p style="margin: 0; font-size: 13px; font-weight: 600; color: #2d3748;">{{ \App\Helpers\AppHelper::formatDateForView($value->meeting_date) }}</p>
                                </div>
                            </div>

                            {{-- Start Time --}}
                            <div class="info-item-box d-flex align-items-center mb-3">
                                <div class="icon-circle" style="width: 32px; height: 32px; background: #fff5ef; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i data-feather="clock" style="color: #FB8233; width: 14px;"></i>
                                </div>
                                <div class="text-content">
                                    <small style="display: block; font-size: 9px; color: #94a3b8; text-transform: uppercase; font-weight: 600;">START TIME</small>
                                    <p style="margin: 0; font-size: 13px; font-weight: 600; color: #2d3748;">{{ \App\Helpers\AttendanceHelper::changeTimeFormatForAttendanceView($value->meeting_start_time) }}</p>
                                </div>
                            </div>

                            {{-- Participators --}}
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
                <p class="text-muted">No meeting records found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $teamMeetings->appends($_GET)->links() }}
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        feather.replace();
    });
</script>
<style>
    .branch-master-card:hover { transform: translateY(-5px); }
    .custom-scroll::-webkit-scrollbar { width: 3px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #FB8233; border-radius: 10px; }
</style>
@endsection