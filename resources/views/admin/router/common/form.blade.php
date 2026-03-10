<div class="row px-2">
    @if(!isset(auth()->user()->branch_id))
    <div class="col-lg-6 mb-4">
        <label for="branch_id" class="form-label fw-bold text-secondary">@lang('index.branch') <span class="text-danger">*</span></label>
        <select class="form-select border-2" id="branch_id" name="branch_id" required>
            <option value="" {{ isset($routerDetail) ? '' : 'selected' }} disabled>@lang('index.select_branch')</option>
            @foreach($companyDetail->branches()->get() as $branch)
                <option value="{{ $branch->id }}" {{ (isset($routerDetail) && $routerDetail->branch_id == $branch->id) ? 'selected' : '' }}>
                    {{ ucfirst($branch->name) }}
                </option>
            @endforeach
        </select>
    </div>
    @endif

    <div class="{{ !isset(auth()->user()->branch_id) ? 'col-lg-6' : 'col-lg-12' }} mb-4">
        <label for="router_ssid" class="form-label fw-bold text-secondary">@lang('index.router_bssid') <span class="text-danger">*</span></label>
        <input type="text" class="form-control border-2" id="router_ssid" required name="router_ssid" 
               value="{{ $routerDetail->router_ssid ?? old('router_ssid') }}" 
               autocomplete="off" placeholder="e.g. 00:00:00:00:00">
        <small class="text-muted"><i class="fa fa-info-circle me-1"></i> Enter the physical address (MAC) of the router.</small>
    </div>
</div>