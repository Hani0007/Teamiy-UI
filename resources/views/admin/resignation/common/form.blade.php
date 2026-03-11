<div class="row">
    @if(!isset(auth()->user()->branch_id))
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="branch_id" class="form-label">{{ __('index.branch') }} <span style="color: red">*</span></label>
        <select class="form-select" id="branch_id" name="branch_id">
            <option {{isset($resignationDetail) ? '' : 'selected'}} disabled>{{ __('index.select_branch') }}</option>
            @if(isset($companyDetail))
                @foreach($companyDetail->branches()->get() as $branch)
                    <option value="{{$branch->id}}"
                        {{ ((isset($resignationDetail) && ($resignationDetail->branch_id ) == $branch->id) || (isset(auth()->user()->branch_id) && auth()->user()->branch_id == $branch->id)) ? 'selected': '' }}>
                        {{ucfirst($branch->name)}}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    @endif

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="department_id" class="form-label">{{ __('index.department') }} <span style="color: red">*</span></label>
        <select class="form-select" id="department_id" name="department_id" onchange="loadEmployeesForDepartment()">
            @if(isset($resignationDetail))
                @foreach($filteredDepartment as $department)
                    <option value="{{ $department->id }}" {{ $department->id == $resignationDetail->department_id ? 'selected' : '' }}>
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
            @if(isset($resignationDetail))
                @foreach($filteredUsers as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $resignationDetail->employee_id ? 'selected' : '' }}>
                        {{ ucfirst($user->name) }}
                    </option>
                @endforeach
            @else
                <option selected disabled>{{ __('index.select_employee') }}</option>
            @endif
        </select>
    </div>

    <div class="col-lg-4 col-md-6 event-date-time mb-4">
        <label for="resignation_date" class="form-label">@lang('index.resignation_date') <span style="color: red">*</span></label>
        @if($isBsEnabled)
            <input type="text" id="resignation_date" name="resignation_date" value="{{ ( isset( $resignationDetail) ? $resignationDetail->resignation_date: old('resignation_date') )}}" placeholder="yyyy-mm-dd" class="form-control nepaliDate"/>
        @else
            <input type="date" class="form-control" name="resignation_date" required value="{{ ( isset( $resignationDetail) ? $resignationDetail->resignation_date: old('resignation_date') )}}" autocomplete="off">
        @endif
    </div>

    <div class="col-lg-4 col-md-6 event-date-time mb-4">
        <label for="last_working_day" class="form-label">@lang('index.last_working_day')</label>
        @if($isBsEnabled)
            <input type="text" id="last_working_day" name="last_working_day" value="{{ ( isset( $resignationDetail) ? $resignationDetail->last_working_day: old('last_working_day') )}}" placeholder="yyyy-mm-dd" class="form-control nepaliDate"/>
        @else
            <input type="date" class="form-control" name="last_working_day" value="{{ ( isset( $resignationDetail) ? $resignationDetail->last_working_day: old('last_working_day') )}}" autocomplete="off">
        @endif
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="status" class="form-label">{{ __('index.status') }}</label>
        <select class="form-select" id="status" name="status" required>
            <option value="" {{isset($resignationDetail) ? '': 'selected'}} disabled>{{ __('index.select_status') }}</option>
            @foreach($resignationStatus as $status)
                <option value="{{$status->value}}" {{ isset($resignationDetail) && ($resignationDetail->status ) == $status->value || old('status') == $status->value ? 'selected': '' }}>
                    {{ ucfirst($status->value) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-6 mb-4">
        <label for="document" class="form-label">{{ __('index.document') }}</label>
        <input class="form-control" type="file" id="document" name="document">
        
        @if(isset($resignationDetail->document))
            <div class="mt-2">
                @php $fileExtension = pathinfo($resignationDetail->document, PATHINFO_EXTENSION); @endphp
                @if(in_array($fileExtension, ['jpeg', 'jpg', 'png', 'webp']))
                    <img class="wd-150 ht-80 rounded shadow-sm border" style="object-fit: cover; cursor: pointer;"
                         src="{{ asset(\App\Models\Resignation::UPLOAD_PATH . $resignationDetail->document) }}"
                         data-bs-toggle="modal" data-bs-target="#resModal">
                @elseif($fileExtension === 'pdf')
                    <a href="{{ asset(\App\Models\Resignation::UPLOAD_PATH . $resignationDetail->document) }}" target="_blank" class="btn btn-xs btn-outline-info">View PDF</a>
                @endif
            </div>
        @endif
    </div>

    <div class="col-lg-6 mb-4">
        <label for="tinymceExample" class="form-label">{{ __('index.reason') }} <span style="color: red">*</span></label>
        <textarea class="form-control" name="reason" id="tinymceExample" rows="2">{{ ( isset($resignationDetail) ? $resignationDetail->reason: old('reason') )}}</textarea>
    </div>

    @if(isset($resignationDetail))
    <div class="col-lg-12 mb-4">
        <label for="admin_remark" class="form-label">{{ __('index.admin_remark') }} <span style="color: red">*</span></label>
        <textarea class="form-control" name="admin_remark" id="admin_remark" rows="3">{{ $resignationDetail->admin_remark ?? old('admin_remark') }}</textarea>
    </div>
    @endif
</div>

{{-- Document Preview Modal --}}
@if(isset($resignationDetail->document))
<div class="modal fade" id="resModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Document Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img class="img-fluid" src="{{ asset(\App\Models\Resignation::UPLOAD_PATH . $resignationDetail->document) }}">
            </div>
        </div>
    </div>
</div>
@endif