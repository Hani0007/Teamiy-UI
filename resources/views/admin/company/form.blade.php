<div class="teamy-body-wrapper">
    <div class="teamy-top-header">
        <div>
            <h2>Company Profile</h2>
            <div class="header-info-row">
                <div class="header-info-item"><span class="status-badge">Active</span></div>
                <div class="header-info-item"><i class="fa fa-code-branch"></i> Main Branch</div>
                <div class="header-info-item"><i class="fa fa-users"></i> {{ isset($companyDetail) && $companyDetail ? $companyDetail->no_of_employees : '0' }} Employees</div>
                <div class="header-info-item"><i class="fa fa-map-marker-alt"></i> {{ isset($companyDetail) && $companyDetail ? $companyDetail->city : 'Location Not Set' }}</div>
            </div>
        </div>
        <div class="circle-progress-wrapper">
            <svg class="circle-progress-svg">
                <circle class="circle-bg" cx="35" cy="35" r="32"></circle>
                <circle class="circle-bar" id="js-progress-circle" cx="35" cy="35" r="32"></circle>
            </svg>
            <div class="circle-percent" id="js-progress-text">0%</div>
        </div>
    </div>

    @canany(['create_company','edit_company'])
    <!--<form action="{{ $companyDetail ? route('admin.company.update', $companyDetail->id) : route('admin.company.store') }}" method="POST" enctype="multipart/form-data" id="companyForm">
        @csrf
        @if($companyDetail) @method('PUT') @endif-->
        
        <input type="hidden" name="contact_number" id="final_contact_number" value="{{ isset($companyDetail) && $companyDetail ? $companyDetail->contact_number : '' }}">
        <input type="hidden" name="country_code" id="final_country_code" value="{{ isset($companyDetail) && $companyDetail ? $companyDetail->country_code : '' }}">

        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon"><i class="fa fa-building"></i></div>
                <div class="section-heading-text"><h4>Company Identity</h4><p>Basic information and branding</p></div>
            </div>
            <div class="section-divider"></div>

            <div class="logo-container">
                <div class="logo-img-box" id="preview-box">
                    @if($companyDetail && $companyDetail->logo)
                        <img src="{{ asset('storage/uploads/company/logo/'.$companyDetail->logo) }}" id="logo-img">
                    @else
                        <i class="fa fa-image fa-2x text-muted"></i>
                    @endif
                </div>
                <div class="logo-meta">
                    <label class="teamy-label">Company Logo</label>
                    <input type="file" name="logo" id="logo-input" style="display:none" onchange="previewFile(this)">
                    <button type="button" class="btn btn-sm" onclick="document.getElementById('logo-input').click()" style="background:var(--teamy-orange); color:white; border-radius:6px; padding:6px 15px; border:none;">Upload Logo</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearLogo()" style="border-radius:6px; padding:6px 15px; border:1px solid #dc3545; color:#dc3545; background:none; margin-left:5px;">Remove</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="teamy-label">Company Name *</label>
                    <input type="text" name="name" class="teamy-input-field" value="{{ old('name', $companyDetail->name ?? '') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="teamy-label">Industry Type *</label>
                    <select name="industry_type" class="teamy-input-field">
                        @foreach($industries as $industry)
                            <option value="{{ $industry->id }}" {{ (old('industry_type', $companyDetail->industry_type ?? '')) == $industry->id ? 'selected' : '' }}>{{ $industry->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3"><label class="teamy-label">Employees Count</label><input type="number" name="no_of_employees" class="teamy-input-field" value="{{ old('no_of_employees', $companyDetail->no_of_employees ?? '') }}"></div>
                <div class="col-md-4 mb-3"><label class="teamy-label">Website URL</label><input type="url" name="website_url" class="teamy-input-field" value="{{ old('website_url', $companyDetail->website_url ?? '') }}"></div>
                <div class="col-md-4 mb-3"><label class="teamy-label">VAT Number</label><input type="text" name="vat_number" class="teamy-input-field" value="{{ old('vat_number', $companyDetail->vat_number ?? '') }}"></div>
                <div class="col-md-6 mb-3"><label class="teamy-label">Registration Number</label><input type="text" name="company_registration" class="teamy-input-field" value="{{ old('company_registration', $companyDetail->company_registration ?? '') }}"></div>
            </div>
        </div>

        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon"><i class="fa fa-map-marked-alt"></i></div>
                <div class="section-heading-text"><h4>Contact & Location</h4><p>Business operation details</p></div>
            </div>
            <div class="section-divider"></div>
            <div class="row">
                <div class="col-md-12 mb-3"><label class="teamy-label">Full Address *</label><input type="text" name="address" class="teamy-input-field" value="{{ old('address', $companyDetail->address ?? '') }}" required></div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <label for="contact_number" class="form-label">Contact Number <span style="color: red">*</span></label>
                    <div class="input-group phone-group" data-no-combine="true">
                        <select class="form-select phone-country" id="company_phone_code" name="country_code" style="max-width: 140px"
                                data-current="{{ old('country_code', $companyDetail->country_code ?? '92') }}" ></select>
                        <input type="tel" class="form-control" id="contact_number" name="contact_number"
                            value="{{ old('contact_number', $companyDetail->contact_number ?? '') }}"
                            autocomplete="off" required placeholder="">
                    </div>
                    @error('country_code') <small class="text-danger">{{ $message }}</small> @enderror
                    @error('contact_number') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="teamy-label">Country *</label>
                    <select name="country" class="teamy-input-field" required>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ (old('country', $companyDetail->country ?? '')) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3"><label class="teamy-label">Province / State</label><input type="text" name="province" class="teamy-input-field" value="{{ old('province', $companyDetail->province ?? '') }}"></div>
                <div class="col-md-6 mb-3"><label class="teamy-label">City *</label><input type="text" name="city" class="teamy-input-field" value="{{ old('city', $companyDetail->city ?? '') }}" required></div>
                <div class="col-md-6 mb-3"><label class="teamy-label">Postal Code</label><input type="text" name="postal_code" class="teamy-input-field" value="{{ old('postal_code', $companyDetail->postal_code ?? '') }}"></div>
            </div>
        </div>

        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon"><i class="fa fa-clock"></i></div>
                <div class="section-heading-text"><h4>Schedule & Currency</h4><p>Operational hours and preferences</p></div>
            </div>
            <div class="section-divider"></div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="teamy-label">Currency Preference</label>
                    <select name="currency_preference" class="teamy-input-field">
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}" {{ (old('currency_preference', $companyDetail->currency_preference ?? '')) == $currency->id ? 'selected' : '' }}>{{ $currency->name }} ({{ $currency->symbol }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <label class="teamy-label">Weekly Off Days</label>
            <div class="weekend-group mb-4">
                @php 
                    $weekends = $companyDetail->weekend ?? []; 
                    if(!is_array($weekends)) { $weekends = json_decode($weekends, true) ?? []; }
                @endphp
                @foreach(['SUN'=>'0','MON'=>'1','TUE'=>'2','WED'=>'3','THU'=>'4','FRI'=>'5','SAT'=>'6'] as $name => $val)
                    <input type="checkbox" id="d-{{$val}}" name="weekend[]" value="{{$val}}" class="weekend-checkbox" {{ in_array($val, $weekends) ? 'checked' : '' }}>
                    <label for="d-{{$val}}" class="weekend-label">{{$name}}</label>
                @endforeach
            </div>

            <!--<div class="terms-wrapper">
                <input type="checkbox" name="terms_conditions" id="tc_check" value="1" required checked>
                <label for="tc_check" style="font-size: 14px; font-weight: 600; color: #444; cursor: pointer; margin-left: 10px;">I agree to the Terms and Conditions *</label>
            </div>-->

            <div class="footer-action-bar">
                <div class="text-muted small"><i class="fa fa-sync"></i> Standard Timezone</div>
                <div>
                    <button type="button" class="btn-discard branch-back-btn" onclick="window.history.back()">Back</button>
                    <button type="submit" class="btn btn-update">{{ $companyDetail ? 'Update Company' : 'Save Company' }}</button>
                </div>
            </div>
        </div>
    <!--</form>-->
    @endcanany
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
<script>
    // Phone Setup
    const input = document.querySelector("#phone_field");
    const hNum = document.querySelector("#final_contact_number");
    const hCode = document.querySelector("#final_country_code");
    const form = document.querySelector("#companyForm");

    const iti = window.intlTelInput(input, {
        initialCountry: "it",
        separateDialCode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
    });

    function syncPhoneData() {
        const code = iti.getSelectedCountryData().dialCode;
        const cleanNumber = input.value.replace(/\D/g,'');
        if (code) {
            hCode.value = code;
            hNum.value = code + "-" + cleanNumber;
        }
        updateFormProgress();
    }

    input.addEventListener('input', syncPhoneData);
    input.addEventListener('countrychange', syncPhoneData);

    form.addEventListener('submit', function(e) {
        syncPhoneData();
        if(!hCode.value || hNum.value.length < 5) {
            e.preventDefault();
            alert("Please enter a valid phone number");
        }
    });

    // Logo Functions
    function previewFile(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('preview-box').innerHTML = `<img src="${e.target.result}" id="logo-img">`;
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearLogo() {
        document.getElementById('logo-input').value = "";
        document.getElementById('preview-box').innerHTML = `<i class="fa fa-image fa-2x text-muted"></i>`;
    }

    // Progress Bar Logic
    function updateFormProgress() {
        const fields = ['name', 'address', 'city', 'country', 'industry_type'];
        let filled = 0;
        fields.forEach(f => {
            const el = document.getElementsByName(f)[0];
            if(el && el.value.trim() !== "") filled++;
        });

        if(input.value.trim() !== "") filled++;
        
        const total = fields.length + 1;
        const percentage = Math.round((filled / total) * 100);
        
        document.getElementById('js-progress-text').innerText = percentage + "%";
        const offset = 220 - (220 * percentage / 100);
        document.getElementById('js-progress-circle').style.strokeDashoffset = offset;
    }

    window.onload = () => {
        syncPhoneData();
        updateFormProgress();
    };

    document.querySelectorAll('.teamy-input-field').forEach(el => {
        el.addEventListener('input', updateFormProgress);
    });
</script>