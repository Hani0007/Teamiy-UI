@extends('auth.main')

@section('title', __('Step 2: Company Details'))

@section('page-styles')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<style>
    .split-reg-container { display: flex; min-height: 100vh; background: #fff; }

    /* LEFT SECTION (FORM) */
    .reg-left {
        flex: 1; display: flex; flex-direction: column; justify-content: center;
        padding: 60px; max-width: 600px; overflow-y: auto;
    }
    .logo-area { align-self: center; margin-bottom: 20px; }
    .reg-header { text-align: center; margin-bottom: 25px; }
    .reg-header h3 { color: #057db0; font-weight: 800; font-size: 22px; margin-bottom: 5px; }
    .reg-header p { color: #64748b; font-size: 14px; }

    /* Progress Dots */
    .step-progress { display: flex; justify-content: center; gap: 10px; margin-bottom: 30px; }
    .step-dot { width: 10px; height: 10px; border-radius: 50%; background: #e2e8f0; }
    .step-dot.active { background: #fb8233; width: 25px; border-radius: 5px; }
    .step-dot.completed { background: #057db0; }

    /* Form Styles */
    .form-group { margin-bottom: 18px; }
    .reg-label { font-weight: 600; color: var(--text-dark); margin-bottom: 8px; display: block; font-size: 13px; }
    .reg-label span { color: red; }
    
    .reg-input {
        width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0;
        background: #fff; font-size: 14px; transition: 0.3s; box-sizing: border-box;
    }
    .reg-input:focus { outline: none; border-color: #057db0; box-shadow: 0 0 0 4px rgba(5, 125, 176, 0.1); }
    
    .text-danger { font-size: 12px; margin-top: 4px; display: block; }

    /* Buttons */
    .btn-group-flex { display: flex; gap: 15px; margin-top: 25px; }
    .btn-reg {
        flex: 1; padding: 13px; border-radius: 12px; border: none;
        font-weight: 700; font-size: 15px; cursor: pointer; transition: 0.3s;
    }
    .btn-next { background-color: #057db0; color: #fff; }
    .btn-next:hover { background-color: #fb8233; transform: translateY(-1px); }
    .btn-prev { background-color: #f1f5f9; color: #64748b; }
    .btn-prev:hover { background-color: #e2e8f0; }

    /* RIGHT SECTION (SLIDER) */
    .reg-right {
        flex: 1.2; background-color: #057db0;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        color: #fff; padding: 40px;
    }
    .slider-wrap { width: 100%; max-width: 450px; text-align: center; }
    .lottie-box { height: 280px; margin-bottom: 25px; display: flex; justify-content: center; }
    .slide-text h3 { font-size: 24px; font-weight: 700; margin-bottom: 12px; }
    .slide-text p { font-size: 15px; opacity: 0.8; line-height: 1.6; }

    @media (max-width: 992px) { .reg-right { display: none; } .reg-left { max-width: 100%; padding: 40px 25px; } }
</style>
@endsection

@section('auth-content')
<div class="split-reg-container">
    
    <div class="reg-left">
        <div class="logo-area">
            <a href="https://teamiy.com/">
                <img src="{{ asset('assets/images/company-logo.png') }}" style="max-width: 180px;">
            </a>
        </div>

        <div class="reg-header">
            <h3>Company Details</h3>
            <p>Tell us more about your organization to personalize your experience.</p>
        </div>

        <div class="step-progress">
            <div class="step-dot completed"></div>
            <div class="step-dot active"></div>
            <div class="step-dot"></div>
        </div>

        <form>
            <div class="form-group">
                <label class="reg-label">{{ __('index.company_name') }} <span>*</span></label>
                <input class="reg-input @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name') }}" required placeholder="e.g. TechVerdi Solutions">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="no_of_employees" class="reg-label">Number of Employees <span>*</span></label>
                <input type="number" class="reg-input" id="no_of_employees" name="no_of_employees" 
                       value="{{ ($companyDetail? $companyDetail->no_of_employees: old('no_of_employees') )}}" 
                       autocomplete="off" required placeholder="e.g. 50">
                @error('no_of_employees') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="contact_number" class="reg-label">Contact Number <span>*</span></label>
                <input type="number" class="reg-input" id="contact_number" name="contact_number" 
                       value="{{ ($companyDetail? $companyDetail->contact_number: old('contact_number') )}}" 
                       autocomplete="off" required placeholder="e.g. 1234567890">
                @error('contact_number') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="btn-group-flex">
                <button type="button" class="btn-reg btn-prev" id="prevBtn">Previous</button>
                <button type="button" class="btn-reg btn-next" id="nextBtn1">Next Step</button>
            </div>
        </form>
    </div>

    <div class="reg-right">
        <div class="slider-wrap">
            <div class="lottie-box">
                <lottie-player src="https://assets3.lottiefiles.com/packages/lf20_q5pk6p1k.json" background="transparent" speed="1" style="width: 300px;" loop autoplay></lottie-player>
            </div>
            <div class="slide-text">
                <h3>Build Your Workspace</h3>
                <p>Provide your company details to set up a customized environment for your team's collaboration and growth.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Aapke Step 2 ke buttons ka logic yahan handle hoga
    document.getElementById('prevBtn').addEventListener('click', function() {
        // Aapka previous step par janey ka logic
    });

    document.getElementById('nextBtn1').addEventListener('click', function() {
        // Aapka next step par janey ka logic
    });

    window.onload = function() {
        if(typeof feather !== 'undefined') { feather.replace(); }
    };
</script>
@endsection