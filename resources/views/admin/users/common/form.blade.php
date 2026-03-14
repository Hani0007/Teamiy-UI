<div class="mb-3">
    <small class="text-muted"><i class="fa fa-info-circle"></i> {!! __('index.all_fields_required') !!}</small>
</div>

<div class="row">
    {{-- Left Side: Account Info --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-none bg-transparent">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label fw-bold"> {{ __('index.name') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control border-2" id="name" name="name" 
                           value="{{ ( isset($userDetail) ? $userDetail->name: old('name') )}}" 
                           placeholder="{{ __('index.enter_name') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label fw-bold">{{ __('index.email') }} <span class="text-danger">*</span></label>
                    <input type="email" class="form-control border-2" id="email" name="email"
                           value="{{ ( isset($userDetail) ? $userDetail->email: old('email') )}}" required
                           placeholder="{{ __('index.enter_email') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label fw-bold">{{ __('index.username') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control border-2" id="username" name="username"
                           value="{{ ( isset($userDetail) ? $userDetail->username: old('username') )}}"
                           required placeholder="{{ __('index.enter_username') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="role" class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                    <select class="form-select border-2" name="role_id" required>
                        <option selected disabled>Select Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ (isset($userDetail) && $userDetail->role_id == $role->id) ? 'selected' : '' }}>
                                {{ ucwords($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if(!isset($userDetail))
                <div class="col-md-12 mb-3">
                    <label for="password" class="form-label fw-bold">{{ __('index.password') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" class="form-control border-2" id="password" name="password"
                               autocomplete="new-password" placeholder="{{ __('index.enter_password') }}" required>
                        <button class="btn btn-outline-secondary border-2" type="button" id="togglePassword">
                            <i class="feather icon-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right Side: Profile Picture --}}
    <div class="col-lg-4 text-center">
        <div class="card bg-light border-0 p-4 rounded-3">
            <label class="form-label fw-bold mb-3 d-block"> <i data-feather="image" class="text-primary me-1" style="width: 18px; height: 18px;"></i>{{ __('index.upload_avatar') }}<span class="text-muted fw-normal" style="font-size: 0.8rem;">
     ( .jpg, .jpeg, .png )
</span></label>
            
            <div class="mb-3">
                @php
                    $path = isset($userDetail) && $userDetail->avatar ? asset(\App\Models\Admin::AVATAR_UPLOAD_PATH.$userDetail->avatar) : asset('assets/images/img.png');
                @endphp
                <img class="rounded-circle border shadow-sm p-1" id="image-preview" 
                     src="{{ $path }}" 
                     style="width: 120px; height: 120px; object-fit: cover;">
            </div>

            <input class="form-control form-control-sm" type="file" id="avatar" name="avatar" accept="image/*">
            <small class="text-muted mt-2 d-block">JPG, PNG or GIF (Max 2MB)</small>
        </div>
    </div>


</div>