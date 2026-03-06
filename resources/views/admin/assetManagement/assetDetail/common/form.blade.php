<div class="row">
    {{-- Branch Selection --}}
    @if(!isset(auth()->user()->branch_id))
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="branch_id" class="form-label">{{ __('index.branch') }} <span style="color: red">*</span></label>
        <select class="form-select select2-input" id="branch_id" name="branch_id" required>
            <option {{!isset($assetDetail) || old('branch_id') ? 'selected': ''}} disabled>{{ __('index.select_branch') }}</option>
            @if(isset($companyDetail))
                @foreach($companyDetail->branches()->get() as $branch)
                    <option value="{{$branch->id}}" {{ (isset($assetDetail) && ($assetDetail->branch_id ) == $branch->id) ? 'selected': '' }}>
                        {{ucfirst($branch->name)}}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    @endif

    {{-- Asset Type --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="type" class="form-label">{{ __('index.type') }} <span style="color: red">*</span></label>
        <select class="form-select select2-input" id="type" name="type_id" required>
            <option value="" {{isset($assetDetail) ? '': 'selected'}} disabled>{{ __('index.select_asset_type') }}</option>
            {{-- Types will be loaded via script --}}
        </select>
    </div>

    {{-- Asset Name --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="name" class="form-label">{{ __('index.name') }} <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="name" name="name" 
               value="{{ (isset($assetDetail) ? $assetDetail->name: old('name') )}}" 
               required autocomplete="off" placeholder="{{ __('index.enter_name') }}">
    </div>

    {{-- Asset Code --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="assetCode" class="form-label">{{ __('index.asset_code') }}</label>
        <input type="text" class="form-control" id="assetCode" name="asset_code" 
               value="{{ ( isset($assetDetail) ? $assetDetail->asset_code: old('asset_code') )}}" 
               autocomplete="off" placeholder="{{ __('index.enter_asset_code') }}">
    </div>

    {{-- Serial Number --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="asset_serial_no" class="form-label">{{ __('index.asset_serial_number') }}</label>
        <input type="text" class="form-control" id="asset_serial_no" name="asset_serial_no" 
               value="{{ ( isset($assetDetail) ? $assetDetail->asset_serial_no: old('asset_serial_no') )}}" 
               autocomplete="off" placeholder="{{ __('index.enter_asset_serial_number') }}">
    </div>

    {{-- Purchased Date --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="purchased_date" class="form-label">{{ __('index.purchased_date') }} <span style="color: red">*</span></label>
        <input type="date" class="form-control" id="purchased_date" name="purchased_date" 
               value="{{ ( isset($assetDetail) ? ($assetDetail->purchased_date): old('purchased_date') )}}" 
               required autocomplete="off">
    </div>

    {{-- Warranty Available --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="warranty_available" class="form-label">{{ __('index.warranty_available') }} <span style="color: red">*</span></label>
        <select class="form-select" id="warranty_available" name="warranty_available" required>
            <option value="" {{(isset($assetDetail) && $assetDetail->warranty_available) ? '': 'selected'}} disabled>{{ __('index.select_warranty_availability') }}</option>
            @foreach(\App\Models\Asset::BOOLEAN_DATA as $key => $value)
                <option value="{{$key}}" {{ isset($assetDetail) && ($assetDetail->warranty_available ) == $key ?'selected': '' }}>
                    {{ucfirst($value)}}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Warranty End Date --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="warranty_end_date" class="form-label">{{ __('index.warranty_end_date') }}</label>
        <input type="date" class="form-control" id="warranty_end_date" name="warranty_end_date" 
               value="{{(isset($assetDetail) ? ($assetDetail->warranty_end_date): old('warranty_end_date') )}}" 
               autocomplete="off">
    </div>

    {{-- Available for Employee --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="is_available" class="form-label">{{ __('index.is_available_for_employee') }} <span style="color: red">*</span></label>
        <select class="form-select" id="is_available" name="is_available" required>
            <option value="" {{(isset($assetDetail) && $assetDetail->is_available) ? '': 'selected'}} disabled>{{ __('index.select_availability') }}</option>
            @foreach(\App\Models\Asset::BOOLEAN_DATA as $key => $value)
                <option value="{{$key}}" {{ (isset($assetDetail) && ($assetDetail->is_available ) == $key) || (!is_null(old('is_available')) && old('is_available') == $key) ?'selected': '' }}>
                    {{ucfirst($value)}}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Asset Image --}}
    <div class="col-lg-6 col-md-6 mb-4">
        <label for="image" class="form-label">{{ __('index.upload_image') }} @if(!isset($assetDetail)) <span style="color: red">*</span> @endif</label>
        <div class="image-upload-wrapper border rounded p-3 text-center">
            <input class="form-control" type="file" id="image" name="image" accept=".jpeg,.png,.jpg,.webp" {{isset($assetDetail) ? '': 'required'}}>
            
            <div class="mt-3">
                <img class="{{(isset($assetDetail) && $assetDetail->image) ? '': 'd-none'}}"
                     id="image-preview"
                     src="{{ (isset($assetDetail) && $assetDetail->image) ? asset(\App\Models\Asset::UPLOAD_PATH.$assetDetail->image) : ''}}"
                     style="object-fit: contain; max-height: 150px; border-radius: 8px;"
                     width="150">
            </div>
        </div>
    </div>

    {{-- Description --}}
    <div class="col-lg-6 mb-4">
        <label for="tinymceExample" class="form-label">{{ __('index.description') }}</label>
        <textarea class="form-control" name="note" id="tinymceExample" rows="6">{{ ( isset($assetDetail) ? $assetDetail->note: old('note') )}}</textarea>
    </div>
</div>