<div class="modal fade" id="statusUpdate" tabindex="-1" aria-labelledby="statusUpdateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> 
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            
            {{-- Modern Glossy Header --}}
            <div class="modal-header border-0 position-relative" style="background: linear-gradient(135deg, #057db0 0%, #046690 100%); padding: 25px;">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1;"></div>
                <div class="d-flex align-items-center position-relative" style="z-index: 2;">
                    <div style="background: rgba(255,255,255,0.2); width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px; backdrop-filter: blur(5px);">
                        <i data-feather="refresh-cw" style="color: white; width: 20px;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-0" id="statusUpdateLabel">{{ __('index.leave_request_section') }}</h5>
                        <p class="text-white-50 mb-0 small" style="font-size: 11px;">Process and update leave status</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" style="background: #f8fafc;">
                <form class="forms-sample" id="updateLeaveStatus" action="" method="post">
                    @csrf
                    @method('put')
                    
                    <div class="row g-4">
                        {{-- Status Selection --}}
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label fw-bold small text-muted text-uppercase" style="letter-spacing: 0.5px;">{{ __('index.status') }} <span class="text-danger">*</span></label>
                                <select class="form-select modern-select" id="status" name="status" style="border-radius: 10px; padding: 10px; border: 1px solid #e2e8f0; font-weight: 600;">
                                    <option value="{{ \App\Enum\LeaveStatusEnum::approved->value }}">{{ __('index.approve') }}</option>
                                    <option value="{{ \App\Enum\LeaveStatusEnum::rejected->value }}">{{ __('index.reject') }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- Admin Remark --}}
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label fw-bold small text-muted text-uppercase" style="letter-spacing: 0.5px;">{{ __('index.admin_remark') }} <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="remark" minlength="10" name="admin_remark" rows="3" placeholder="Enter reason or remarks..." style="border-radius: 10px; padding: 12px; border: 1px solid #e2e8f0;"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Previous Approvers Section (Styled) --}}
                    <div id="previousApprovers" class="mt-4" style="background: #ffffff; border-radius: 12px; border-left: 4px solid #057db0;">
                        </div>

                    {{-- Action Buttons --}}
                    <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal" style="border-radius: 12px; color: #64748b; border: 1px solid #e2e8f0;">
                            {{ __('index.cancel') }}
                        </button>
                        <button type="submit" id="submit-btn" class="btn btn-primary px-4 fw-bold" style="background: linear-gradient(135deg, #057db0 0%, #046690 100%); border: none; border-radius: 12px; min-width: 140px; box-shadow: 0 4px 15px rgba(5, 125, 176, 0.2);">
                            {{ __('index.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>