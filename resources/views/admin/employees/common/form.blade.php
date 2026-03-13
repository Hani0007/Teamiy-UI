@php use App\Models\User; @endphp
@php use App\Models\EmployeeAccount; @endphp
<div class="mb-2 text-md-start text-center"><small>{!! __('index.all_fields_required') !!}</small></div>
<style>
    .is-invalid {
        border-color: red !important;
    }

    .is-invalid+.error-message {
        display: block;
        color: red !important;
    }

    .error-message {
        display: none;
        color: red !important;
    }

    .remove-doc {
        position: absolute;
        top: -10px;
        right: -10px;
        padding: 5px 9px;
        background-color: #057db0;
        color: white;
        border-radius: 50%;
        cursor: pointer;
    }

    .remove-contract {
        position: absolute;

        padding: 2px 6px;
        background-color: #057db0;
        color: white;
        border-radius: 50%;
        cursor: pointer;
    }

    .remove-doc:hover {
        background-color: #6193a7 !important;
        color: white;
    }
</style>
<div class="card mb-4 teamy-main-card mb-4">
    <div class="card-body pb-2 ">
        <div class="profile-detail">
            <div class="section-title-wrapper border-bottom w-100 pb-3">
                <div class="section-icon"><i class="fa fa-user"></i></div>
                <div class="section-heading-text ">
                    <h4>{{ __('index.personal_detail') }}</h4>
                    <p>Basic identity and contact information</p>
                </div>
            </div>
            <!-- <h5 class="mb-3 border-bottom pb-3">{{ __('index.personal_detail') }}</h5> -->
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-3 @if (isset($userDetail) && $userDetail->role_id == 1) d-none @endif">
                    <label for="employee_code" class="form-label">{{ __('index.employee_code') }} </label>
                    <input type="text" class="form-control" id="employee_code" name="employee_code" readonly
                        style="pointer-events: none;"
                        value="{{ isset($userDetail) ? $userDetail->employee_code : $employeeCode }}" autocomplete="off"
                        placeholder="{{ __('index.employee_code') }}" required>
                </div>
                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="name" class="form-label"> {{ __('index.name') }} <span
                            style="color: red">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ isset($userDetail) ? $userDetail->name : old('name') }}" autocomplete="off"
                        placeholder="{{ __('index.enter_name') }}" required>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="address" class="form-label"> {{ __('index.address') }}</label>
                    {{-- <input type="text"
                           class="form-control"
                           id="address"
                           name="address"
                           value="{{ (isset($userDetail) ? ($userDetail->address): old('address'))}}"
                           autocomplete="off" placeholder="{{ __('index.enter_employee_address') }}"> --}}

                    <input type="text" class="form-control" id="address" required name="address"
                        value="{{ isset($userDetail) ? $userDetail->address : old('address') }}" autocomplete="off">

                    <div id="address-suggestions" class="list-group position-absolute w-100" style="z-index: 1000;">
                    </div>

                    @error('address')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="email" class="form-label">Personal Email <span style="color: red">*</span></label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ isset($userDetail) ? $userDetail->email : old('email') }}" required
                        autocomplete="off" placeholder="{{ __('index.enter_email') }}">
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="work_email" class="form-label">Work Email <span style="color: red">*</span></label>
                    <input type="email" class="form-control" id="work_email" name="work_email"
                        value="{{ isset($userDetail) ? $userDetail->work_email : old('work_email') }}" required
                        autocomplete="off" placeholder="Enter Work Email">
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="number" class="form-label">{{ __('index.phone_no') }}</label>
                    <div class="input-group phone-group">
                        <select class="form-select phone-country" id="company_phone_code" style="max-width: 140px"
                            data-current="{{ $companyDetail->country_code ?? '92' }}"></select>
                        <input type="tel" class="form-control" id="phone" name="phone"
                            value="{{ isset($userDetail) ? $userDetail->phone : old('phone') }}" autocomplete="off"
                            placeholder="{{ __('index.phone_no') }}">
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="dob" class="form-label"> {{ __('index.dob') }} </label>
                    @if ($bsEnabled)
                        <input type="text" class="form-control birthDate" id="dob" name="dob"
                            value="{{ isset($userDetail->dob) ? \App\Helpers\AppHelper::taskDate($userDetail->dob) : old('dob') }}"
                            autocomplete="off" placeholder="{{ __('index.dob') }}">
                    @else
                        <input type="date" class="form-control" id="dob" name="dob"
                            value="{{ isset($userDetail) ? $userDetail->dob : old('dob') }}" autocomplete="off"
                            placeholder="{{ __('index.dob') }}">
                    @endif

                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="gender" class="form-label">{{ __('index.gender') }}</label>
                    <select class="form-select" id="gender" name="gender">
                        <option value="" {{ isset($userDetail) || old('gender') ? '' : 'selected' }} disabled>
                            {{ __('index.select_gender') }}
                        </option>
                        @foreach (User::GENDER as $value)
                            <option value="{{ $value }}"
                                {{ (isset($userDetail) && $userDetail->gender == $value) || old('gender') == $value ? 'selected' : '' }}>
                                {{ ucfirst($value) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="marital_status" class="form-label">{{ __('index.marital_status') }}</label>
                    <select class="form-select" id="marital_status" name="marital_status" required>
                        <option value="" {{ isset($userDetail) || old('marital_status') ? '' : 'selected' }}
                            disabled>
                            {{ __('index.choose_marital_status') }}
                        </option>
                        @foreach (User::MARITAL_STATUS as $value)
                            <option value="{{ $value }}"
                                {{ (isset($userDetail) && $userDetail->marital_status == $value) || old('marital_status') == $value ? 'selected' : '' }}>
                                {{ ucfirst($value) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="nationality" class="form-label">Nationality</label>
                    <select class="form-select" id="nationality" name="nationality" required>
                        <option value="" {{ isset($userDetail) || old('nationality') ? '' : 'selected' }}
                            disabled>
                            Select
                        </option>
                        @foreach (App\Helpers\AppHelper::getCountries() as $value)
                            <option value="{{ $value->id }}"
                                {{ (isset($userDetail) && $userDetail->nationality == $value->id) || old('nationality') == $value->id ? 'selected' : '' }}>
                                {{ ucfirst($value->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="place_of_birth" class="form-label">Place of Birth <span
                            style="color: #6c757d">(optional)</span></label>
                    <input type="text" class="form-control" id="place_of_birth" name="place_of_birth"
                        value="{{ isset($userDetail) ? $userDetail->place_of_birth : old('place_of_birth') }}"
                        autocomplete="off" placeholder="Enter Place of Birth">
                </div>

                <div class="col-lg-4 mb-3">
                    <label for="avatar" class="form-label">{{ __('index.upload_avatar') }} <span
                            style="color: red">*</span> </label>
                    <input class="form-control" type="file" id="avatar" name="avatar" accept="image/*"
                        value="{{ isset($userDetail) ? $userDetail->avatar : old('avatar') }}"
                        {{ isset($userDetail) ? '' : 'required' }}>

                    @if (isset($userDetail))
                        @if ($userDetail->avatar && file_exists(public_path(User::AVATAR_UPLOAD_PATH . $userDetail->avatar)))
                            <img class="mt-2 rounded" id="image-preview"
                                src="{{ asset(User::AVATAR_UPLOAD_PATH . $userDetail->avatar) }}"
                                style="object-fit: contain" width="100" height="100" alt="profile">
                        @else
                            <img class="mt-2 rounded" id="image-preview" src="{{ asset('assets/images/img.png') }}"
                                style="object-fit: contain" width="100" height="100" alt="profile">
                        @endif
                    @endif
                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 mb-3 empl-desc">
                            <label for="remarks" class="form-label">{{ __('index.description') }}</label>
                            <textarea class="form-control" name="remarks" id="tinymceExample" rows="2">{{ isset($userDetail) ? $userDetail->remarks : old('remarks') }}</textarea>
                        </div>

                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-12 col-md-4 mb-3">
                                    <label for="username" class="form-label">{{ __('index.username') }} <span
                                            style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        value="{{ isset($userDetail) ? $userDetail->username : old('username') }}"
                                        required autocomplete="off" placeholder="{{ __('index.enter_username') }}">
                                </div>
                                @if (!isset($userDetail))
                                    <!-- <div class="col-lg-12 col-md-4 mb-3">
                                        <label for="password" class="form-label">{{ __('index.password') }} <span style="color: red">*</span></label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            value="{{ old('password') }}" autocomplete="off" placeholder="{{ __('index.enter_password') }}" required>

                                    </div> -->
                                    <div class="col-lg-12 col-md-4 mb-3 position-relative">
                                        <label for="password" class="form-label">{{ __('index.password') }} <span
                                                style="color: red">*</ span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password"
                                                name="password" value="{{ old('password') }}" autocomplete="off"
                                                placeholder="{{ __('index.enter_password') }}" required>
                                            <span class="input-group-text" id="togglePassword"
                                                style="cursor: pointer;">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-lg-12 col-md-4 mb-3">
                                    <label for="role" class="form-label">{{ __('index.role') }} <span
                                            style="color: red">*</span></label>
                                    <select class="form-select" id="role" name="role_id" required>
                                        <option value=""
                                            {{ isset($userDetail) || old('role_id') ? '' : 'selected' }} disabled>
                                            {{ __('index.select_role') }}
                                        </option>
                                        @if ($roles)
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}"
                                                    {{ (isset($userDetail) && $userDetail->hasRole($role->name)) || old('role') == $role->name ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.5.5/build/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var group = document.querySelector(".phone-group");
        if (!group) return;
        var input = group.querySelector('input[type="tel"]');
        var select = group.querySelector('.phone-country');
        if (!input || !select || !window.intlTelInput) return;
        var iti = window.intlTelInput(input, {
            nationalMode: false
        });
        var data = iti.getCountryData();
        select.innerHTML = "";
        data.forEach(function(country) {
            var option = document.createElement("option");
            option.value = country.dialCode;
            option.text = "+" + country.dialCode;
            option.setAttribute("data-country", country.iso2);
            select.appendChild(option);
        });
        select.value = "92";
        select.addEventListener("change", function() {
            iti.setCountry(this.options[this.selectedIndex].getAttribute("data-country"));
        });
    });
</script>

<div class="card mb-4 teamy-main-card">
    <div class="card-body pb-2">
        <div class="company-detail">
            <div class="section-title-wrapper border-bottom w-100 pb-4">
                <div class="section-icon">
                    <i class="fa fa-building"></i>
                </div>
                <div class="section-heading-text">
                    <h4>{{ __('index.company_detail') }}</h4>
                    <p>Define the employee's role, department, and work timings</p>
                </div>
            </div>
            <!-- <h5 class="mb-3 border-bottom pb-3">{{ __('index.company_detail') }}</h5> -->
            <div class="row">
                @if (!isset(auth()->user()->branch_id))
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="branch_id" class="form-label">{{ __('index.branch') }} <span
                                style="color: red">*</span></label>
                        <select class="form-select @error('branch_id') is-invalid @enderror" id="branch"
                            name="branch_id" required>
                            <option value="" selected disabled>{{ __('index.select_branch') }}
                            </option>
                            @if (isset($companyDetail))
                                @foreach ($companyDetail->branches()->get() as $key => $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ isset($userDetail) && $userDetail->branch_id == $branch->id ? 'selected' : '' }}>
                                        {{ ucfirst($branch->name) }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('branch_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="department" class="form-label">{{ __('index.departments') }} <span
                            style="color: red">*</span></label>
                    <select class="form-select @error('department_id') is-invalid @enderror" id="department"
                        name="department_id" required>
                        @if (isset($userDetail->department))
                            <option value="{{ $userDetail->department->id }}" selected>
                                {{ $userDetail->department->dept_name }}</option>
                        @else
                            <option value="" selected disabled>{{ __('index.select_department') }}</option>
                        @endif
                    </select>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="post" class="form-label ">Designations <span style="color: red">*</span></label>
                    <select class="form-select @error('post_id') is-invalid @enderror" id="post" name="post_id"
                        required>
                        @if (isset($userDetail->post))
                            <option value="{{ $userDetail->post->id }}" selected>{{ $userDetail->post->post_name }}
                            </option>
                        @else
                            <option selected>Select Designation</option>
                        @endif
                    </select>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="supervisor" class="form-label">{{ __('index.supervisor') }}</label>
                    <select class="form-select" id="supervisor" name="supervisor_id">
                        @if (isset($userDetail))
                            @foreach ($filteredSupervisor as $supervisor)
                                @if ($supervisor->id !== $userDetail->id)
                                    <option value="{{ $supervisor->id }}"
                                        {{ $supervisor->id == $userDetail->supervisor_id ? 'selected' : '' }}>
                                        {{ ucfirst($supervisor->name) }}
                                    </option>
                                @endif
                            @endforeach
                        @else
                            <option selected disabled>{{ __('index.select_supervisor') }}</option>
                        @endif
                    </select>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="employment_type" class="form-label">{{ __('index.employment_type') }}
                    </label>
                    <select class="form-select" id="employment_type" name="employment_type">
                        <option value="" {{ isset($userDetail) || old('employment_type') ? '' : 'selected' }}
                            disabled>
                            {{ __('index.select_employment_type') }}
                        </option>
                        @foreach (User::EMPLOYMENT_TYPE as $value)
                            <option value="{{ $value }}"
                                {{ (isset($userDetail) && $userDetail->employment_type == $value) || old('employment_type') == $value ? 'selected' : '' }}>
                                {{ ucfirst($value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="officeTime" class="form-label ">{{ __('index.office_time') }} <span
                            style="color: red">*</span></label>
                    <select class="form-select @error('office_time_id') is-invalid @enderror" id="officeTime"
                        name="office_time_id" required>

                    </select>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="joining_date" class="form-label">{{ __('index.joining_date') }}</label>
                    @if ($bsEnabled)
                        <input type="text" class="form-control joiningDate" id="joining_date" name="joining_date"
                            value="{{ isset($userDetail->joining_date) ? \App\Helpers\AppHelper::taskDate($userDetail->joining_date) : old('joining_date') }}"
                            autocomplete="off" placeholder="{{ __('index.enter_joining_date') }}">
                    @else
                        <input type="date" class="form-control" id="joining_date" name="joining_date"
                            value="{{ isset($userDetail) ? $userDetail->joining_date : old('joining_date') }}"
                            autocomplete="off" placeholder="{{ __('index.enter_joining_date') }}">
                    @endif

                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="workspace_type" class="form-label">{{ __('index.workspace') }}</label>
                    <select class="form-select" id="workspace_type" name="workspace_type">
                        <option value="" {{ isset($userDetail) || old('workspace_type') ? '' : 'selected' }}
                            disabled>
                            {{ __('index.select_workspace') }}
                        </option>
                        <option value="{{ User::FIELD }}"
                            {{ (isset($userDetail) && $userDetail->workspace_type == User::FIELD) || old('workspace_type') == User::FIELD ? 'selected' : '' }}>
                            {{ __('index.field') }}
                        </option>
                        <option value="{{ User::OFFICE }}"
                            {{ (isset($userDetail) && $userDetail->workspace_type == User::OFFICE) || old('workspace_type') == User::OFFICE ? 'selected' : '' }}>
                            {{ __('index.office') }}
                        </option>

                    </select>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="fiscal_number" class="form-label">Fiscal Number <span
                            style="color: #6c757d">(optional)</span></label>
                    <input type="text" class="form-control" id="fiscal_number" name="fiscal_number"
                        value="{{ isset($userDetail) ? $userDetail->fiscal_number : old('fiscal_number') }}"
                        autocomplete="off" placeholder="Enter Fiscal Number">
                </div>

                <div class="col-lg-4 col-md-6 mt-5">
                    <input type="checkbox" name="allow_holiday_check_in" id="allow_holiday_check_in"
                        {{ isset($userDetail) && $userDetail->allow_holiday_check_in == 1 ? 'checked' : '' }}>
                    {{ __('index.allow_holiday_check_in') }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4 ">
    <div class="card-body pb-2 teamy-main-card px-5">
        <div class="profile-detail">
            <div class="section-title-wrapper border-bottom w-100 pb-4">
                <div class="section-icon"><i class="fa fa-file-contract"></i></div>
                <div class="section-heading-text">
                    <h4>Employee Contract & Documents</h4>
                    <p>Manage tenure, pay grade, and legal documents</p>

                </div>

            </div>

            <!-- <h5 class="mb-3 border-bottom pb-3">Employee Contract</h5> -->
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="contract_start_date" class="form-label">Contract Start Date <span
                            style="color: red">*</span></label>
                    <input type="date" class="form-control" id="contract_start_date" name="contract_start_date"
                        value="{{ isset($userDetail->contract_start_date) ? $userDetail->contract_start_date : old('contract_start_date') }}"
                        autocomplete="off" placeholder="Select Date" required>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="contract_end_date" class="form-label">Contract End Date <span
                            style="color: red">*</span></label>
                    <input type="date" class="form-control" id="contract_end_date" name="contract_end_date"
                        value="{{ isset($userDetail->contract_end_date) ? $userDetail->contract_end_date : old('contract_end_date') }}"
                        autocomplete="off" placeholder="Select Date" required>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="pay_grade" class="form-label">Pay Grade</label>
                    <i class="fa fa-info-circle text-primary ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Pay Grade defines the employee salary level">
                    </i>
                    <input type="text" class="form-control" id="pay_grade" name="pay_grade"
                        value="{{ isset($userDetail->pay_grade) ? $userDetail->pay_grade : old('pay_grade') }}"
                        autocomplete="off" placeholder="Enter Pay Grade">
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="contract_type" class="form-label">Contract Type <span
                            style="color: #6c757d">(optional)</span></label>
                    <select class="form-select" id="contract_type" name="contract_type">
                        <option value="" {{ isset($userDetail) || old('contract_type') ? '' : 'selected' }}
                            disabled>
                            Select Contract Type
                        </option>
                        <option value="permanent"
                            {{ (isset($userDetail) && $userDetail->contract_type == 'permanent') || old('contract_type') == 'permanent' ? 'selected' : '' }}>
                            Permanent
                        </option>
                        <option value="temporary"
                            {{ (isset($userDetail) && $userDetail->contract_type == 'temporary') || old('contract_type') == 'temporary' ? 'selected' : '' }}>
                            Temporary
                        </option>
                        <option value="contract"
                            {{ (isset($userDetail) && $userDetail->contract_type == 'contract') || old('contract_type') == 'contract' ? 'selected' : '' }}>
                            Contract
                        </option>
                        <option value="internship"
                            {{ (isset($userDetail) && $userDetail->contract_type == 'internship') || old('contract_type') == 'internship' ? 'selected' : '' }}>
                            Internship
                        </option>
                        <option value="probation"
                            {{ (isset($userDetail) && $userDetail->contract_type == 'probation') || old('contract_type') == 'probation' ? 'selected' : '' }}>
                            Probation
                        </option>
                    </select>
                </div>

                <div class="col-lg-6 col-md-6 mb-3">
                    <label for="upload_contract" class="form-label">Upload Contract</label>
                    <input type="file" class="form-control" id="upload_contract" name="upload_contract"
                        value="{{ old('upload_contract') }}" autocomplete="off">
                    {{-- <p>{{ $userDetail->employeeDocuments->employee_contract ?? '' }}</p> --}}
                    @if (!empty($userDetail->employeeDocuments->employee_contract))
                        <div class="contract-preview position-relative mt-2" style="display:inline-block;">
                            <img src="{{ asset('uploads/user/emp-documents/' . $userDetail->employeeDocuments->employee_contract) }}"
                                class="img-fluid rounded"
                                style="height:120px;width:auto;object-fit:cover; margin-top:10px;">
                            <button type="button" class="btn  btn-sm remove-contract"
                                data-file="{{ $userDetail->employeeDocuments->employee_contract }}"
                                data-user="{{ $userDetail->id }}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                </div>

                <div class="col-lg-6 mb-3 empl-desc">
                    <label for="additional_notes" class="form-label">Additional Notes</label>
                    <textarea class="form-control" name="additional_notes" id="tinymceExample" rows="2">{{ isset($userDetail) ? $userDetail->additional_notes : old('additional_notes') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body pb-2 teamy-main-card px-5">
        <div class="profile-detail">
            <!-- <h5 class="mb-3 border-bottom pb-3">Employee Document</h5> -->
            <div class="section-title-wrapper mb-4 border-bottom w-100 pb-4">
                <div class="d-flex align-items-center">
                    <div class="section-icon bg-light-primary text-primary p-2 rounded-circle me-3">
                        <i class="fa fa-file-text"></i>
                    </div>
                    <div class="section-heading-text">
                        <h4>Employee Document</h4>
                        <p class="text-muted small mb-0">Identity proofs and employment related files</p>
                    </div>

                </div>

            </div>

            <div class="row" id="document-container">

                <div class="col-lg-6 col-md-6 mb-3 document-field">
                    <label class="form-label">Upload Document</label>
                    <input type="file" class="form-control" name="employee_document[]"
                        accept="application/pdf,
                    application/msword,
                    application/vnd.openxmlformats-officedocument.wordprocessingml.document,
                    image/jpeg"
                        multiple>
                </div>
            </div>
            <div class="row" id="document-container">
                <div class="col-md-12 d-flex flex-wrap gap-3">
                    <p class="mb-3 pb-1 w-100">uploaded Documents</p>
                    @php
                        $documents = $userDetail->employeeDocuments->employee_document ?? [];
                        if (is_string($documents)) {
                            $documents = json_decode($documents, true);
                        }
                    @endphp

                    @foreach ($documents as $doc)
                        <div class="col-md-2 mb-3 document-preview">
                            <div class="position-relative border p-2 rounded">

                                <img src="{{ asset('uploads/user/emp-documents/' . $doc) }}"
                                    class="img-fluid rounded" style="height:120px;width:100%;object-fit:cover;">

                                <button type="button" class="btn  btn-sm remove-doc"
                                    data-file="{{ $doc }}" data-user="{{ $userDetail->id }}">
                                    <i class="fas fa-times"></i>
                                </button>

                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 text-end">
                    <button type="button" class="btn btn-primary" id="add-document">
                        <i class="link-icon" data-feather="plus"></i> Add More Documents
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <!-- <div class="col-lg-6 d-flex">
        <div class="card mb-4 w-100">
            <div class="card-body">
                <div class="bank-detail">
                    <h5 class="mb-3 border-bottom pb-3">{{ __('index.leave_detail') }}</h5>
                    <label for="leave_allocated" class="form-label">{{ __('index.leave_allocated') }}</label>
                    <input type="number" class="form-control mb-2" min="0"
                           id="leave_allocated"
                           name="leave_allocated"
                           oninput="validity.valid||(value='');"
                           value="{{ isset($userDetail) ? $userDetail->leave_allocated : old('leave_allocated') }}"
                           autocomplete="off" placeholder="{{ __('index.leave_allocated') }}">

                    <div id="error-message" style="color: red !important; display: none;"></div>
                    <table class="table table-responsive">
                        <h5 class="my-3">{{ __('index.assigned_leaves') }}</h5>
                        <thead>
                        <tr>
                            <th>{{ __('index.leave') }}</th>
                            <th>{{ __('index.no_of_days') }}</th>
                            <th>{{ __('index.is_active') }}</th>
                        </tr>
                        </thead>
                        <tbody id="leave-types-table">
                        @if (isset($leaveTypes))
@for ($k = 0; $k < count($leaveTypes); $k++)
<tr>
                                    <td>
                                        {{ $leaveTypes[$k]->name }}
                                        <input type="hidden" name="leave_type_id[{{ $k }}]" value="{{ $leaveTypes[$k]->id }}">
                                    </td>
                                    @if (isset($employeeLeaveTypes[$k]))
@php $leaveType = $employeeLeaveTypes[$k]; @endphp
@endif
                                    <td>
                                        <input type="number" min="0" class="form-control leave-days"
                                               value="{{ $leaveType->days ?? '' }}"
                                               oninput="validity.valid||(value='');"
                                               placeholder="{{ __('index.total_leave_days') }}"
                                               name="days[{{ $k }}]">
                                        <span class="error-message" style="display: none; color: red;">{{ __('index.required_field') }}.</span>
                                    </td>
                                    <td>
                                        <input class="me-1 is-active-checkbox" type="checkbox"
                                               {{ isset($leaveType->is_active) && $leaveType->is_active == 1 ? 'checked' : '' }}
                                               name="is_active[{{ $k }}]" value="1">{{ __('index.is_active') }}
                                    </td>
                                </tr>
@endfor
@endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> -->
    <div class="col-lg-12 d-flex">
        <div class="card mb-4 w-100 teamy-main-card">
            <div class="card-body pb-0">
                <div class="bank-detail ">
                    <!-- <h5 class="mb-3 border-bottom pb-3">{{ __('index.bank_detail') }}</h5> -->
                    <div class="section-title-wrapper border-bottom w-100 pb-4">
                        <div class="section-icon"><i class="fa fa-university"></i></div>
                        <div class="section-heading-text ">
                            <h4>{{ __('index.bank_detail') }}</h4>
                            <p>Salary disbursement and banking information</p>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 mb-4">
                            <label for="bank_name" class="form-label">{{ __('index.bank_name') }} </label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name"
                                value="{{ isset($userDetail?->accountDetail) ? $userDetail?->accountDetail?->bank_name : old('bank_name') }}"
                                autocomplete="off" placeholder="{{ __('index.bank_name') }}">
                        </div>

                        <div class="col-lg-6 col-md-6 mb-4">
                            <label for="bank_account_no" class="form-label">{{ __('index.bank_account_number') }}
                            </label>
                            <input type="number" class="form-control" id="bank_account_no" name="bank_account_no"
                                value="{{ isset($userDetail?->accountDetail) ? $userDetail?->accountDetail?->bank_account_no : old('bank_account_no') }}"
                                autocomplete="off" placeholder=" {{ __('index.bank_account_number') }}">
                        </div>

                        <div class="col-lg-6 col-md-6 mb-4">
                            <label for="account_holder" class="form-label">{{ __('index.account_holder_name') }}
                            </label>
                            <input type="text" class="form-control" id="account_holder" name="account_holder"
                                value="{{ isset($userDetail) ? $userDetail?->accountDetail?->account_holder : old('account_holder') }}"
                                autocomplete="off" placeholder="{{ __('index.account_holder_name') }}">
                        </div>

                        <div class="col-lg-6 col-md-6 mb-4">
                            <label for="bank_account_type"
                                class="form-label">{{ __('index.bank_account_type') }}</label>
                            <select class="form-select" id="bank_account_type" name="bank_account_type">
                                <option value=""
                                    {{ isset($userDetail) || old('bank_account_type') ? '' : 'selected' }}>
                                    {{ __('index.select_account_type') }}
                                </option>
                                @foreach (EmployeeAccount::BANK_ACCOUNT_TYPE as $value)
                                    <option value="{{ $value }}"
                                        {{ (isset($userDetail?->accountDetail) && $userDetail?->accountDetail?->bank_account_type == $value) || old('bank_account_type') == $value ? 'selected' : '' }}>
                                        {{ ucfirst($value) }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="float-end">
   <a href="{{ route('admin.employees.index') }}" class="branch-back-btn btn">
                <i class="link-icon" data-feather="arrow-left me-3"></i> {{ __('index.back') }}
            </a>
<button type="submit" class="btn btn-primary ">
    {{isset($userDetail)? "Update Employee":__('index.add_employee')}}
</button>
</div>


<script>
    $(document).on('click', '.remove-doc', function() {

        let button = $(this);
        let file = button.data('file');
        let userId = button.data('user');

        Swal.fire({
            title: `{{ __('index.image_delete_confirmation') }}`,
            showDenyButton: true,
            confirmButtonText: `{{ __('index.yes') }}`,
            denyButtonText: `{{ __('index.no') }}`,
            padding: '10px 50px 10px 50px',
            allowOutsideClick: false
        }).then((result) => {
            $.ajax({
                url: "{{ route('admin.employee.document.delete') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    file: file,
                    user_id: userId
                },
                success: function(response) {

                    if (response.success) {

                        button.closest('.document-preview').remove();

                    } else {
                        alert(response.message);
                    }

                },
                error: function() {
                    alert("Something went wrong.");
                }
            });
        });



    });

    $(document).on('click', '.remove-contract', function() {

        let button = $(this);
        let userId = button.data('user');

        Swal.fire({
            title: `{{ __('index.image_delete_confirmation') }}`,
            showDenyButton: true,
            confirmButtonText: `{{ __('index.yes') }}`,
            denyButtonText: `{{ __('index.no') }}`,
            padding: '10px 50px 10px 50px',
            allowOutsideClick: false
        }).then((result) => {
            $.ajax({
                url: "{{ route('admin.employee.contract.delete') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    user_id: userId
                },
                success: function(response) {

                    if (response.success) {

                        button.closest('.contract-preview').remove();

                    } else {
                        alert(response.message);
                    }

                },
                error: function() {
                    alert("Something went wrong.");
                }
            });
        });



    });
    // address autocomplete logic
    document.addEventListener('DOMContentLoaded', function() {

        const input = document.getElementById('address');
        const suggestionsBox = document.getElementById('address-suggestions');

        if (!input || !suggestionsBox) return;

        let debounceTimer;

        input.addEventListener('keyup', function() {
            let query = this.value.trim();

            clearTimeout(debounceTimer);

            if (query.length < 2) {
                suggestionsBox.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => {

                fetch(
                        `https://us-central1-flecso-98e70.cloudfunctions.net/placesAutocomplete?input=${encodeURIComponent(query)}`
                    )
                    .then(response => response.json())
                    .then(data => {

                        suggestionsBox.innerHTML = '';

                        if (data.status === "OK" && data.predictions.length > 0) {

                            data.predictions.forEach(item => {

                                let option = document.createElement('a');
                                option.classList.add('list-group-item',
                                    'list-group-item-action');
                                option.textContent = item.description;

                                option.addEventListener('click', function() {

                                    input.value = item.description;
                                    suggestionsBox.innerHTML = '';

                                    input.dispatchEvent(new CustomEvent(
                                        'placeSelected', {
                                            detail: {
                                                placeId: item
                                                    .place_id,
                                                description: item
                                                    .description
                                            }
                                        }));
                                });

                                suggestionsBox.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Autocomplete Error:", error);
                    });

            }, 300);
        });

        input.addEventListener('placeSelected', function(e) {

            console.log("Event Triggered");

            const placeId = e.detail.placeId;

            fetch(
                    `https://us-central1-flecso-98e70.cloudfunctions.net/placeDetails?place_id=${placeId}`
                )
                .then(res => res.json())
                .then(data => {
                    if (data.status === "OK") {

                        const location = data.result.geometry.location;

                        if (document.getElementById('branch_location_latitude')) {
                            document.getElementById('branch_location_latitude').value = location
                                .lat;
                            document.getElementById('branch_location_longitude').value = location
                                .lng;
                        }

                        // Fill address components safely
                        fillAddressComponents(data.result);

                    }
                })
                .catch(err => console.error('Place Details Error:', err));
        });

        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.innerHTML = '';
            }
        });
    });

    function fillAddressComponents(result) {
        console.log("Filling address components with result:", result);
        if (!result) return;

        let country = '';
        let postalCode = '';
        let province = '';
        let city = '';

        // Use address_components if available (most reliable)
        if (result.address_components && Array.isArray(result.address_components)) {
            const components = result.address_components;

            components.forEach(component => {
                const types = component.types || [];
                const longName = component.long_name || '';

                if (types.includes('postal_code')) {
                    postalCode = longName;
                }

                if (types.includes('locality')) {
                    city = longName;
                }

                if (types.includes('administrative_area_level_1')) {
                    province = longName;
                }

                if (types.includes('country')) {
                    country = longName;
                }
            });
        }

        if (!city || !country || !postalCode || !province) {
            const parts = result.formatted_address.split(',').map(p => p.trim());

            if (!country) {
                country = parts[parts.length - 1] || '';
            }

            if ((!postalCode || !province) && parts.length >= 2) {
                const postalProvincePart = parts[parts.length - 2];

                const postalMatch = postalProvincePart.match(/(\d+)\s*$/);
                if (postalMatch && !postalCode) {
                    postalCode = postalMatch[1];
                }

                if (!province) {
                    province = postalProvincePart.replace(postalCode, '').trim();
                }
            }

            if (!city && parts.length >= 3) {
                city = parts[parts.length - 3].trim();
            }
        }

        const provinceInput = document.querySelector('input[name="province"]');
        const cityInput = document.querySelector('input[name="city"]');
        const postalInput = document.querySelector('input[name="postal_code"]');

        if (provinceInput) provinceInput.value = province || '';
        if (cityInput) cityInput.value = city || '';
        if (postalInput) postalInput.value = postalCode || '';

        const countrySelect = document.querySelector('select[name="country"]');
        if (countrySelect) {
            let matched = false;
            for (let option of countrySelect.options) {
                if (option.text.toLowerCase() === country.toLowerCase()) {
                    option.selected = true;
                    matched = true;
                    break;
                }
            }
            if (!matched && countrySelect.options.length > 0) countrySelect.selectedIndex = 0;
        }

        console.log("Parsed Address:", {
            country,
            province,
            city,
            postalCode
        });
    }
</script>
