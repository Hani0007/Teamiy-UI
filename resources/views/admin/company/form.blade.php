<div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="name" class="form-label"> {{ __('index.company_name') }} <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="name" name="name" value="{{ ( $companyDetail ? $companyDetail->name: '' )}}" autocomplete="off" required>

        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <!-- <div class="col-lg-4 col-md-6 mb-4">
        <label for="industry_type" class="form-label">Industry Type <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="industry_type" name="industry_type" value="{{ ($companyDetail? $companyDetail->industry_type: old('industry_type') )}}" autocomplete="off" required>
        @error('industry_type') <small class="text-danger">{{ $message }}</small> @enderror
    </div> -->
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="industry_type" class="form-label">
            Industry Type <span style="color: red">*</span>
        </label>

        <select class="form-control @error('industry_type') is-invalid @enderror"
                name="industry_type"
                id="industry_type"
                required>

            <option value="">Select Industry</option>

            @foreach($industries as $industry)
                <option value="{{ $industry->id }}"
                    {{ old('industry_type', $companyDetail->industry_type ?? '') == $industry->id ? 'selected' : '' }}>
                    {{ $industry->name }}
                </option>
            @endforeach
        </select>

        @error('industry_type')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>


    <div class="col-lg-4 col-md-6 mb-4">
        <label for="no_of_employees" class="form-label">Number of Employees <span style="color: red">*</span></label>
        <input type="number" class="form-control" id="no_of_employees" name="no_of_employees" value="{{ ($companyDetail? $companyDetail->no_of_employees: old('no_of_employees') )}}" autocomplete="off" required>
        @error('no_of_employees') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="contact_number" class="form-label">Contact Number <span style="color: red">*</span></label>
        <div class="input-group phone-group" data-no-combine="true">
            <select class="form-select phone-country" id="company_phone_code" name="country_code" style="max-width: 140px"
                    data-current="{{ old('country_code', ltrim($companyDetail->country_code ?? '92', '+')) }}" ></select>
            <input type="tel" class="form-control" id="contact_number" name="contact_number"
                   value="{{ old('contact_number', $companyDetail->contact_number ?? '') }}"
                   autocomplete="off" required placeholder="">
        </div>
        @error('country_code') <small class="text-danger">{{ $message }}</small> @enderror
        @error('contact_number') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-lg-4 col-md-6 mb-4 position-relative">
        <label for="address" class="form-label">
            {{ __('index.address') }}
            <span style="color: red">*</span>
        </label>

        <input type="text"
            class="form-control"
            id="address"
            name="address"
            value="{{ ($companyDetail ? $companyDetail->address : old('address')) }}"
            autocomplete="off"
            required>

        <div id="address-suggestions" class="list-group position-absolute w-100"
            style="z-index: 1000;"></div>

        @error('address')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="country" class="form-label">Country <span style="color: red">*</span> </label>
        <select class="form-control" id="country" name="country" autocomplete="off" required>
            <option selected disabled>Select</option>
            @foreach ($countries as $country)
                <option value="{{ $country->id }}" {{ !empty($companyDetail) && $companyDetail->country === $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
            @endforeach
            {{-- <option value="1" {{ !empty($companyDetail) && $companyDetail->country === 1 ? 'selected' : '' }}>Itlay</option>
            <option value="2" {{ !empty($companyDetail) && $companyDetail->country === 2 ? 'selected' : '' }}>France</option>
            <option value="3" {{ !empty($companyDetail) && $companyDetail->country === 3 ? 'selected' : '' }}>Germany</option>
            <option value="4" {{ !empty($companyDetail) && $companyDetail->country === 4 ? 'selected' : '' }}>Switzerland</option>
            <option value="5" {{ !empty($companyDetail) && $companyDetail->country === 5 ? 'selected' : '' }}>Poland</option> --}}
        </select>
        @error('country') <small class="text-danger">{{ $message }}</small> @enderror
        {{-- <input type="text" class="form-control" id="address" name="address" value="{{ ($companyDetail? $companyDetail->address: old('address') )}}" autocomplete="off" placeholder=""> --}}
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="province" class="form-label">State/Province</label>
        <input type="text" class="form-control" id="province" name="province" value="{{ ($companyDetail? $companyDetail->province: old('province') )}}" autocomplete="off">
        @error('province') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="city" class="form-label">City</label>
        <input type="text" class="form-control" id="city" name="city" value="{{ ($companyDetail? $companyDetail->city: old('city') )}}" autocomplete="off">
        @error('city') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="postal_code" class="form-label">Postal Code</label>
        <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ ($companyDetail? $companyDetail->postal_code: old('postal_code') )}}" autocomplete="off">
        @error('postal_code') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="website" class="form-label"> {{ __('index.website_url') }}</label>
        <input type="text" class="form-control" id="website" name="website_url" value="{{ ($companyDetail? $companyDetail->website_url: old('website_url') )}}" autocomplete="off" placeholder="">
    </div>

    <div class="col-lg-12 mb-4">
        <label for="logo" class="form-label">{{ __('company_logo') }}</label>
        <div class="row">
            <div class="col-md-8">
                <input class="form-control" type="file" id="logo" name="logo" accept="image/*">
                @error('logo') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-4">
                <img id="logoPreview" 
                     src="{{ isset($companyDetail->logo) && $companyDetail->logo && file_exists(public_path(\App\Models\Company::UPLOAD_PATH . $companyDetail->logo))
                        ? asset(\App\Models\Company::UPLOAD_PATH . $companyDetail->logo)
                        : asset('uploads/company/teamiy-logo.webp') }}" 
                     alt="{{ $companyDetail->name ?? 'Company Logo' }}"  
                     style="object-fit: contain; max-height: 60px; max-width: 150px;" 
                     class="img-fluid">
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="vat_number" class="form-label">VAT Number <span style="color: #6c757d">(optional)</span></label>
        <input type="text" class="form-control" id="vat_number" name="vat_number"
               value="{{ ($companyDetail? $companyDetail->vat_number : old('vat_number')) }}"
               autocomplete="off" placeholder="Enter VAT Number">
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="company_registration" class="form-label">Company Registration <span style="color: #6c757d">(optional)</span></label>
        <input type="text" class="form-control" id="company_registration" name="company_registration"
               value="{{ ($companyDetail? $companyDetail->company_registration : old('company_registration')) }}"
               autocomplete="off" placeholder="Enter Company Registration">
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <label for="currency_preference" class="form-label">Currency Preferences</label>
        <select class="form-control" id="currency_preference" name="currency_preference" autocomplete="off">
            <option selected disabled>Select</option>
            @foreach ($currencies as $currency)
                <option value="{{ $currency->id }}" {{ !empty($companyDetail) && $companyDetail->currency_preference === $currency->id ? 'selected' : '' }}>{{ $currency->name }} {{ '(' .$currency->symbol. ')' }}</option>
            @endforeach
            {{-- <option value="1" {{ !empty($companyDetail) && $companyDetail->currency_preference === 1 ? 'selected' : '' }}>EURO</option>
            <option value="2" {{ !empty($companyDetail) && $companyDetail->currency_preference === 2 ? 'selected' : '' }}>CHF</option>
            <option value="3" {{ !empty($companyDetail) && $companyDetail->currency_preference === 3 ? 'selected' : '' }}>PLN</option>
            <option value="4" {{ !empty($companyDetail) && $companyDetail->currency_preference == 4 ? 'selected' : '' }}>USD</option> --}}
        </select>
        {{-- <input type="url" class="form-control" id="website" name="website_url" value="{{ ($companyDetail? $companyDetail->website_url: old('website_url') )}}" autocomplete="off" placeholder=""> --}}
    </div>

    <div class="col-lg-12 mb-4">
        <label for="weekend" class="form-label">Check Office Off Days</label><br>

        @php
            $weekends = !empty($companyDetail) ? $companyDetail->weekend : [];
        @endphp

        <input type="checkbox" id="Sunday" name="weekend[]" value="0" {{ !empty($weekends) && in_array('0', $weekends) ? 'checked' : '' }}>
        <label for="Sunday">Sunday</label><br>

        <input type="checkbox" id="Monday" name="weekend[]" value="1" {{ !empty($weekends) && in_array('1', $weekends) ? 'checked' : '' }}>
        <label for="Monday">Monday</label><br>

        <input type="checkbox" id="Tuesday" name="weekend[]" value="2" {{ !empty($weekends) && in_array('2', $weekends) ? 'checked' : '' }}>
        <label for="Tuesday">Tuesday</label><br>

        <input type="checkbox" id="Wednesday" name="weekend[]" value="3" {{ !empty($weekends) && in_array('3', $weekends) ? 'checked' : '' }}>
        <label for="Wednesday">Wednesday</label><br>

        <input type="checkbox" id="Thursday" name="weekend[]" value="4" {{ !empty($weekends) && in_array('4', $weekends) ? 'checked' : '' }}>
        <label for="Thursday">Thursday</label><br>

        <input type="checkbox" id="Friday" name="weekend[]" value="5" {{ !empty($weekends) && in_array('5', $weekends) ? 'checked' : '' }}>
        <label for="Friday">Friday</label><br>

        <input type="checkbox" id="Saturday" name="weekend[]" value="6" {{ !empty($weekends) && in_array('6', $weekends) ? 'checked' : '' }}>
        <label for="Saturday">Saturday</label><br>
    </div>

    

    {{-- <div class="col-lg-6 mb-4">
        <label for="weekend" class="form-label"> {{ __('index.check_office_off_days') }}  </label><br>
        @foreach(\App\Helpers\AttendanceHelper::WEEK_DAY_IN_NEPALI as $key => $value)
            <input type="checkbox" id="{{ \App\Helpers\AppHelper::ifDateInBsEnabled() ? $value['np'] : $value['en'] }}" name="weekend[]" value="{{$key}}"
            @if($companyDetail && !is_null($companyDetail->weekend))
                @foreach($companyDetail->weekend as $i => $datum)
                    {{ $datum == $key ? 'checked' : '' }}
                    @endforeach
                @endif
            >
            <label for="weekends"> {{ $value['en'] }}</label><br>
        @endforeach
    </div> --}}

    {{--    <div class="col-lg-6 mb-4">--}}
    {{--        <label for="exampleFormControlSelect1" class="form-label">Status</label>--}}
    {{--        <select class="form-select" id="exampleFormControlSelect1" name="is_active">--}}
    {{--            <option value="" {{ isset($companyDetail) ? '' :'selected' }} disabled>Select status</option>--}}
    {{--            <option value="1" @selected( old('is_active',isset($companyDetail) && $companyDetail->is_active ) == 1)>Active</option>--}}
    {{--            <option value="0" @selected( old('is_active',isset($companyDetail) && $companyDetail->is_active ) == 0)>Inactive</option>--}}
    {{--        </select>--}}
    {{--    </div>--}}

    {{-- <div class="col-lg-6 mb-4">
        <label for="upload" class="form-label">{{ __('index.upload_logo') }}</label>
        <input class="form-control" type="file" id="upload" name="logo" >
        @if(($companyDetail && $companyDetail->logo))
            <img  src="{{asset(\App\Models\Company::UPLOAD_PATH.$companyDetail->logo)}}"
                  alt=""  style="object-fit: contain" class="mt-3 ht-150 wd-150"
            >
        @endif
    </div> --}}

    <!-- <div class="col-lg-6 mb-3">
        @if(($companyDetail && $companyDetail->logo))
        <img  src="{{asset(\App\Models\Company::UPLOAD_PATH.$companyDetail->logo)}}"
                  alt=""  style="object-fit: contain" class="mt-3 ht-150 wd-150"
            >
        @endif
    </div> -->

    @canany(['create_company','edit_company'])
        <div class="col-lg-6 mb-4 text-start">
            <!-- <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="plus"></i> {{ $companyDetail ? __('index.update') : __('index.save') }} {{ __('index.company') }}</button> -->
            <button type="submit" class="btn btn-primary"> {{ $companyDetail ? __('index.update') : __('index.save') }} {{ __('index.company') }}</button>
        </div>
    @endcanany
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logoPreview');
    
    if (logoInput && logoPreview) {
        logoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
