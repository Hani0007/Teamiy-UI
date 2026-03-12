<div class="teamy-body-wrapper">
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('company_profile') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item"><span class="status-badge">{{ __('active') }}</span></div>
                <div class="header-info-item"><i class="fa fa-code-branch"></i> {{ __('main_branch') }}</div>
                <div class="header-info-item"><i class="fa fa-users"></i>
                    {{ isset($companyDetail) && $companyDetail ? $companyDetail->no_of_employees : '0' }}
                    {{ __('employees') }}</div>
                <div class="header-info-item"><i class="fa fa-map-marker-alt"></i>
                    {{ isset($companyDetail) && $companyDetail ? $companyDetail->city : __('location_not_set') }}</div>
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

    @canany(['create_company', 'edit_company'])
        <!--<form action="{{ $companyDetail ? route('admin.company.update', $companyDetail->id) : route('admin.company.store') }}" method="POST" enctype="multipart/form-data" id="companyForm">
                                    @csrf
                                    @if ($companyDetail)
    @method('PUT')
    @endif-->

        @php
            // Properly initialize phone fields for editing
            $initialCountryCode = '';
            $initialPhoneNumber = '';
            if (isset($companyDetail) && $companyDetail) {
                $initialCountryCode = $companyDetail->country_code ?? '';
                $initialPhoneNumber = $companyDetail->contact_number ?? '';
            }
        @endphp

        <input type="hidden" name="contact_number" id="final_contact_number" value="{{ $initialPhoneNumber }}">
        <input type="hidden" name="country_code" id="final_country_code" value="{{ $initialCountryCode }}">

        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon"><i class="fa fa-building"></i></div>
                <div class="section-heading-text">
                    <h4>{{ __('company_identity') }}</h4>
                    <p>{{ __('basic_information_and_branding') }}</p>
                </div>
            </div>
            <div class="section-divider"></div>

            <div class="logo-container">
                <div class="logo-img-box" id="preview-box">
                    @if ($companyDetail && $companyDetail->logo)
                        @php
                            $publicPath = public_path('uploads/company/logo/' . $companyDetail->logo);
                            $storagePath = storage_path('app/public/uploads/company/logo/' . $companyDetail->logo);
                            $imagePath = file_exists($publicPath)
                                ? asset('uploads/company/logo/' . $companyDetail->logo)
                                : asset('storage/uploads/company/logo/' . $companyDetail->logo);
                        @endphp
                        <img src="{{ $imagePath }}" id="logo-img">
                    @else
                        <i class="fa fa-image fa-2x text-muted"></i>
                    @endif
                </div>
                <div class="logo-meta">
                    <label class="teamy-label">{{ __('company_logo') }}</label>
                    <input type="file" name="logo" id="logo-input" style="display:none" onchange="previewFile(this)">
                    <button type="button" class="btn btn-sm" onclick="document.getElementById('logo-input').click()"
                        style="background:var(--teamy-orange); color:white; border-radius:6px; padding:6px 15px; border:none;">{{ __('index.upload_logo') }}</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearLogo()"
                        style="border-radius:6px; padding:6px 15px; border:1px solid #dc3545; color:#dc3545; background:none; margin-left:5px;">{{ __('remove') }}</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="teamy-label">{{ __('index.company_name') }} *</label>
                    <input type="text" name="name" class="teamy-input-field"
                        value="{{ old('name', $companyDetail->name ?? '') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="teamy-label">{{ __('industry_type') }} *</label>
                    <select name="industry_type" class="teamy-input-field">
                        @foreach ($industries as $industry)
                            <option value="{{ $industry->id }}"
                                {{ old('industry_type', $companyDetail->industry_type ?? '') == $industry->id ? 'selected' : '' }}>
                                {{ $industry->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3"><label class="teamy-label">{{ __('no_of_employees') }}</label><input
                        type="number" name="no_of_employees" class="teamy-input-field"
                        value="{{ old('no_of_employees', $companyDetail->no_of_employees ?? '') }}"></div>
                <div class="col-md-4 mb-3"><label class="teamy-label">{{ __('index.website_url') }}</label><input
                        type="url" name="website_url" class="teamy-input-field"
                        value="{{ old('website_url', $companyDetail->website_url ?? '') }}"></div>
                <div class="col-md-4 mb-3"><label class="teamy-label">{{ __('vat_number') }}</label><input type="text"
                        name="vat_number" class="teamy-input-field"
                        value="{{ old('vat_number', $companyDetail->vat_number ?? '') }}"></div>
                <div class="col-md-6 mb-3"><label class="teamy-label">{{ __('registration_number') }}</label><input
                        type="text" name="company_registration" class="teamy-input-field"
                        value="{{ old('company_registration', $companyDetail->company_registration ?? '') }}"></div>
            </div>
        </div>

        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon"><i class="fa fa-map-marked-alt"></i></div>
                <div class="section-heading-text">
                    <h4>{{ __('contact_location') }}</h4>
                    <p>{{ __('business_operation_details') }}</p>
                </div>
            </div>
            <div class="section-divider"></div>
            <div class="row">
                <div class="col-md-12 mb-3"><label class="teamy-label">{{ __('full_address') }} *</label><input
                        type="text" name="address" id="address" class="teamy-input-field"
                        value="{{ old('address', $companyDetail->address ?? '') }}" required></div>
                <div id="address-suggestions" class="address-suggestions hidden absolute overflow-y-auto z-50"></div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <label for="contact_number" class="form-label">{{ __('index.contact_number') }} <span
                            style="color: red">*</span></label>
                    <div class="input-group phone-group" data-no-combine="true">
                        <select class="form-select phone-country" id="company_phone_code" name="country_code"
                            style="max-width: 140px"
                            data-current="{{ old('country_code', $companyDetail->country_code ?? '92') }}"></select>
                        <input type="tel" class="form-control" id="contact_number" name="contact_number"
                            value="{{ old('contact_number', $companyDetail->contact_number ?? '') }}" autocomplete="off"
                            required placeholder="">
                    </div>
                    @error('country_code')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    @error('contact_number')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="teamy-label">{{ __('index.country') }} *</label>
                    <select name="country" class="teamy-input-field" required>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}"
                                {{ old('country', $companyDetail->country ?? '') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3"><label class="teamy-label">{{ __('province') }}</label><input type="text"
                        name="province" class="teamy-input-field"
                        value="{{ old('province', $companyDetail->province ?? '') }}"></div>
                <div class="col-md-6 mb-3"><label class="teamy-label">{{ __('city') }} *</label><input type="text"
                        name="city" class="teamy-input-field" value="{{ old('city', $companyDetail->city ?? '') }}"
                        required></div>
                <div class="col-md-6 mb-3"><label class="teamy-label">{{ __('postal_code') }}</label><input
                        type="text" name="postal_code" class="teamy-input-field"
                        value="{{ old('postal_code', $companyDetail->postal_code ?? '') }}"></div>
            </div>
        </div>

        <div class="teamy-main-card">
            <div class="section-title-wrapper">
                <div class="section-icon"><i class="fa fa-clock"></i></div>
                <div class="section-heading-text">
                    <h4>{{ __('schedule_currency') }}</h4>
                    <p>{{ __('operational_hours_and_rest_days') }}</p>
                </div>
            </div>
            <div class="section-divider"></div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="teamy-label">{{ __('currency_preference') }}</label>
                    <select name="currency_preference" class="teamy-input-field">
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}"
                                {{ old('currency_preference', $companyDetail->currency_preference ?? '') == $currency->id ? 'selected' : '' }}>
                                {{ $currency->name }} ({{ $currency->symbol }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <label class="teamy-label">{{ __('weekly_off_days') }}</label>
            <div class="weekend-group mb-4">
                @php
                    $weekends = $companyDetail->weekend ?? [];
                    if (!is_array($weekends)) {
                        $weekends = json_decode($weekends, true) ?? [];
                    }
                @endphp
                @foreach ([__('sunday') => '0', __('monday') => '1', __('tuesday') => '2', __('wednesday') => '3', __('thursday') => '4', __('friday') => '5', __('saturday') => '6'] as $name => $val)
                    <input type="checkbox" id="d-{{ $val }}" name="weekend[]" value="{{ $val }}"
                        class="weekend-checkbox" {{ in_array($val, $weekends) ? 'checked' : '' }}>
                    <label for="d-{{ $val }}" class="weekend-label">{{ $name }}</label>
                @endforeach
            </div>

            <!--<div class="terms-wrapper">
                                            <input type="checkbox" name="terms_conditions" id="tc_check" value="1" required checked>
                                            <label for="tc_check" style="font-size: 14px; font-weight: 600; color: #444; cursor: pointer; margin-left: 10px;">I agree to the Terms and Conditions *</label>
                                        </div>-->

            <div class="footer-action-bar">
                <div class="text-muted small"><i class="fa fa-sync"></i> {{ __('standard_timezone') }}</div>
                <div>
                    <button type="button" class="btn-discard branch-back-btn"
                        onclick="window.history.back()">{{ __('index.back') }}</button>
                    <button type="submit"
                        class="btn btn-update">{{ $companyDetail ? __('index.update_company') : __('index.save_company') }}</button>
                </div>
            </div>
        </div>
        <!--</form>-->
    @endcanany
</div>

<script>
    // Phone Setup - Use existing dropdown without intl-tel-input
    const input = document.querySelector("#contact_number");
    const hNum = document.querySelector("#final_contact_number");
    const hCode = document.querySelector("#final_country_code");
    const countryCodeSelect = document.querySelector("#company_phone_code");
    const form = document.querySelector("#companyForm"); // This might be null if form is commented out

    // Get existing data from company and clean country code
    const existingCountryCode = "{{ $companyDetail->country_code ?? '' }}".replace('+', '');
    const existingPhoneNumber = "{{ $companyDetail->contact_number ?? '' }}";

    console.log('Initial data:', {
        existingCountryCode,
        existingPhoneNumber
    });

    // Initialize with existing data
    function initializePhoneData() {
        if (existingCountryCode && existingPhoneNumber) {
            // Set the phone number input
            input.value = existingPhoneNumber;

            // Set the country code dropdown (remove + sign for dropdown value)
            const cleanCountryCode = existingCountryCode.replace('+', '');
            countryCodeSelect.value = cleanCountryCode;

            // Check if Select2 is initialized and set value properly
            if (window.jQuery && jQuery.fn.select2) {
                jQuery(countryCodeSelect).val(cleanCountryCode).trigger('change.select2');
            }
        }

        syncPhoneData();
    }

    function syncPhoneData() {
        const code = countryCodeSelect.value;
        const cleanNumber = input.value.replace(/\D/g, '');
        console.log('Syncing - Code:', code, 'Number:', cleanNumber);

        // IMPORTANT: Send concatenated phone number to backend
        if (code && cleanNumber) {
            const concatenatedNumber = code + ' ' + cleanNumber;
            hCode.value = code; // Country code for reference
            hNum.value = concatenatedNumber; // Send concatenated to backend
            console.log('Sending concatenated:', concatenatedNumber);
        }
        updateFormProgress();
    }

    // Prevent any automatic country code changes when typing in phone field
    input.addEventListener('input', function(e) {
        // Get current phone input value before any processing
        const currentPhoneValue = input.value;
        const currentCode = countryCodeSelect.value;
        const originalCode = existingCountryCode.replace('+', '');

        console.log('=== PHONE INPUT DEBUG ===');
        console.log('Phone input value before processing:', currentPhoneValue);
        console.log('Current country code dropdown value:', currentCode);
        console.log('Original country code:', originalCode);
        console.log('Input event data:', e.data);
        console.log('Input event target value:', e.target.value);

        // Check if phone value is being truncated
        if (currentPhoneValue.length > 0 && currentPhoneValue.length < 5) {
            console.warn('Phone number appears to be truncated to:', currentPhoneValue);
        }

        // Sync phone data but DON'T let it change the country code
        const cleanNumber = currentPhoneValue.replace(/\D/g, '');
        hNum.value = cleanNumber;

        console.log('Clean phone number (digits only):', cleanNumber);

        // Force restore the original country code
        if (originalCode) {
            countryCodeSelect.value = originalCode;
            hCode.value = originalCode;

            // Update Select2 if present
            if (window.jQuery && jQuery.fn.select2) {
                jQuery(countryCodeSelect).val(originalCode).trigger('change.select2');
            }

            console.log('Forced country code to remain:', originalCode);
        }

        // Check if phone input was changed after our processing
        setTimeout(() => {
            if (input.value !== currentPhoneValue) {
                console.warn('Phone input value was changed from', currentPhoneValue, 'to', input
                    .value);
            }
        }, 10);

        console.log('=== END PHONE INPUT DEBUG ===');

        updateFormProgress();
    });

    // Also prevent any change events on the country code dropdown during phone input
    let isPhoneInput = false;
    input.addEventListener('focus', function() {
        isPhoneInput = true;
    });

    input.addEventListener('blur', function() {
        setTimeout(() => {
            isPhoneInput = false;
        }, 100);
    });

    countryCodeSelect.addEventListener('change', function(e) {
        if (!isPhoneInput) {
            // Only allow manual changes to country code, not automatic ones
            syncPhoneData();
        } else {
            console.log('Blocking automatic country code change during phone input');
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });

    // Only add form event listener if form exists
    if (form) {
        form.addEventListener('submit', function(e) {
            syncPhoneData();

            // DEBUG: Log all form data before submission
            console.log('=== FORM SUBMISSION DEBUG ===');
            console.log('Current country code dropdown value:', countryCodeSelect.value);
            console.log('Current phone input value:', input.value);
            console.log('Hidden country code field value:', hCode.value);
            console.log('Hidden phone number field value:', hNum.value);

            // Check all form inputs
            const formData = new FormData(form);
            console.log('All form data:');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            // Specifically check phone-related fields
            console.log('Phone-related fields:');
            console.log('contact_number (visible input):', document.querySelector(
                'input[name="contact_number"]').value);
            console.log('country_code (select):', document.querySelector('select[name="country_code"]').value);
            console.log('final_contact_number (hidden):', document.querySelector(
                'input[name="final_contact_number"]').value);
            console.log('final_country_code (hidden):', document.querySelector(
                'input[name="final_country_code"]').value);

            console.log('Final values to be submitted:', {
                countryCode: hCode.value,
                phoneNumber: hNum.value
            });
            console.log('=== END FORM SUBMISSION DEBUG ===');

            if (!hCode.value || hNum.value.length < 5) {
                e.preventDefault();
                alert("Please enter a valid phone number");
            }
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for Select2 to be initialized
        setTimeout(initializePhoneData, 300);
    });

    // Logo Functions
    function previewFile(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('preview-box').innerHTML =
                `<img src="${e.target.result}" id="logo-img">`;
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
            if (el && el.value.trim() !== "") filled++;
        });

        if (input.value.trim() !== "") filled++;

        const total = fields.length + 1;
        const percentage = Math.round((filled / total) * 100);

        document.getElementById('js-progress-text').innerText = percentage + "%";
        const offset = 220 - (220 * percentage / 100);
        document.getElementById('js-progress-circle').style.strokeDashoffset = offset;
    }

    document.querySelectorAll('.teamy-input-field').forEach(el => {
        el.addEventListener('input', updateFormProgress);
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



    window.onload = initializePhoneData;
</script>
