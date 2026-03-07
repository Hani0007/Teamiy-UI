@extends('auth.main')

@section('title', __('Reset Password'))

@section('page-styles')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<style>
    :root {
        --primary-blue: #057db0;
        --accent-orange: #fb8233;
        --text-dark: #1e293b;
    }

    body, html { margin: 0; padding: 0; font-family: 'Inter', sans-serif; height: 100%; overflow: hidden; }
    .split-reset-container { display: flex; min-height: 100vh; background: #fff; }

    /* LEFT SECTION (FORM) */
    .reset-left {
        flex: 1; display: flex; flex-direction: column; justify-content: center;
        padding: 60px; max-width: 600px; overflow-y: auto;
    }
    .logo-area { align-self: center; margin-bottom: 25px; }
    .reset-header { text-align: center; margin-bottom: 30px; }
    .reset-header h3 { color: var(--primary-blue); font-weight: 800; font-size: 24px; margin-bottom: 10px; }
    .reset-header p { color: #64748b; font-size: 15px; }

    /* Form Styles from Login Design */
    .form-group { margin-bottom: 20px; position: relative; }
    .reset-label { font-weight: 600; color: var(--accent-orange); margin-bottom: 8px; display: block; font-size: 14px; }
    .reset-input {
        width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid #e2e8f0;
        background: #fff; font-size: 14px; transition: 0.3s; box-sizing: border-box;
    }
    .reset-input:focus { outline: none; border-color: var(--primary-blue); box-shadow: 0 0 0 4px rgba(5, 125, 176, 0.1); }

    /* Password Rules Styling */
    .reset-password-rules { 
        background: #f8fafc; border: 1px dashed #cbd5e1; color: #64748b; 
        font-size: 13px; border-radius: 12px; padding: 12px; margin-bottom: 20px; 
    }
    .rule-valid { color: #10b981 !important; font-weight: 700; }

    .password-wrapper { position: relative; }
    .password-toggle { 
        position: absolute; right: 15px; top: 45px; cursor: pointer; color: #94a3b8; width: 20px; z-index: 10;
    }
    .password-toggle.active { color: var(--primary-blue); }

    .reset-btn {
        width: 100%; padding: 14px; border-radius: 12px; border: none;
        background-color: var(--primary-blue); color: #fff; font-weight: 700;
        font-size: 16px; cursor: pointer; transition: 0.3s; margin-top: 10px;
    }
    .reset-btn:hover { background-color: var(--accent-orange); transform: translateY(-1px); }

    /* RIGHT SECTION (SLIDER) */
    .reset-right {
        flex: 1.2; background-color: var(--primary-blue);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        position: relative; color: #fff; padding: 40px;
    }
    .slider-container { width: 100%; max-width: 500px; text-align: center; position: relative; }
    .slide { display: none; }
    .slide.active { display: block; animation: fadeEffect 0.6s ease-in-out; }
    
    @keyframes fadeEffect { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }

    .lottie-box { height: 320px; margin-bottom: 30px; display: flex; justify-content: center; align-items: center; }
    .slide-content h3 { font-size: 26px; font-weight: 700; margin-bottom: 15px; }
    .slide-content p { font-size: 16px; opacity: 0.8; line-height: 1.6; }

    .dots-container { margin-top: 40px; display: flex; justify-content: center; gap: 8px; }
    .dot { height: 10px; width: 10px; background-color: rgba(255,255,255,0.3); border-radius: 50%; cursor: pointer; transition: 0.3s; }
    .dot.active { background-color: #fff; width: 30px; border-radius: 5px; }

    @media (max-width: 992px) { .reset-right { display: none; } .reset-left { max-width: 100%; padding: 40px 25px; } }
</style>
@endsection

@section('auth-content')
<div class="split-reset-container">
    
    <div class="reset-left">
        <div class="logo-area">
            <a href="https://teamiy.com/">
                <img src="{{ asset('assets/images/company-logo.png') }}" style="max-width: 200px;">
            </a>
        </div>

        <div class="reset-header">
            <h3>{{ __('Reset Password') }}</h3>
            <p>Create a strong new password to secure your account.</p>
        </div>

        @if ($errors->any())
            <div style="border-radius:12px; font-size: 14px; background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; padding: 15px; margin-bottom: 20px;">
                <ul style="margin:0; padding-left: 20px;">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        {{-- PASSWORD RULES --}}
        <div class="reset-password-rules" id="passwordRules">
            <div id="ruleLength">• Minimum 8 characters</div>
            <div id="ruleMatch">• Passwords must match</div>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label class="reset-label">Email Address</label>
                <input type="email" name="email" class="reset-input" value="{{ $email ?? old('email') }}" required readonly>
            </div>

            <div class="form-group">
                <label class="reset-label">New Password*</label>
                <input type="password" id="newPassword" name="password" class="reset-input" placeholder="Enter new password" required autocomplete="new-password">
                <i data-feather="eye" class="password-toggle" id="newPassIcon" onclick="toggleNewPassword()"></i>
            </div>

            <div class="form-group">
                <label class="reset-label">Confirm Password*</label>
                <input type="password" id="confirmPassword" name="password_confirmation" class="reset-input" placeholder="Confirm new password" required autocomplete="new-password">
                <i data-feather="eye" class="password-toggle" id="confirmPassIcon" onclick="toggleConfirmPassword()"></i>
            </div>

            <button type="submit" class="reset-btn">{{ __('Reset Password') }}</button>

            <div class="text-center mt-4">
                <p style="font-size: 14px; color: #64748b;">Remember your password? 
                    <a href="{{ route('admin.login') }}" style="color: #057db0; font-weight:700; text-decoration:none;">Back to Login</a>
                </p>
            </div>
        </form>
    </div>

    <div class="reset-right">
        <div class="slider-container">
            <div class="slide active">
                <div class="lottie-box">
                    <lottie-player src="https://assets3.lottiefiles.com/packages/lf20_m6cu96.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></lottie-player>
                </div>
                <div class="slide-content">
                    <h3>Secure Your Account</h3>
                    <p>Updating your password regularly helps keep your employee data and company records safe from unauthorized access.</p>
                </div>
            </div>

            <div class="slide">
                <div class="lottie-box">
                    <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_G9pWpx.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></lottie-player>
                </div>
                <div class="slide-content">
                    <h3>Almost There!</h3>
                    <p>Just one more step to regain access to your comprehensive HR management dashboard.</p>
                </div>
            </div>

            <div class="dots-container">
                <span class="dot active" onclick="currentSlide(0)"></span>
                <span class="dot" onclick="currentSlide(1)"></span>
            </div>
        </div>
    </div>
</div>

<script>
    // Password Toggle Logic
    function toggleNewPassword(){
        const p = document.getElementById('newPassword');
        const i = document.getElementById('newPassIcon');
        if (p.type === 'password') {
            p.type = 'text';
            i.setAttribute('data-feather', 'eye-off');
        } else {
            p.type = 'password';
            i.setAttribute('data-feather', 'eye');
        }
        feather.replace();
    }

    function toggleConfirmPassword(){
        const p = document.getElementById('confirmPassword');
        const i = document.getElementById('confirmPassIcon');
        if (p.type === 'password') {
            p.type = 'text';
            i.setAttribute('data-feather', 'eye-off');
        } else {
            p.type = 'password';
            i.setAttribute('data-feather', 'eye');
        }
        feather.replace();
    }

    // Live Password Validation
    const newPass = document.getElementById('newPassword');
    const confirmPass = document.getElementById('confirmPassword');
    const ruleLength = document.getElementById('ruleLength');
    const ruleMatch = document.getElementById('ruleMatch');

    function validatePassword(){
        if(newPass.value.length >= 8) ruleLength.classList.add('rule-valid');
        else ruleLength.classList.remove('rule-valid');

        if(confirmPass.value !== '' && newPass.value === confirmPass.value) ruleMatch.classList.add('rule-valid');
        else ruleMatch.classList.remove('rule-valid');
    }

    newPass.addEventListener('input', validatePassword);
    confirmPass.addEventListener('input', validatePassword);

    // Slider Logic
    let slideIndex = 0;
    let slides = document.getElementsByClassName("slide");
    let dots = document.getElementsByClassName("dot");

    function showSlides(n) {
        if (n >= slides.length) { slideIndex = 0; }
        if (n < 0) { slideIndex = slides.length - 1; }
        for (let i = 0; i < slides.length; i++) {
            slides[i].classList.remove("active");
            dots[i].classList.remove("active");
        }
        slides[slideIndex].classList.add("active");
        dots[slideIndex].classList.add("active");
    }

    function currentSlide(n) {
        slideIndex = n;
        showSlides(slideIndex);
    }

    setInterval(() => {
        slideIndex++;
        showSlides(slideIndex);
    }, 5000);

    window.onload = function() {
        if(typeof feather !== 'undefined') { feather.replace(); }
    };
</script>
@endsection