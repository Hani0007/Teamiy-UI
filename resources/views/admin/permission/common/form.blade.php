

<div class="row">

    <div class="col-lg-6 col-md-6 mb-4">
        <label for="name" class="form-label"> Permission Name <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="name" required name="name" value="{{ ( isset($permission) ? $permission->name: '' )}}" autocomplete="off" placeholder="">
    </div>

    <div class="col-lg-6 col-md-6 mb-4">
        <label for="group" class="form-label">Permission Group</label>
        <select class="selectpicker form-control" id="group" name="permission_group" data-size="5" data-live-search="true">
            <option disabled selected >Select Group</option>
            @forelse ($groups as $group)
                <option value="{{ $group }}" {{ (isset($permission) && $permission->group === $group) ? 'selected' : '' }}>{{ $group }}</option>
            @empty
                
            @endforelse
        </select>
    </div>

    {{-- <div class="col-lg-6 col-md-6 mb-4">
        <label for="exampleFormControlSelect1" class="form-label">@lang('index.authorize_backend_login')</label>
        <select class="form-select" id="exampleFormControlSelect1" name="backend_login_authorize">
            <option value="" {{isset($roleDetail) ? '':'selected'}} >@lang('index.select_status')</option>
            <option value="1" {{ isset($roleDetail) && ($roleDetail->backend_login_authorize ) == 1 ? 'selected': old('backend_login_authorize') }}>@lang('index.yes')</option>
            <option value="0" {{ isset($roleDetail) && ($roleDetail->backend_login_authorize ) == 0 ? 'selected': old('backend_login_authorize') }}>@lang('index.no')</option>
        </select>
    </div>


    <div class="col-lg-6 col-md-6 mb-4">
        <label for="exampleFormControlSelect1" class="form-label">@lang('index.status')</label>
        <select class="form-select" id="exampleFormControlSelect1" name="is_active">
            <option value=""  disabled>@lang('index.select-status')</option>
            <option value="1" {{ isset($roleDetail) && ($roleDetail->is_active ) == 1 ? 'selected': old('is_active') }}>@lang('index.active')</option>
            <option value="0" {{ isset($roleDetail) && ($roleDetail->is_active ) == 0 ? 'selected': old('is_active') }}>@lang('index.inactive')</option>
        </select>
    </div> --}}



    <div class="col-lg-6 col-md-6 text-start mb-4 mt-md-4">
        <!-- <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="plus"></i> {{isset($permission)? __('index.update'): __('index.create') }} Permission</button> -->
        <button type="submit" class="btn btn-primary"> {{isset($permission)? __('index.update'): __('index.create') }} Permission</button>
    </div>
</div>
