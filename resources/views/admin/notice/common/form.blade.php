<div class="row">
    {{-- Branch & Title Row --}}
    @if(!isset(auth()->user()->branch_id))
    <div class="col-lg-6 mb-4">
        <label for="branch_id" class="form-label fw-bold small text-secondary">@lang('index.branch') <span class="text-danger">*</span></label>
        <select class="form-select border-2 shadow-none" id="branch_id" name="branch_id" required style="border-color: #e9ecef;">
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
        <label for="title" class="form-label fw-bold small text-secondary">@lang('index.notice_title') <span class="text-danger">*</span></label>
        <input type="text" class="form-control border-2 shadow-none" id="title" name="title" required value="{{ $noticeDetail->title ?? old('title') }}" placeholder="Enter notice heading..." style="border-color: #e9ecef;">
    </div>

    {{-- Left Side: Content --}}
    <div class="col-lg-8 mb-4">
        <label for="description" class="form-label fw-bold small text-secondary">@lang('index.notice_description') <span class="text-danger">*</span></label>
        <div class="border-1 rounded" style="border: 1px solid #e9ecef;">
            <textarea class="form-control" name="description" id="tinymceExample" rows="15">{!! $noticeDetail->description ?? old('description') !!}</textarea>
        </div>
    </div>

    {{-- Right Side: Settings Sidebar --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4" style="background: #f8f9fa;">
            <div class="card-body p-4">
                <div class="mb-4">
                    <label for="notice" class="form-label fw-bold small text-secondary">@lang('index.notice_receiver') <span class="text-danger">*</span></label>
                    <div class="select2-container-wrapper">
                        <select class="form-select select2-input shadow-none" id="notice" name="receiver[][notice_receiver_id]" multiple="multiple" required style="width: 100%;">
                            {{-- Options dynamically loaded via JS --}}
                        </select>
                    </div>
                    
                    {{-- Checkbox "All Employees" con color personalizado --}}
                    <div class="mt-3 d-flex align-items-center bg-white p-3 border rounded-3 shadow-sm border-0">
                        <div class="form-check mb-0">
                            <input class="form-check-input cursor-pointer custom-orange-check" type="checkbox" id="checkbox" style="width: 1.2em; height: 1.2em;">
                            <label class="form-check-label fw-bold ms-2 cursor-pointer" for="checkbox" style="font-size: 0.9rem; color: #FB8233;">
                                <i class="fa fa-users me-1"></i> @lang('index.all_employees')
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="my-4 opacity-10">

                <div class="mb-2">
                    <label for="is_active" class="form-label fw-bold small text-secondary">@lang('index.status') <span class="text-danger">*</span></label>
                    <select class="form-select border-2 shadow-none" id="is_active" name="is_active" required style="border-color: #e9ecef;">
                        <option value="1" {{ (isset($noticeDetail) && $noticeDetail->is_active == 1) ? 'selected' : '' }}>@lang('index.active')</option>
                        <option value="0" {{ (isset($noticeDetail) && $noticeDetail->is_active == 0) ? 'selected' : '' }}>@lang('index.inactive')</option>
                    </select>
                </div>
                
                <div class="d-flex align-items-start gap-2 mt-3 p-2 rounded-3" style="background: #fff3e0;">
                    <i class="fa fa-info-circle mt-1" style="color: #FB8233;"></i>
                    <p class="mb-0 small text-muted" style="line-height: 1.4;">
                        Only active notices are visible to employees on their dashboard.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilo para el checkbox naranja */
    .custom-orange-check:checked {
        background-color: #FB8233 !important;
        border-color: #FB8233 !important;
    }
    .custom-orange-check:focus {
        box-shadow: 0 0 0 0.25rem rgba(251, 130, 51, 0.25) !important;
        border-color: #FB8233 !important;
    }
    /* Mejora visual de inputs al enfocar */
    .form-control:focus, .form-select:focus {
        border-color: #057DB0 !important; /* Azul para campos de texto */
    }
</style>