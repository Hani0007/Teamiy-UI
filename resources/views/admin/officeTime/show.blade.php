<div class="modal fade" id="addslider" tabindex="-1" aria-labelledby="addslider" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 10px;">
            
            {{-- Modal Header: Clean & Standard with Fixed Close Button --}}
            <div class="modal-header border-bottom py-3 px-4 d-flex align-items-center justify-content-between " style="background:#057DB0;color:white">
                <h6 class="modal-title fw-bold text-white mb-0" id="exampleModalLabel">
                    <i class="link-icon me-2" data-feather="clock" style="width: 16px; height: 16px; vertical-align: middle;"></i>
                    {{ __('index.office_time_details') }}
                </h6>
                {{-- Fixed Close Button --}}
                <button type="button" class="btn-close shadow-none text-white" data-bs-dismiss="modal" aria-label="Close"         style="filter: invert(1); padding: 0.5rem; margin: -0.5rem -0.5rem -0.5rem auto;">
></button>
            </div>

            <div class="modal-body p-4">
                
                {{-- Shift Name: Theme Based Typography --}}
                <div class="mb-4">
                    <small class="text-muted d-block mb-1 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                        {{ __('index.shift') }}
                    </small>
                    <div class="shift fw-bold fs-5" style="color:#fb8233;"></div>
                </div>

                <div class="row g-3">
                    {{-- Opening Time Card --}}
                    <div class="col-6">
                        <div class="p-3 border rounded bg-light">
                            <small class="text-muted d-block mb-1" style="font-size: 11px;">{{ __('index.opening_time') }}</small>
                            <span class="opening_time fw-bold text-dark d-block"></span>
                        </div>
                    </div>
                    {{-- Closing Time Card --}}
                    <div class="col-6">
                        <div class="p-3 border rounded bg-light">
                            <small class="text-muted d-block mb-1" style="font-size: 11px;">{{ __('index.closing_time') }}</small>
                            <span class="closing_time fw-bold text-dark d-block"></span>
                        </div>
                    </div>

                    {{-- Attendance Rules: Standard Theme Table --}}
                    <div class="col-12 mt-4">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" style="font-size: 13px; border-color: #e9ecef;">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-2 ps-3 text-muted fw-bold border-0">Attendance Rules</th>
                                        <th class="py-2 text-center text-muted fw-bold border-0">Before (Min)</th>
                                        <th class="py-2 text-center text-muted fw-bold border-0">After (Min)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="ps-3 fw-medium text-dark py-2">Check-In</td>
                                        <td class="text-center checkin_before py-2"></td>
                                        <td class="text-center checkin_after py-2"></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-3 fw-medium text-dark py-2">Check-Out</td>
                                        <td class="text-center checkout_before py-2"></td>
                                        <td class="text-center checkout_after py-2"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                <button type="button" class="btn branch-back-btn shadow-sm w-100" data-bs-dismiss="modal">
                    {{ __('index.close') }}
                </button>
            </div>
        </div>
    </div>
</div>