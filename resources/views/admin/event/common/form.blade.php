<style>
    .img-wrap { position: relative; display: inline-block; border: 2px dashed #d1d5db; padding: 5px; border-radius: 8px; }
    .img-wrap .close {
        position: absolute; top: -10px; right: -10px; background: #ef4444; color: #fff;
        width: 25px; height: 25px; line-height: 22px; text-align: center;
        border-radius: 50%; font-size: 18px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .form-label { font-weight: 600; color: #374151; font-size: 0.875rem; }
    .required-star { color: #ef4444; }
    .event-footer-actions { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f3f4f6; }
</style>

<div class="row">
    {{-- Branch Selection --}}
    @if(!isset(auth()->user()->branch_id))
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="branch_id" class="form-label">{{ __('index.branch') }} <span class="required-star">*</span></label>
        <select class="form-select" id="branch_id" name="branch_id">
            <option {{!isset($eventDetail) || old('branch_id') ? 'selected': ''}} disabled>{{ __('index.select_branch') }}</option>
            @if(isset($companyDetail))
                @foreach($companyDetail->branches()->get() as $branch)
                    <option value="{{$branch->id}}" {{ ((isset($eventDetail) && ($eventDetail->branch_id ) == $branch->id) || (isset(auth()->user()->branch_id) && auth()->user()->branch_id == $branch->id)) ? 'selected': '' }}>
                        {{ucfirst($branch->name)}}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    @endif

    {{-- Basic Info --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="title" class="form-label">{{ __('index.event_title') }} <span class="required-star">*</span></label>
        <input type="text" class="form-control" id="title" name="title" required value="{{ isset($eventDetail) ? $eventDetail->title : old('title') }}" placeholder="{{ __('index.enter_event_title') }}">
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="host" class="form-label">{{ __('index.event_host') }} <span class="required-star">*</span></label>
        <input type="text" class="form-control" id="host" name="host" required value="{{ isset($eventDetail) ? $eventDetail->host : old('host') }}" placeholder="{{ __('index.event_host') }}">
    </div>

    <div class="col-lg-12 col-md-12 mb-4">
        <label for="location" class="form-label">{{ __('index.event_location') }} <span class="required-star">*</span></label>
        <input type="text" class="form-control" id="location" name="location" required value="{{ isset($eventDetail) ? $eventDetail->location : old('location') }}" placeholder="{{ __('index.event_location') }}">
    </div>

    <div class="section-divider"></div>

    {{-- Dates and Times --}}
    <div class="col-lg-6">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">@lang('index.event_start_date') <span class="required-star">*</span></label>
                <input type="{{$isBsEnabled ? 'text' : 'date'}}" class="form-control {{$isBsEnabled ? 'nepali_date' : ''}}" name="start_date" required value="{{ ( isset( $eventDetail) ? ($isBsEnabled ? \App\Helpers\AppHelper::taskDate($eventDetail->start_date) : $eventDetail->start_date) : old('start_date') )}}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">@lang('index.event_end_date')</label>
                <input type="{{$isBsEnabled ? 'text' : 'date'}}" class="form-control {{$isBsEnabled ? 'nepali_date' : ''}}" name="end_date" value="{{ ( isset( $eventDetail->end_date) ? ($isBsEnabled ? \App\Helpers\AppHelper::taskDate($eventDetail->end_date) : $eventDetail->end_date) : old('end_date') )}}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">@lang('index.event_start_time') <span class="required-star">*</span></label>
                <input type="time" class="form-control" name="start_time" required value="{{ isset($eventDetail) ? $eventDetail->start_time : old('start_time') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">@lang('index.event_end_time') <span class="required-star">*</span></label>
                <input type="time" class="form-control" name="end_time" required value="{{ isset($eventDetail) ? $eventDetail->end_time : old('end_time') }}">
            </div>
        </div>
    </div>

    {{-- Description --}}
    <div class="col-lg-6 mb-3">
        <label for="description" class="form-label">{{ __('index.description') }} <span class="required-star">*</span></label>
        <textarea class="form-control" minlength="10" name="description" id="description" rows="5" placeholder="Describe the event contents...">{!! isset($eventDetail) ? $eventDetail->description : old('description') !!}</textarea>
    </div>

    <div class="section-divider"></div>

    {{-- Attachment & Color --}}
    <div class="col-lg-6 mb-4">
        <label for="image" class="form-label">{{ __('index.upload_attachment') }}</label>
        <input class="form-control" type="file" accept="image/*" id="image" name="attachment" />
        @if(isset($eventDetail) && $eventDetail->attachment)
            <div class="img-wrap mt-3">
                <span class="close removeImage" data-href="{{route('admin.event.remove-image',$eventDetail->id)}}">&times;</span>
                <img src="{{asset(\App\Models\Event::UPLOAD_PATH.$eventDetail->attachment)}}" alt="Attachment" style="max-width: 100%; height: 150px; object-fit: cover; border-radius: 4px;">
            </div>
        @endif
    </div>

    <div class="col-lg-6 mb-4">
        <label class="form-label">{{ __('index.background_color') }}</label>
        <div class="d-flex align-items-center gap-3">
            <input type="color" class="form-control form-control-color" name="background_color" value="{{ isset($eventDetail) ? $eventDetail->background_color : (old('background_color') ?? '#4e73df') }}" style="width: 100px;">
            <small class="text-muted">Choose how this appears in calendars</small>
        </div>
    </div>

    {{-- Multi-selects --}}
    <div class="col-lg-6 mb-3">
        <label class="form-label">@lang('index.departments') <span class="required-star">*</span></label>
        <select class="form-select select2" id="department_id" name="department_id[]" multiple="multiple" required>
            <option value="select_all">{{ __('index.select_all') }}</option>
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label class="form-label">@lang('index.employee') <span class="required-star">*</span></label>
        <select class="form-select select2" id="employee_id" name="employee_id[]" multiple="multiple" required>
            <option value="select_all">{{ __('index.select_all') }}</option>
        </select>
    </div>

    {{-- Action Buttons --}}
    <input type="hidden" readonly id="eventNotification" name="notification" value="0">
        <div class="col-12 event-footer-actions">
            <div class="d-flex justify-content-end align-items-center gap-2">
                
        
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.event.index') }}" class="btn branch-back-btn px-4">
                        <i class="fa fa-arrow-left me-1"></i> {{ __('index.back') }}
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        {{ isset($eventDetail) ? __('index.update') : __('index.create') }}
                    </button>
                    
                    <button type="submit" id="withEventNotification" class="btn btn-primary px-4">
                        
                        {{ isset($eventDetail) ? __('index.update_send') : __('index.create_send') }}
                    </button>
                </div>
            </div>
        </div>
</div>