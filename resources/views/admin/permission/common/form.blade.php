<div class="row px-2">
    {{-- Permission Name --}}
    <div class="col-lg-6 col-md-6 mb-4">
        <label for="name" class="form-label fw-bold text-secondary"> Permission Name <span class="text-danger">*</span> </label>
        <input type="text" class="form-control border-2" id="name" required name="name" 
               value="{{ $permission->name ?? old('name') }}" 
               autocomplete="off" placeholder="e.g. view_users">
    </div>

    {{-- Permission Group --}}
    <div class="col-lg-6 col-md-6 mb-4">
        <label for="group" class="form-label fw-bold text-secondary">Permission Group</label>
        <select class="selectpicker form-control border-2" id="group" name="permission_group" data-size="5" data-live-search="true">
            <option disabled selected>Select Group</option>
            @forelse ($groups as $group)
                <option value="{{ $group }}" {{ (isset($permission) && $permission->group === $group) ? 'selected' : '' }}>
                    {{ $group }}
                </option>
            @empty
                {{-- No Groups Available --}}
            @endforelse 
        </select>
        <small class="text-muted"><i class="fa fa-info-circle me-1"></i> Categorize permissions for easier management.</small>
    </div>
</div>