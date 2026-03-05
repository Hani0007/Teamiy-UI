<div class="row">
    <div class="mb-3">
        <label class="form-label">{{ __('index.company_name') }} <span style="color: red">*</span></label>
        <input class="form-control @error('name') is-invalid @enderror"
            name="name" value="{{ old('name') }}" required>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    {{-- <div class="mb-3">
        <label for="industry_type" class="form-label">Industry Type <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="industry_type" name="industry_type" value="{{ ($companyDetail? $companyDetail->industry_type: old('industry_type') )}}" autocomplete="off" required>
        @error('industry_type') <small class="text-danger">{{ $message }}</small> @enderror
    </div> --}}

    <div class="mb-3">
        <label for="no_of_employees" class="form-label">Number of Employees <span style="color: red">*</span></label>
        <input type="number" class="form-control" id="no_of_employees" name="no_of_employees" value="{{ ($companyDetail? $companyDetail->no_of_employees: old('no_of_employees') )}}" autocomplete="off" required>
        @error('no_of_employees') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label for="contact_number" class="form-label">Contact Number <span style="color: red">*</span></label>
        <input type="number" class="form-control" id="contact_number" name="contact_number" value="{{ ($companyDetail? $companyDetail->contact_number: old('contact_number') )}}" autocomplete="off" required>
        @error('contact_number') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    {{-- <div class="mb-3">
        <label for="country" class="form-label">Country <span style="color: red">*</span> </label>
        <select class="form-control" id="country" name="country" autocomplete="off" required>
            <option selected disabled>Select</option>
            <option value="1" {{ !empty($companyDetail) && $companyDetail->country === 1 ? 'selected' : '' }}>Itlay</option>
            <option value="2" {{ !empty($companyDetail) && $companyDetail->country === 2 ? 'selected' : '' }}>France</option>
            <option value="3" {{ !empty($companyDetail) && $companyDetail->country === 3 ? 'selected' : '' }}>Germany</option>
            <option value="4" {{ !empty($companyDetail) && $companyDetail->country === 4 ? 'selected' : '' }}>Switzerland</option>
            <option value="5" {{ !empty($companyDetail) && $companyDetail->country === 5 ? 'selected' : '' }}>Poland</option>
        </select>
        @error('country') <small class="text-danger">{{ $message }}</small> @enderror
    </div> --}}

    {{-- <div class="mb-3">
        <label for="province" class="form-label">State/Province <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="province" name="province" value="{{ ($companyDetail? $companyDetail->province: old('province') )}}" autocomplete="off" required>
        @error('province') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label for="city" class="form-label">City <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="city" name="city" value="{{ ($companyDetail? $companyDetail->city: old('city') )}}" autocomplete="off" required>
        @error('city') <small class="text-danger">{{ $message }}</small> @enderror
    </div> --}}

    {{-- <div class="mb-3">
        <label for="postal_code" class="form-label">Postal Code <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ ($companyDetail? $companyDetail->postal_code: old('postal_code') )}}" autocomplete="off" required>
        @error('postal_code') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label for="address" class="form-label"> {{ __('index.address') }} <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="address" name="address" value="{{ ($companyDetail? $companyDetail->address: old('address') )}}" autocomplete="off" required>
        @error('address') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label for="website" class="form-label"> {{ __('index.website_url') }}</label>
        <input type="url" class="form-control" id="website" name="website_url" value="{{ ($companyDetail? $companyDetail->website_url: old('website_url') )}}" autocomplete="off" placeholder="">
    </div>

    <div class="mb-3">
        <label for="currency_preference" class="form-label">Curreny Preference</label>
        <select class="form-control" id="currency_preference" name="currency_preference" autocomplete="off">
            <option selected disabled>Select</option>
            <option value="1" {{ !empty($companyDetail) && $companyDetail->currency_preference === 1 ? 'selected' : '' }}>EURO</option>
            <option value="2" {{ !empty($companyDetail) && $companyDetail->currency_preference === 2 ? 'selected' : '' }}>CHF</option>
            <option value="3" {{ !empty($companyDetail) && $companyDetail->currency_preference === 3 ? 'selected' : '' }}>PLN</option>
        </select>
    </div> --}}


    {{-- @canany(['create_company','edit_company'])
        <div class="col-lg-6 mb-4 text-start">
            <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="plus"></i> {{ $companyDetail ? __('index.update') : __('index.save') }} {{ __('index.company') }}</button>
        </div>
    @endcanany --}}
    <div class="col-md-6">
        <button type="button" class="btn btn-secondary" id="prevBtn">Previous</button>
        <button type="button" class="btn btn-primary" id="nextBtn1">Next</button>
    </div>

</div>
