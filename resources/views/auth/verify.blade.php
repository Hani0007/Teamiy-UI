@extends('auth.main')

@section('title', __('verify_account'))

@section('page-styles')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<style>
    .split-verify-container { display: flex; min-height: 100vh; background: #fff; }
    .verify-left {flex: 1; display: flex; flex-direction: column; justify-content: center;padding: 60px; max-width: 600px; overflow-y: auto;}
    .logo-area { align-self: center; margin-bottom: 25px; }
    .verify-header { text-align: center; margin-bottom: 30px; }
    .verify-header h3 { color: #057db0;font-size: 24px; margin-bottom: 10px; }
    .verify-header p { color: #64748b; font-size: 15px; }
    .email-display-card {background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;padding: 15px; margin-bottom: 25px; text-align: center;}
    .email-label { color: #fb8233; font-weight: 700; font-size: 12px; text-transform: uppercase; margin-bottom: 5px; display: block; }
    .email-value { color: var(--text-dark); font-weight: 600; font-size: 15px; }

    /* OTP Input Style */
    .verify-input {
        width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;
        background: #fff; font-size: 24px; font-weight: 700; letter-spacing: 8px;
        text-align: center; transition: 0.3s; box-sizing: border-box; color: #057db0;
    }
    .verify-input:focus { outline: none; border-color: #057db0; box-shadow: 0 0 0 4px rgba(5, 125, 176, 0.1); }
    .verify-input::placeholder { letter-spacing: normal; font-size: 16px; font-weight: 400; opacity: 0.5; }

    .verify-btn {
        width: 100%; padding: 14px; border-radius: 12px; border: none;
        background-color: #057db0; color: #fff; font-weight: 700;
        font-size: 16px; cursor: pointer; transition: 0.3s; margin-top: 20px;
    }
    .verify-btn:hover { background-color: #fb8233; transform: translateY(-1px); }

    /* Timer & Resend Section */
    .resend-box { margin-top: 20px; text-align: center; padding: 15px; border-radius: 12px; background: #f0f9ff; }
    #expiry-text { font-size: 13px; color: #0369a1; font-weight: 600; margin-bottom: 8px; }
    #resend-btn { 
        background: none; border: none; color: #057db0; 
        font-weight: 700; cursor: pointer; text-decoration: underline; font-size: 14px;
    }
    #resend-btn:disabled { color: #94a3b8; cursor: not-allowed; text-decoration: none; }

    .back-link { text-align: center; margin-top: 25px; }
    .back-link a { color: #64748b; font-size: 14px; text-decoration: none; font-weight: 600; }
    .back-link a:hover { color: #057db0; }

    /* RIGHT SECTION (SLIDER) */
    .verify-right {
        flex: 1.2; background-color: #057db0;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        position: relative; color: #fff; padding: 40px;
    }
    .slider-container { width: 100%; max-width: 500px; text-align: center; }
    .slide { display: none; }
    .slide.active { display: block; animation: fadeEffect 0.6s ease-in-out; }
    @keyframes fadeEffect { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }

    .lottie-box { height: 320px; margin-bottom: 30px; display: flex; justify-content: center; }
    .slide-content h3 { font-size: 26px; font-weight: 700; margin-bottom: 15px; }
    .slide-content p { font-size: 16px; opacity: 0.8; line-height: 1.6; }

    .dots-container { margin-top: 40px; display: flex; justify-content: center; gap: 8px; }
    .dot { height: 10px; width: 10px; background-color: rgba(255,255,255,0.3); border-radius: 50%; cursor: pointer; }
    .dot.active { background-color: #fff; width: 30px; border-radius: 5px; }

    @media (max-width: 992px) { .verify-right { display: none; } .verify-left { max-width: 100%; padding: 40px 25px; } }
</style>
@endsection

@section('auth-content')
<div class="split-verify-container">
    
    <div class="verify-left">
        <div class="logo-area">
            <a href="https://teamiy.com/">
                <img src="{{ asset('assets/images/company-logo.png') }}" style="max-width: 200px;">
            </a>
        </div>

        <div class="verify-header">
            <h3>{{ __('registration_success') }}</h3>
            <p>{{ __('verify_account') }}</p>
        </div>

        @include('admin.section.flash_message')

        <div class="email-display-card">
            <span class="email-label">Verification Code Sent To</span>
            <span class="email-value">{{ $email ?? old('email') }}</span>
        </div>

        <form method="POST" action="{{ route('admin.verify.perform') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
            <div class="form-group">
                <input type="text" name="otp" class="verify-input" maxlength="6" pattern="[0-9]{6}" placeholder="Enter 6-digit code" required autofocus>
            </div>
            <button type="submit" class="verify-btn">Verify Account</button>
        </form>

        <div class="resend-box" id="resend-block" data-expiry="{{ $expiresMs ?? '' }}">
            <div id="expiry-text">Checking code status...</div>
            <form method="POST" action="{{ route('admin.verify.resend') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
                <button type="submit" id="resend-btn" disabled>Resend Verification Code</button>
            </form>
        </div>

        <div class="back-link">
            <a href="{{ route('admin.login') }}">← Back to Login</a>
        </div>
    </div>

    <div class="verify-right">
        <div class="slider-container">
            <div class="slide active">
                <div class="lottie-box">
                    <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_m6cu9t9c.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></lottie-player>
                </div>
                <div class="slide-content">
                    <h3>One Step Away!</h3>
                    <p>Verify your email to activate your account and start managing your team efficiently.</p>
                </div>
            </div>

            <div class="slide">
                <div class="lottie-box">
                    <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_ai769uun.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></lottie-player>
                </div>
                <div class="slide-content">
                    <h3>Secure Onboarding</h3>
                    <p>We take security seriously. Email verification ensures that only you can access your company data.</p>
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
    // TIMER LOGIC (Aapka original script)
    (function () {
        var block = document.getElementById('resend-block');
        var btn = document.getElementById('resend-btn');
        var text = document.getElementById('expiry-text');
        if (!block || !btn || !text) return;
        var expiryMsAttr = block.getAttribute('data-expiry');
        var expiryMs = expiryMsAttr ? parseInt(expiryMsAttr, 10) : null;
        
        function fmt(ms) {
            var total = Math.max(0, Math.floor(ms / 1000));
            var m = Math.floor(total / 60);
            var s = total % 60;
            return (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
        }
        
        function tick() {
            var now = Date.now();
            if (!expiryMs) {
                btn.disabled = false;
                text.textContent = 'Code expired. You can resend.';
                return;
            }
            var rem = expiryMs - now;
            if (rem <= 0) {
                btn.disabled = false;
                text.textContent = 'Code expired. You can resend.';
            } else {
                btn.disabled = true;
                text.textContent = 'Code expires in ' + fmt(rem);
                setTimeout(tick, 1000);
            }
        }
        tick();
    })();

    // SLIDER LOGIC
    let slideIndex = 0;
    let slides = document.getElementsByClassName("slide");
    let dots = document.getElementsByClassName("dot");

    function showSlides(n) {
        if (n >= slides.length) slideIndex = 0;
        if (n < 0) slideIndex = slides.length - 1;
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
</script>
@endsection