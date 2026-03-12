<div class="tab-content mt-2" id="myTabContent">
    @foreach($permissions as $group => $permissionList)
    
        @php
            $role_permission = $role_permission ?? [];
            $allChecked = collect($permissionList)->every(function($per) use ($role_permission) {
                return in_array($per->name, $role_permission);
            });
        @endphp

        <div class="group-checkbox mb-4 border rounded-3 bg-white shadow-sm overflow-hidden">
            {{-- Module Header --}}
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom bg-light">
                <h5 class="mb-0 text-dark fw-bold">
                    <i class="fa fa-cube text-primary me-2"></i>
                    {{ $group }} @lang('index.module')
                </h5>
                
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input js-check-all" type="checkbox" id="checkAll_{{ Str::slug($group) }}" {{ $allChecked ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold text-primary" for="checkAll_{{ Str::slug($group) }}">
                        @lang('index.check_all')
                    </label>
                </div>
            </div>

            {{-- Permissions List --}}
            <div class="p-4">
                <div class="row g-3">
                    @foreach($permissionList as $per)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="permission-item-box p-2 border rounded-2 d-flex align-items-center hover-shadow transition">
                                <div class="form-check mb-0">
                                    <input class="form-check-input module_checkbox" type="checkbox"
                                           {{ in_array($per->name, $role_permission) ? 'checked' : '' }}
                                           name="permission_value[]" value="{{ $per->name }}"
                                           id="perm_{{ $per->id }}">
                                    <label class="form-check-label text-secondary ms-2 cursor-pointer" for="perm_{{ $per->id }}">
                                        {{ ucwords(str_replace('_', ' ', $per->name)) }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>