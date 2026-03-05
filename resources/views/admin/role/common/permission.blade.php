
{{-- <ul class="nav nav-tabs" id="myTab" role="tablist">
    @foreach($permissions as $key => $permissionGroupType)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $key == 0 ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#tab-{{ $permissionGroupType->slug }}" type="button" role="tab" aria-controls="tab-{{ $permissionGroupType->slug }}" aria-selected="{{ $key == 0 ? 'true' : 'false' }}" id="{{$permissionGroupType->slug}}">
                {{$permissionGroupType->name}} @lang('index.permissions')
            </button>
        </li>
    @endforeach
</ul> --}}

<div class="tab-content mt-4 px-4" id="myTabContent">
    @foreach($permissions as $group => $permission)
    
        @php
            $allChecked = collect($permission)->every(function($per) use ($role_permission) {
                return in_array($per->name, $role_permission ?? []);
            });
        @endphp

        <div class="row mb-2 {{ $group }}">
            <div class="col-lg-12">
                <div class="group-checkbox border-bottom pb-3 mb-4 w-100">
                    <div class="title-ch mb-2 permission-group" data-group="{{ \Illuminate\Support\Str::slug($group) }}">
                        <h5 style="color:#e82e5f;">{{$group}} @lang('index.module'):</h5>
                    </div>
                    <div class="head-checkbox d-flex align-items-center gap-3 flex-wrap">

                        <div class="checkAll">
                            <label class="label-ch lh-1">
                                <input class="js-check-all check-all" type="checkbox" name="" {{ $allChecked ? 'checked' : '' }}>
                                <span class="text fw-bold">@lang('index.check_all')</span>
                            </label>
                        </div>
                        <ul class="js-check-all-target list-ch d-flex align-items-center justify-content-start gap-3 p-0 flex-wrap" data-check-all="website">
                            @foreach($permission as $keys => $per)
                            
                                <li class="item">
                                    <label class="label lh-1">
                                        <input class="module_checkbox permission-item" type="checkbox"
                                            {{ in_array($per->name, $role_permission ?? []) ? 'checked' : '' }}
                                            name="permission_value[]" value="{{$per->name}}">
                                        <span class="text">{{ ucwords(str_replace('_', ' ', $per->name)) }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="text-start">
    <button type="submit" class="btn btn-success btn-md">
        Update
        {{-- {{$isEdit ? __('index.update'): __('index.save') }} --}}
    </button>
</div>

