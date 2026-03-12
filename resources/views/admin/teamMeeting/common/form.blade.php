@php use App\Helpers\AppHelper; @endphp
@php use App\Models\TeamMeeting; @endphp

<div class="row">
    {{-- Branch Selection --}}
    @if(!isset(auth()->user()->branch_id))
        <div class="col-lg-4 col-md-6 mb-4">
            <label for="branch_id" class="form-label">{{ __('index.branch') }} <span style="color: red">*</span></label>
            <select class="form-select select2-input" id="branch_id" name="branch_id">
                <option selected disabled>{{ __('index.select_branch') }}</option>
                @if(isset($companyDetail))
                    @foreach($companyDetail->branches()->get() as $branch)
                        <option value="{{$branch->id}}"
                            {{ (isset($teamMeetingDetail) && ($teamMeetingDetail->branch_id ) == $branch->id) ? 'selected': '' }}>
                            {{ucfirst($branch->name)}}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    @endif

    {{-- Department Selection --}}
    <div class="col-lg-4 col-md-6 mb-4 internalTrainer">
        <label for="department_id" class="form-label">{{ __('index.department') }} <span style="color: red">*</span></label>
        <select class="form-select select2-input" id="department_id" multiple name="department[][department_id]">
            @if(isset($trainingDetail))
                @foreach($filteredDepartment as $department)
                    <option value="{{ $department->id }}" {{ in_array($department->id, $departmentIds) ? 'selected' : '' }}>
                        {{ ucfirst($department->dept_name) }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>

    {{-- Meeting Title --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="title" class="form-label">{{ __('index.meeting_title') }} <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="title" name="title" required
               value="{{ (isset($teamMeetingDetail) ? $teamMeetingDetail->title : old('title')) }}"
               autocomplete="off" placeholder="{{ __('index.enter_content_title') }}">
    </div>

    {{-- Venue --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="venue" class="form-label">{{ __('index.meeting_venue') }} <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="venue" name="venue" required
               value="{{ (isset($teamMeetingDetail) ? $teamMeetingDetail->venue : old('venue')) }}"
               autocomplete="off" placeholder="{{ __('index.enter_venue_name') }}">
    </div>

    {{-- Meeting Date --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="meeting_date" class="form-label">{{ __('index.meeting_date') }} <span style="color: red">*</span></label>
        @if(AppHelper::ifDateInBsEnabled())
            <input class="form-control meetingDate" name="meeting_date" id="meetingDate"
                   value="{{(isset($teamMeetingDetail) ? $teamMeetingDetail->meeting_date: old('meeting_date'))}}"
                   required autocomplete="off" type="text" placeholder="mm/dd/yyyy" />
        @else
            <input class="form-control" name="meeting_date" type="date"
                   value="{{(isset($teamMeetingDetail) ? $teamMeetingDetail->meeting_date: old('meeting_date'))}}"
                   required />
        @endif
    </div>

    {{-- Start Time --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="meeting_start_time" class="form-label">{{ __('index.meeting_start_time') }} <span style="color: red">*</span></label>
        <input type="time" class="form-control" id="meeting_start_time" name="meeting_start_time" required
               value="{{ (isset($teamMeetingDetail) ? $teamMeetingDetail->meeting_start_time : old('meeting_start_time')) }}">
    </div>

    {{-- Participators (Full Width) --}}
    <div class="col-lg-12 mb-4">
        <label for="team_meeting" class="form-label">{{ __('index.meeting_participator') }} <span style="color: red">*</span></label>
        <select class="form-select select2-input" id="team_meeting" name="participator[][meeting_participator_id]"
                multiple="multiple" required>
            {{-- Loaded via script based on department/branch --}}
        </select>
    </div>

    {{-- Description --}}
    <div class="col-lg-7 mb-4">
        <label for="description" class="form-label">{{ __('index.meeting_description') }}</label>
        <textarea class="form-control" name="description" id="tinymceExample" 
                  rows="6">{!! (isset($teamMeetingDetail) ? $teamMeetingDetail->description : old('description')) !!}</textarea>
    </div>

    {{-- Image Upload & Preview --}}
    <div class="col-lg-5 mb-4">
        <label for="image" class="form-label">{{ __('index.upload_image') }}</label>
        <div class="image-upload-container border rounded p-3 bg-light text-center">
            <input class="form-control mb-3" type="file" id="image" name="image" accept="image/*" />
            
            @if(isset($teamMeetingDetail) && $teamMeetingDetail->image)
                <div class="img-preview-wrapper position-relative d-inline-block">
                    <span class="remove-img-btn removeImage" 
                          data-href="{{ route('admin.team-meetings.remove-image', $teamMeetingDetail->id) }}"
                          style="position: absolute; top: -10px; right: -10px; background: #FB8233; color: white; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; line-height: 22px;">
                        &times;
                    </span>
                    <img src="{{ asset(TeamMeeting::UPLOAD_PATH.$teamMeetingDetail->image) }}"
                         class="rounded shadow-sm" style="max-height: 150px; object-fit: contain;">
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Hidden notification trigger --}}
<input type="hidden" readonly id="teamNotification" name="notification" value="0">