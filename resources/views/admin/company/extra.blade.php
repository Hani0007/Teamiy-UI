<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>

    :root {
        --teamy-navy: #057db0; --teamy-orange: #fb7633; --teamy-bg: #f5f7fb;        
        --teamy-border: #eaedf2; --label-black: #1a1a1a; --text-muted: #64748b;      
    }
    .teamy-body-wrapper {font-family: 'Public Sans', sans-serif; padding: 20px; }
    .teamy-top-header { background: var(--teamy-navy); border-radius: 12px; padding: 30px 40px; color: white; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
    .header-info-row { display: flex; gap: 25px; margin-top: 15px; font-size: 13px; opacity: 0.9; }
    .status-badge { background: #22c55e; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .circle-progress-wrapper { position: relative; width: 70px; height: 70px; }
    .circle-progress-svg { transform: rotate(-90deg); width: 70px; height: 70px; }
    .circle-bg { fill: none; stroke: rgba(255,255,255,0.2); stroke-width: 6; }
    .circle-bar { fill: none; stroke: var(--teamy-orange); stroke-width: 6; stroke-dasharray: 220; stroke-dashoffset: 33; stroke-linecap: round; }
    .circle-percent { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 14px; font-weight: 800; }
    .section-title-wrapper { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
    .section-icon { width: 35px; height: 35px; background: #e0f2fe; color: var(--teamy-navy); display: flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 16px; }
    .section-heading-text h4 { margin: 0; font-size: 18px; font-weight: 700; color: #1e293b; }
    .section-divider { height: 1px; background: var(--teamy-border); margin-bottom: 25px; }
    .teamy-main-card { background: white; border-radius: 15px; border: 1px solid var(--teamy-border); padding: 30px; margin-bottom: 25px; }
    .teamy-label { color: var(--label-black); font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; display: block; }
    .teamy-input-field { background-color: #f8fafc !important; border: 1px solid #dce4ec !important; border-radius: 8px; padding: 10px 15px; font-size: 14px; width: 100%; }
    .logo-container { display: flex; gap: 20px; align-items: center; margin-bottom: 25px; }
    .logo-img-box { width: 100px; height: 100px; border: 1px solid var(--teamy-border); border-radius: 12px; overflow: hidden; background: #f1f5f9; display: flex; align-items: center; justify-content: center; }
    .logo-img-box img { width: 100%; height: 100%; object-fit: cover; }
    .weekend-group { display: flex; gap: 8px; flex-wrap: wrap; }
    .weekend-checkbox { display: none; }
    .weekend-label { padding: 10px 18px; border: 1px solid var(--teamy-border); border-radius: 8px; cursor: pointer; font-weight: 700; font-size: 12px; transition: 0.3s; background: white; }
    .weekend-checkbox:checked + .weekend-label { background-color: var(--teamy-orange) !important; color: white !important; border-color: var(--teamy-orange) !important; }
    .terms-wrapper { background: #fffcf9; border: 1px solid #ffe4d5; padding: 15px; border-radius: 10px; display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
    .terms-wrapper input[type="checkbox"] { accent-color: var(--teamy-orange); width: 20px; height: 20px; cursor: pointer; }
    .footer-action-bar { display: flex; justify-content: space-between; align-items: center; background: white; padding: 20px 30px; border-radius: 12px; border: 1px solid var(--teamy-border); }
    .btn-update { background: var(--teamy-orange); color: white; padding: 10px 30px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; }
    .btn-discard { color: var(--text-muted); font-weight: 600; text-decoration: none; margin-right: 20px; cursor: pointer; background: none; border: none; }
    .iti { width: 100%; }
    .text-danger { font-size: 11px; color: #dc3545; margin-top: 4px; display: block; }
</style>
<div class="teamy-body-wrapper">
    <div class="teamy-top-header">
        <div>
            <h2>Company Profile</h2>
            <div class="header-info-row">
                <div class="header-info-item"><span class="status-badge">Active</span></div>
                <div class="header-info-item"><i class="fa fa-code-branch"></i> Main Branch</div>
                <div class="header-info-item"><i class="fa fa-users"></i> {{ $companyDetail->no_of_employees ?? '0' }} Employees</div>
                <div class="header-info-item"><i class="fa fa-map-marker-alt"></i> {{ $companyDetail->city ?? 'Location Not Set' }}</div>
            </div>
        </div>
        <div class="circle-progress-wrapper">
            <svg class="circle-progress-svg"><circle class="circle-bg" cx="35" cy="35" r="32"></circle><circle class="circle-bar" cx="35" cy="35" r="32"></circle></svg>
            <div class="circle-percent">85%</div>
        </div>
    </div>
    <form action="{{ $companyDetail ? route('admin.company.store') : route('admin.company.store') }}" method="POST" enctype="multipart/form-data" id="companyForm">
        @csrf
        @if($companyDetail)
            <input type="hidden" name="company_id" value="{{ $companyDetail->id }}">
        @endif
        <input type="hidden" name="contact_number" id="final_contact_number" value="">
<input type="hidden" name="country_code" id="final_country_code" value="">
        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon"><i class="fa fa-building"></i></div>
                <div class="section-heading-text"><h4>Company Identity</h4><p>Basic information and branding</p></div>
            </div>
            <div class="section-divider"></div>
            <div class="logo-container">
                <div class="logo-img-box" id="preview-box">
                    @if($companyDetail && $companyDetail->logo)
                        <img src="{{ asset('storage/uploads/company/logo/' . $companyDetail->logo) }}" id="logo-img">
                    @else
                        <i class="fa fa-image fa-2x text-muted"></i>
                    @endif
                </div>
                <div class="logo-meta">
                    <label class="teamy-label">Company Logo</label>
                    <input type="file" name="logo" id="logo-input" style="display:none" onchange="previewFile(this)" accept="image/*">
                    <button type="button" class="btn btn-sm" onclick="document.getElementById('logo-input').click()" style="background:var(--teamy-orange); color:white; border-radius:6px; padding:6px 15px; border:none;">Upload Logo</button>
                    @error('logo') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="teamy-label">Company Name *</label>
                    <input type="text" name="name" class="teamy-input-field" value="{{ old('name', $companyDetail->name ?? '') }}" required>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="teamy-label">Industry Type *</label>
                    <select name="industry_type" class="teamy-input-field" required>
                        <option value="">Select Industry</option>
                        @if(isset($industries))
                            @foreach($industries as $industry)
                                <option value="{{ $industry->id }}" {{ old('industry_type', $companyDetail->industry_type ?? '') == $industry->id ? 'selected' : '' }}>{{ $industry->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="teamy-label">Number of Employees *</label>
                    <input type="number" name="no_of_employees" class="teamy-input-field" value="{{ old('no_of_employees', $companyDetail->no_of_employees ?? '') }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="teamy-label">Website URL</label>
                    <input type="text" name="website_url" class="teamy-input-field" value="{{ old('website_url', $companyDetail->website_url ?? '') }}">
                </div>
               <div class="col-md-4 mb-3">
                    <label class="teamy-label">Registration Number</label>
                    <input type="text" name="company_registration" class="teamy-input-field" value="{{ old('company_registration', $companyDetail->company_registration ?? '') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="teamy-label">VAT Number</label>
                    <input type="text" name="vat_number" class="teamy-input-field" value="{{ old('vat_number', $companyDetail->vat_number ?? '') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="teamy-label">Currency Preferences</label>
                    <select name="currency_preference" class="teamy-input-field">
                        <option value="" selected disabled>Select Currency</option>
                        @if(isset($currencies) && count($currencies) > 0)
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}" {{ old('currency_preference', $companyDetail->currency_preference ?? '') == $currency->id ? 'selected' : '' }}>
                                    {{ $currency->name }} ({{ $currency->symbol }})
                                </option>
                            @endforeach
                        @else
                            <option value="1">USD ($)</option>
                            <option value="2">EUR (€)</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon"><i class="fa fa-map-marked-alt"></i></div>
                <div class="section-heading-text"><h4>Contact & Location</h4><p>Business operation details</p></div>
            </div>
            <div class="section-divider"></div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="teamy-label">Full Address *</label>
                    <input type="text" name="address" class="teamy-input-field" value="{{ old('address', $companyDetail->address ?? '') }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="teamy-label">Contact Number *</label>
                    <input type="tel" id="phone_field" class="teamy-input-field" value="{{ old('contact_number', $companyDetail->contact_number ?? '') }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="teamy-label">Country *</label>
                    <select name="country" class="teamy-input-field" required>
                        <option value="" disabled>Select Country</option>
                        @if(isset($countries))
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country', $companyDetail->country ?? '') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4 mb-3"><label class="teamy-label">Province / State</label><input type="text" name="province" class="teamy-input-field" value="{{ old('province', $companyDetail->province ?? '') }}"></div>
                <div class="col-md-6 mb-3"><label class="teamy-label">City</label><input type="text" name="city" class="teamy-input-field" value="{{ old('city', $companyDetail->city ?? '') }}"></div>
                <div class="col-md-6 mb-3"><label class="teamy-label">Postal Code</label><input type="text" name="postal_code" class="teamy-input-field" value="{{ old('postal_code', $companyDetail->postal_code ?? '') }}"></div>
            </div>
        </div>
        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon"><i class="fa fa-clock"></i></div>
                <div class="section-heading-text"><h4>Schedule</h4><p>Operational hours and rest days</p></div>
            </div>
            <div class="section-divider"></div>
            <label class="teamy-label">Weekly Off Days</label>
            <div class="weekend-group mb-4">
                @php
                    $weekends = old('weekend', $companyDetail->weekend ?? []);
                    if(!is_array($weekends)) { $weekends = json_decode($weekends, true) ?? []; }
                @endphp
                @foreach(['SUN'=>'0','MON'=>'1','TUE'=>'2','WED'=>'3','THU'=>'4','FRI'=>'5','SAT'=>'6'] as $name => $val)
                    <input type="checkbox" id="d-{{$val}}" name="weekend[]" value="{{$val}}" class="weekend-checkbox" {{ in_array($val, $weekends) ? 'checked' : '' }}>
                    <label for="d-{{$val}}" class="weekend-label">{{$name}}</label>
                @endforeach
            </div>
            <div class="terms-wrapper">
                <input type="checkbox" name="terms_conditions" id="tc_check" value="1" required checked>
                <label for="tc_check" style="font-size: 14px; font-weight: 600; color: #444; cursor: pointer;">I agree to the Terms and Conditions *</label>
            </div>
            <div class="footer-action-bar">
                <div class="text-muted small"><i class="fa fa-sync"></i> Standard Timezone</div>
                <div>
                    <button type="button" class="btn-discard" onclick="window.location.reload()">Discard</button>
                    @canany(['create_company','edit_company'])
                        <button type="submit" class="btn btn-update">Update Company</button>
                    @endcanany
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
<script>
    const input = document.querySelector("#phone_field");
    const hNum = document.querySelector("#final_contact_number");
    const hCode = document.querySelector("#final_country_code");
    const form = document.querySelector("#companyForm");
    const iti = window.intlTelInput(input, {
        initialCountry: "auto",
        geoIpLookup: function(callback) {
            fetch("https://ipapi.co/json").then(res => res.json()).then(data => callback(data.country_code)).catch(() => callback("it"));
        },
        separateDialCode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
    });
    function syncPhoneData() {
    const countryData = iti.getSelectedCountryData();
    const dialCode = countryData.dialCode; // Example: 92
    // User ne jo number likha usse sirf numbers nikalen
    let numericPart = input.value.replace(/\D/g, '');
    // Agar number 0 se shuru ho raha hai toh 0 hata den (standard practice)
    if (numericPart.startsWith('0')) {
        numericPart = numericPart.substring(1);
    }
    if (dialCode) {
        document.getElementById('final_country_code').value = dialCode;
        // ZAROORI: Controller ko "DialCode + Space + Number" chahiye
        // Agar aap dialCode ke baad space nahi dalenge toh error "Array key 1" ayega
        const formattedForBackend = dialCode + " " + numericPart;
        document.getElementById('final_contact_number').value = formattedForBackend;
        console.log("Sending to Backend:", formattedForBackend); // Debugging ke liye
    }
}
// Ensure events are attached
input.addEventListener('keyup', syncPhoneData);
input.addEventListener('change', syncPhoneData);
input.addEventListener('countrychange', syncPhoneData);
// Form submit hone se pehle aik baar phir sync karein
document.querySelector("#companyForm").addEventListener('submit', function(e) {
    syncPhoneData();
});
    input.addEventListener('input', syncPhoneData);
    input.addEventListener('countrychange', syncPhoneData);
    form.addEventListener('submit', function(e) {
        syncPhoneData();
        if(input.value.trim() === "") {
            e.preventDefault();
            alert("Please enter a valid phone number");
        }
    });
    function previewFile(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('preview-box').innerHTML = `<img src="${e.target.result}" id="logo-img">`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>