
<div class="mb-2"><small>{!! __('index.all_fields_required') !!}</small></div>
<style>
    .is-invalid {
        border-color: red !important;
    }

    .is-invalid + .error-message {
        display: block;
        color: red !important;
    }

    .error-message {
        display: none;
        color: red !important;
    }
</style>
<div class="card mb-4">
    <div class="card-body pb-3">
        <div class="profile-detail">
            <div class="row">

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="name" class="form-label"> {{ __('index.name') }} <span style="color: red">*</span></label>
                    <input type="text" class="form-control"
                           id="name"
                           name="name"
                           value="{{ ( isset($userDetail) ? $userDetail->name: old('name') )}}" autocomplete="off"
                           placeholder="{{ __('index.enter_name') }}" required>
                </div>


                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="email" class="form-label">{{ __('index.email') }} <span style="color: red">*</span></label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="{{ ( isset($userDetail) ? $userDetail->email: old('email') )}}" required
                           autocomplete="off" placeholder="{{ __('index.enter_email') }}">
                </div>

                <div class="col-lg-4 mb-3">
                    <label for="avatar" class="form-label">{{ __('index.upload_avatar') }} </label>
                    <input class="form-control"
                           type="file"
                           id="avatar"
                           name="avatar"
                           accept="image/*"
                           value="{{ isset($userDetail) ? $userDetail->avatar: old('avatar') }}">

                    @if(isset($userDetail))
                    @if($userDetail->avatar && file_exists(public_path(\App\Models\Admin::AVATAR_UPLOAD_PATH.$userDetail->avatar)))
                    <img class="mt-2 rounded" id="image-preview"
                         src="{{ asset(\App\Models\Admin::AVATAR_UPLOAD_PATH.$userDetail->avatar) }}"
                         style="object-fit: contain"
                         width="100"
                         height="100"
                         alt="profile">
                    @else
                    <img class="mt-2 rounded" id="image-preview"
                         src="{{ asset('assets/images/img.png') }}"
                         style="object-fit: contain"
                         width="100"
                         height="100"
                         alt="profile">
                    @endif
@endif
                </div>
                <div class="col-lg-4 mb-3">
                    <label for="username" class="form-label">{{ __('index.username') }} <span style="color: red">*</span></label>
                    <input type="text" class="form-control" id="username" name="username"
                        value="{{ ( isset($userDetail) ? $userDetail->username: old('username') )}}"
                        required autocomplete="off" placeholder="{{ __('index.enter_username') }}">
                </div>
                @if(!isset($userDetail))
                    <div class="col-lg-4 mb-3">
                        <label for="password" class="form-label">{{ __('index.password') }} <span style="color: red">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password"
                            value="{{old('password')}}" autocomplete="off" placeholder="{{ __('index.enter_password') }}" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword" 
                            style="padding: 8px 12px; border: none; background: #f8f9fa; border-radius: 0 4px 4px 0; transition: all 0.2s;"
                            onmouseover="this.style.backgroundColor='#e9ecef'; this.querySelector('i').style.color='#6c757d'" 
                            onmouseout="this.style.backgroundColor='#f8f9fa'; this.querySelector('i').style.color='#000'">
                                <i class="feather" data-feather="eye-off" style="width: 18px; height: 18px;"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="col-lg-4 mb-3">
                    <label for="role" class="form-label">Role <span style="color: red">*</span></label>
                    <select class="form-control" name="role_id" required>
                        <option selected disabled>Select Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ ucwords($role->name) }}</option>
                        @endforeach
                    </select>
                    {{-- <input type="text" class="form-control" id="username" name="username"
                        value="{{ ( isset($userDetail) ? $userDetail->username: old('username') )}}"
                        required
                        autocomplete="off" placeholder="{{ __('index.enter_username') }}"> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">
    <!-- <i class="link-icon" data-feather="plus"></i> {{isset($userDetail)? __('index.update_user'):__('index.create_user')}} -->
     {{isset($userDetail)? __('index.update_user'):__('index.create_user')}}
</button>
