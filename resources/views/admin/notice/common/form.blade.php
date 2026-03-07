<div class="row">
    {{-- Branch & Title Row --}}
    @if(!isset(auth()->user()->branch_id))
    <div class="col-lg-6 mb-4">
        <label for="branch_id" class="form-label fw-bold text-secondary">@lang('index.branch') <span class="text-danger">*</span></label>
        <select class="form-select border-2" id="branch_id" name="branch_id" required>
            <option value="" {{!isset($noticeDetail) ? 'selected': ''}} disabled>{{ __('index.select_branch') }}</option>
            @if(isset($companyDetail))
                @foreach($companyDetail->branches()->get() as $branch)
                    <option value="{{$branch->id}}" {{ (isset($noticeDetail) && $noticeDetail->branch_id == $branch->id) ? 'selected': '' }}>
                        {{ucfirst($branch->name)}}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    @endif

    <div class="{{ !isset(auth()->user()->branch_id) ? 'col-lg-6' : 'col-lg-12' }} mb-4">
        <label for="title" class="form-label fw-bold text-secondary">@lang('index.notice_title') <span class="text-danger">*</span></label>
        <input type="text" class="form-control border-2" id="title" name="title" required value="{{ $noticeDetail->title ?? old('title') }}" placeholder="Enter notice heading...">
    </div>

    {{-- Left Side: Content --}}
    <div class="col-lg-8 mb-4">
        <label for="description" class="form-label fw-bold text-secondary">@lang('index.notice_description') <span class="text-danger">*</span></label>
        <textarea class="form-control" name="description" id="tinymceExample" rows="15">{!! $noticeDetail->description ?? old('description') !!}</textarea>
    </div>

    {{-- Right Side: Settings Sidebar --}}
    <div class="col-lg-4">
        <div class="card shadow-none border-0 bg-light rounded-3">
            <div class="card-body p-3">
                <div class="mb-4">
                    <label for="notice" class="form-label fw-bold text-secondary">@lang('index.notice_receiver') <span class="text-danger">*</span></label>
                    <div class="select2-container-wrapper">
                        <select class="form-select select2-input" id="notice" name="receiver[][notice_receiver_id]" multiple="multiple" required style="width: 100%;">
                            {{-- Options dynamically loaded via JS --}}
                        </select>
                    </div>
                    
                    <div class="mt-3 d-flex align-items-center bg-white p-2 border rounded-2 shadow-sm">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="checkbox" style="cursor: pointer;">
                            <label class="form-check-label text-primary fw-bold ms-1" for="checkbox" style="cursor: pointer; font-size: 0.85rem;">
                                <i class="fa fa-users me-1"></i> @lang('index.all_employees')
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="text-muted">

                <div class="mb-2">
                    <label for="is_active" class="form-label fw-bold text-secondary">@lang('index.status') <span class="text-danger">*</span></label>
                    <select class="form-select border-2" id="is_active" name="is_active" required>
                        <option value="1" {{ (isset($noticeDetail) && $noticeDetail->is_active == 1) ? 'selected' : '' }}>@lang('index.active')</option>
                        <option value="0" {{ (isset($noticeDetail) && $noticeDetail->is_active == 0) ? 'selected' : '' }}>@lang('index.inactive')</option>
                    </select>
                </div>
                
                <p class="text-muted small mt-2">
                    <i class="fa fa-info-circle me-1"></i> Only active notices are visible to employees on their dashboard.
                </p>
            </div>
        </div>
    </div>
</div>