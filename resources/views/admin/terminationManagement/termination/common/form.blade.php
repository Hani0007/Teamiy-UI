<div class="row">
    @if(!isset(auth()->user()->branch_id))
        <div class="col-lg-4 col-md-6 mb-4">
            <label for="branch_id" class="form-label">{{ __('index.branch') }} <span style="color: red">*</span></label>
            <select class="form-select" id="branch_id" name="branch_id">
                <option value="" {{ !isset($terminationDetail) ? 'selected' : '' }} disabled>
                    {{ __('index.select_branch') }}
                </option>
                @if(isset($companyDetail))
                    @foreach($companyDetail->branches()->get() as $branch)
                        <option value="{{ $branch->id }}"
                            {{ ($terminationDetail->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
                            {{ ucfirst($branch->name) }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    @endif

    @if(!auth('admin')->check() && auth()->check())
        <input type="hidden" disabled readonly id="branch_id" name="branch_id" value="{{ auth()->user()->branch_id }}">
    @endif

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="department_id" class="form-label">{{ __('index.department') }} <span style="color: red">*</span></label>
        <select class="form-select" id="department_id" name="department_id">
            @if(isset($terminationDetail))
                @foreach($filteredDepartment as $department)
                    <option value="{{ $department->id }}" {{ $department->id == $terminationDetail->department_id ? 'selected' : '' }}>
                        {{ ucfirst($department->dept_name) }}
                    </option>
                @endforeach
            @else
                <option selected disabled>{{ __('index.select_department') }}</option>
            @endif
        </select>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="employee_id" class="form-label">{{ __('index.employee') }} <span style="color: red">*</span></label>
        <select class="form-select" id="employee_id" name="employee_id">
            @if(isset($terminationDetail))
                @foreach($filteredUsers as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $terminationDetail->employee_id ? 'selected' : '' }}>
                        {{ ucfirst($user->name) }}
                    </option>
                @endforeach
            @else
                <option selected disabled>{{ __('index.select_employee') }}</option>
            @endif
        </select>
    </div>

    <div class="col-lg-4 col-md-6 event-date-time mb-4">
        <label for="notice_date" class="form-label">@lang('index.notice_date') <span style="color: red">*</span></label>
        @if($isBsEnabled)
            <input type="text" id="notice_date" name="notice_date" value="{{ ( isset( $terminationDetail) ? $terminationDetail->notice_date: old('notice_date') )}}" placeholder="yyyy-mm-dd" class="form-control nepaliDate"/>
        @else
            <input type="date" class="form-control" name="notice_date" required value="{{ ( isset( $terminationDetail) ? $terminationDetail->notice_date: old('notice_date') )}}" autocomplete="off">
        @endif
    </div>

    <div class="col-lg-4 col-md-6 event-date-time mb-4">
        <label for="termination_date" class="form-label">@lang('index.termination_date')</label>
        @if($isBsEnabled)
            <input type="text" id="termination_date" name="termination_date" value="{{ ( isset( $terminationDetail) ? $terminationDetail->termination_date: old('termination_date') )}}" placeholder="yyyy-mm-dd" class="form-control nepaliDate"/>
        @else
            <input type="date" class="form-control" name="termination_date" value="{{ ( isset( $terminationDetail) ? $terminationDetail->termination_date: old('termination_date') )}}" autocomplete="off">
        @endif
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="status" class="form-label">{{ __('index.status') }}</label>
        <select class="form-select" id="status" name="status" required>
            <option value="" {{isset($terminationDetail) ? '': 'selected'}} disabled>{{ __('index.select_status') }}</option>
            @foreach($terminationStatus as $status)
                <option value="{{$status->value}}" {{ isset($terminationDetail) && ($terminationDetail->status ) == $status->value || old('status') == $status->value ? 'selected': '' }}>
                    {{ucfirst($status->name)}}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-6 mb-4 col-md-6">
        <label for="document" class="form-label">{{ __('index.document') }}</label>
        <input class="form-control" type="file" id="document" name="document">

        @if(isset($terminationDetail->document))
            <div class="mt-2">
                @php $fileExtension = pathinfo($terminationDetail->document, PATHINFO_EXTENSION); @endphp
                @if(in_array($fileExtension, ['jpeg', 'jpg', 'png', 'webp']))
                    <img class="wd-150 ht-80 rounded shadow-sm border" style="object-fit: cover; cursor: pointer;"
                         src="{{ asset(\App\Models\Termination::UPLOAD_PATH . $terminationDetail->document) }}"
                         data-bs-toggle="modal" data-bs-target="#certModal">
                @elseif($fileExtension === 'pdf')
                    <a href="{{ asset(\App\Models\Termination::UPLOAD_PATH . $terminationDetail->document) }}" target="_blank" class="btn btn-xs btn-outline-info">View PDF</a>
                @endif
            </div>
        @endif
    </div>

    <div class="col-lg-6 mb-4 col-md-6">
        <label for="tinymceExample" class="form-label">{{ __('index.reason') }} <span style="color: red">*</span></label>
        <textarea class="form-control" name="reason" id="tinymceExample" rows="2">{{ ( isset($terminationDetail) ? $terminationDetail->reason: old('reason') )}}</textarea>
    </div>
</div>

@if(isset($terminationDetail->document))
<div class="modal fade" id="certModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Document Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img class="img-fluid" src="{{ asset(\App\Models\Termination::UPLOAD_PATH . $terminationDetail->document) }}">
            </div>
        </div>
    </div>
</div>
@endif