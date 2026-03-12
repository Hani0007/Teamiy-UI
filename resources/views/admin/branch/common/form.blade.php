<div class="row">

    <!-- <div class="col-lg-4 col-md-6 mb-4">
        <label for="exampleFormControlSelect1" class="form-label">{{ __('index.company_name') }} <span style="color: red">*</span></label>
        <select class="form-select" id="exampleFormControlSelect1" name="company_id">
            <option selected value="{{ isset($company) ? $company->id : '' }}">{{ isset($company) ? $company->name : '' }}</option>
        </select>
    </div> -->
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="company_name" class="form-label">
            {{ __('index.company_name') }} <span style="color: red">*</span>
        </label>

        <!-- Disabled input showing company name -->
        <input
            type="text"
            class="form-control"
            id="company_name"
            value="{{ isset($company) ? $company->name : '' }}"
            disabled
        >

        <!-- Hidden input containing company ID -->
        <input
            type="hidden"
            name="company_id"
            value="{{ isset($company) ? $company->id : '' }}"
        >
    </div>


    <div class="col-lg-4 col-md-6 mb-4">
        <label for="name" class="form-label">{{ __('index.branch_name') }} <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="name" required name="name" value="{{ isset($branch) ? $branch->name : '' }}" autocomplete="off" placeholder="">
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="exampleFormControlSelect1" class="form-label">{{ __('index.branch_head') }}</label>
        <select class="form-select" id="exampleFormControlSelect1" name="branch_head_id">
            <option value="" {{ !isset($branch) ? 'selected' : '' }} disabled>{{ __('index.select_branch_head') }}</option>
            @foreach($users as $key => $user)
                <option value="{{ $user->id }}" {{ isset($branch) && $branch->branch_head_id  == $user->id ? 'selected' : '' }}>{{ ucfirst($user->name) }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="address" class="form-label">{{ __('index.address') }} <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="address" required name="address" value="{{ isset($branch) ? $branch->address : old('address') }}"
            autocomplete="off">

        <div id="address-suggestions" class="list-group position-absolute w-100" style="z-index: 1000;"></div>

        @error('address')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="phone" class="form-label">{{ __('index.phone_number') }} <span style="color: red">*</span></label>

        <div class="input-group phone-group">
            <select class="form-select phone-country" id="company_phone_code" style="max-width: 140px"
                data-current="{{ $company->country_code ?? '92' }}"></select>

            <input type="tel" class="form-control" id="phone" required name="phone"
                value="{{ isset($branch) ? $branch->phone : old('phone') }}" autocomplete="off" placeholder="">
            @error('contact_number') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4" style="display: none;">
        <label for="branch_location_latitude" class="form-label">{{ __('index.branch_location_latitude') }}</label>
        <input type="text" class="form-control" id="branch_location_latitude" name="branch_location_latitude" value="{{ isset($branch) ? $branch->branch_location_latitude : old('branch_location_latitude') }}" autocomplete="off" placeholder="{{ __('index.enter_branch_location_latitude') }}">
    </div>

     <div class="col-lg-4 col-md-6 mb-4" style="display: none;">
        <label for="branch_location_longitude" class="form-label">{{ __('index.branch_location_longitude') }}</label>
        <input type="text" class="form-control" id="branch_location_longitude" name="branch_location_longitude" value="{{ isset($branch) ? $branch->branch_location_longitude : old('branch_location_longitude') }}" autocomplete="off" placeholder="{{ __('index.enter_branch_location_longitude') }}">
    </div>

    <div class="col-lg-4 mb-4">
        <label for="exampleFormControlSelect1" class="form-label">{{ __('index.status') }}</label>
        <select class="form-select" id="exampleFormControlSelect1" name="is_active">
            <option value="" {{ !isset($branch) ? 'selected' : '' }} disabled>{{ __('index.select_status') }}</option>
            <option value="1" {{ isset($branch) && $branch->is_active == 1 ? 'selected' : old('is_active') }}>{{ __('index.active') }}</option>
            <option value="0" {{ isset($branch) && $branch->is_active == 0 ? 'selected' : old('is_active') }}>{{ __('index.inactive') }}</option>
        </select>
    </div>

    <!--<div class="col-lg-6 mb-4">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="plus"></i> {{ isset($branch) ? __('index.update') : __('index.create') }}</button>
        <button type="submit" class="btn btn-primary"> {{ isset($branch) ? __('index.update') : __('index.create') }}</button>
    </div>-->
</div>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.5.5/build/js/intlTelInput.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var input = document.querySelector("#phone");

    if (!input || !window.intlTelInput) return;

    var iti = window.intlTelInput(input, { nationalMode: false });
    var countrySelect = document.getElementById("country_code");
    if (countrySelect) {
        var data = iti.getCountryData();
        countrySelect.innerHTML = "";
        data.forEach(function(country) {
            var option = document.createElement("option");
            option.value = country.dialCode;
            option.text = "+" + country.dialCode;
            option.setAttribute("data-country", country.iso2);
            countrySelect.appendChild(option);
        });
        countrySelect.value = "92";
        countrySelect.addEventListener("change", function() {
            iti.setCountry(this.options[this.selectedIndex].getAttribute("data-country"));
        });
    }
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

<!-- submit handler provided globally in layouts.master -->
