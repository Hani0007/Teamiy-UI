<div class="modal fade" id="addslider" tabindex="-1" aria-labelledby="addsliderLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            
            {{-- Modern Glossy Header --}}
            <div class="modal-header border-0 position-relative" style="background: linear-gradient(135deg, #057db0 0%, #046690 100%); padding: 25px;">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1;"></div>
                <div class="d-flex align-items-center position-relative" style="z-index: 2;">
                    <div style="background: rgba(255,255,255,0.2); width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px; backdrop-filter: blur(5px);">
                        <i data-feather="clock" style="color: white; width: 22px;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-0" id="addsliderLabel">Time Leave Request
</h5>
                        <p class="text-white-50 mb-0 small" style="font-size: 11px;">Reviewing time-based request</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" style="background: #f8fafc;">
                <div class="row g-3">
                    
                    {{-- Referred By Section --}}
                    <div class="col-12">
                        <div class="p-3 shadow-sm" style="background: white; border-radius: 15px; border: 1px solid #e2e8f0;">
                            <div class="d-flex align-items-center mb-2">
                                <div style="background: rgba(5, 125, 176, 0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                    <i data-feather="user" style="color: #057db0; width: 16px;"></i>
                                </div>
                                <label class="text-muted small fw-bold mb-0" style="text-transform: uppercase; letter-spacing: 0.5px; font-size: 10px;">{{ __('index.referred_by') }}</label>
                            </div>
                            <p class="mb-0 text-dark fw-600 ps-1" id="referral" style="font-size: 14px; min-height: 20px; font-style: italic;"></p>
                        </div>
                    </div>

                    {{-- Leave Reason Section --}}
                    <div class="col-12">
                        <div class="p-3 shadow-sm" style="background: white; border-radius: 15px; border: 1px solid #e2e8f0;">
                            <div class="d-flex align-items-center mb-2">
                                <div style="background: rgba(5, 125, 176, 0.1); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                    <i data-feather="help-circle" style="color: #057db0; width: 16px;"></i>
                                </div>
                                <label class="text-muted small fw-bold mb-0" style="text-transform: uppercase; letter-spacing: 0.5px; font-size: 10px;">{{ __('index.leave_reason') }}</label>
                            </div>
                            <div class="p-2 ms-1" style="border-left: 3px solid #057db0; background: #fcfdfe;">
                                <p class="mb-0 text-secondary fst-italic" id="description" style="font-size: 13px; line-height: 1.5;"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Admin Remark Section --}}
                    <div class="col-12">
                        <div class="p-3" style="background: #eff6ff; border-radius: 15px; border: 1px dashed #bfdbfe;">
                            <div class="d-flex align-items-center mb-2">
                                <div style="background: #fff; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                    <i data-feather="message-square" style="color: #057db0; width: 16px;"></i>
                                </div>
                                <label class="text-primary small fw-bold mb-0" style="text-transform: uppercase; letter-spacing: 0.5px; font-size: 10px;">{{ __('index.admin_remark') }}</label>
                            </div>
                            <p class="mb-0 text-dark ps-1" id="adminRemark" style="font-size: 13px; min-height: 20px; font-style: italic;"></p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer border-0 p-4 pt-0" style="background: #f8fafc;">
                <button type="button" class="btn  py-2 fw-bold" data-bs-dismiss="modal" 
                        style="background: #fff; color: #64748b; border: 1px solid #e2e8f0; border-radius: 12px; transition: all 0.3s;">
                    {{ __('index.close') }}
                </button>
            </div>
        </div>
    </div>
</div>