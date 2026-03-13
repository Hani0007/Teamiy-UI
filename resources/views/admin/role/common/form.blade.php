<div class="row px-2">
    <div class="col-lg-6 col-md-6 mb-4">
        <label for="name" class="form-label fw-bold"> @lang('index.role_name') <span class="text-danger">*</span> </label>
        <input type="text" class="form-control border-2" id="name" required name="name" 
               value="{{ ( isset($roleDetail) ? $roleDetail->name: old('name') )}}" autocomplete="off">
    </div>

    <div class="col-lg-6 col-md-6 mb-4">
        <label for="role_for" class="form-label fw-bold"> Role For <span class="text-danger">*</span> </label>
        <select name="role_for" class="form-select border-2" id="role_for" required>
            <option selected disabled>Select</option>
            <option value="admin" {{ ((isset($roleDetail) && $roleDetail->role_for == 'admin') ? 'selected' : '' )}}>Admin</option>
            <option value="employee" {{ ((isset($roleDetail) && $roleDetail->role_for == 'employee') ? 'selected' : '' )}}>Employee</option>
        </select>
    </div>

    {{-- Permissions Section --}}
    <!-- <div class="col-12 mt-3">
        <h5 class="mb-3 text-primary"><i class="fa fa-list-check me-2"></i> Assign Permissions</h5>
        <div class="row">
            @if(isset($permissions))
                @foreach($permissions as $permission)
                    <div class="col-lg-3 col-md-4 mb-3">
                        <div class="form-check form-switch p-2 border rounded shadow-sm bg-white">
                            <input class="form-check-input ms-0 me-2" type="checkbox" name="permissions[]" 
                                   value="{{ $permission->id }}" id="p_{{ $permission->id }}"
                                   {{ (isset($roleDetail) && $roleDetail->permissions->contains($permission->id)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="p_{{ $permission->id }}">
                                {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                            </label>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div> -->
</div>