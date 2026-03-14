@extends('auth.main')

@section('title', __('Forget Password'))

@section('page-styles')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<style>
    .split-forget-container { display: flex; min-height: 100vh; background: #fff; }
    .forget-left {flex: 1; display: flex; flex-direction: column; justify-content: center;padding: 60px; max-width: 600px; overflow-y: auto;}
    .logo-area { align-self: center; margin-bottom: 25px; }
    .forget-header { text-align: center; margin-bottom: 30px; }
    .forget-header h2 {color: #057db0; margin-bottom: 8px; }
    .forget-header p { color: #64748b; font-size: 15px; line-height: 1.6; }
    .form-group { margin-bottom: 20px; text-align: left; }
    .forget-label { font-weight: 600; color: #fb8233; margin-bottom: 8px; display: block; font-size: 14px; }
    .forget-input {width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid #e2e8f0;font-size: 14px; transition: 0.3s; box-sizing: border-box;}
    .forget-input:focus { outline: none; border-color: #057db0; }
    .forget-input.error { border-color: #ef4444; }
    .forget-btn {width: 100%; padding: 14px; border-radius: 12px; border: none; background-color:#057db0; color: #fff; font-size: 16px; cursor: pointer; transition: 0.3s; margin-top: 10px;}
    .forget-btn:hover { background-color: #fb8233; transform: translateY(-1px); }
    .alert-error-custom { background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; }
    .alert-success-custom { background: #f0fdf4; border: 1px solid #dcfce7; color: #166534; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; }
    .forget-right {flex: 1.2; background-color: #057db0;display: flex; flex-direction: column; align-items: center; justify-content: center;position: relative; color: #fff; padding: 40px;}
    .slider-container { width: 100%; max-width: 500px; text-align: center; }
    .slide { display: none; }
    .slide.active { display: block; animation: fadeEffect 0.6s ease-in-out; }    
    @keyframes fadeEffect { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
    .lottie-box { height: 400px; margin-bottom: 30px; display: flex; justify-content: center; align-items: center; }
    .slide-content h3 { font-size: 26px; margin-bottom: 15px; }
    .dots-container { margin-top: 40px; display: flex; justify-content: center; gap: 8px; }
    .dot { height: 10px; width: 10px; background-color: rgba(255,255,255,0.3); border-radius: 50%; cursor: pointer; transition: 0.3s; }
    .dot.active { background-color: #fff; width: 30px; border-radius: 5px; }
    .reg-links { text-align: center; margin-top: 20px; }
    .reg-links a { color: #057db0; text-decoration: none; font-size: 14px; }
    @media (max-width: 992px) { .forget-right { display: none; } .forget-left { max-width: 100%; padding: 40px 25px; } }
</style>
@endsection

@section('auth-content')
<div class="split-forget-container">
    
    <div class="forget-left">
        <div class="logo-area">
            <a href="https://teamiy.com/">
                <img src="{{ asset('assets/images/company-logo.png') }}" style="max-width: 200px;">
            </a>
        </div>

        <div class="forget-header">
            <h2>{{ __('Forget Password') }}</h2>
            <p>{{ __('Enter your email to receive password reset link') }}</p>
        </div>

        {{-- ERRORS --}}
        @if ($errors->any())
            <div class="alert-error-custom" id="autoHideError">
                <ul style="margin:0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- SUCCESS STATUS --}}
        @if (session('status'))
            <div class="alert-success-custom">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label class="forget-label">Email Address</label>
                <input id="email" type="email" class="forget-input @error('email') error @enderror" 
                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                       placeholder="Enter your registered email">
            </div>

            <button type="submit" class="forget-btn">
                {{ __('Send Password Reset Link') }}
            </button>

            <div class="reg-links">
                <a href="{{ route('admin.login') }}">← Back to Login</a>
            </div>
        </form>
    </div>

    <div class="forget-right">
        <div class="slider-container">
            <div class="slide active">
                <div class="lottie-box">
                    <lottie-player 
                    src="{{ asset('assets/lottie/signin-data.json') }}" 
                    background="transparent" speed="1" 
                    
                    loop autoplay>
                </lottie-player>
                </div>
                <div class="slide-content">
                    <h3>Secure Reset Process</h3>
                    <p>Don't worry! It happens. We'll send you a secure link to reset your password and get you back on track.</p>
                </div>
            </div>

            <div class="slide">
                <div class="lottie-box">
                    <lottie-player 
                    src="{{ asset('assets/lottie/newstart.json') }}" 
                    background="transparent" speed="1" 
                     
                    loop autoplay>
                </lottie-player>
                </div>
                <div class="slide-content">
                    <h3>Keep Your Account Safe</h3>
                    <p>Always use a strong, unique password for your Teamiy account to ensure your company data stays protected.</p>
                </div>
            </div>

            <div class="dots-container">
                <span class="dot active" onclick="currentSlide(0)"></span>
                <span class="dot" onclick="currentSlide(1)"></span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-hide Error
    setTimeout(() => {
        const errorBox = document.getElementById('autoHideError');
        if (errorBox) {
            errorBox.style.transition = 'opacity 0.6s ease';
            errorBox.style.opacity = '0';
            setTimeout(() => errorBox.remove(), 700);
        }
    }, 15000);

    // Slider Logic
    let slideIndex = 0;
    let autoSlideTimer;

    function showSlides(n) {
        let slides = document.getElementsByClassName("slide");
        let dots = document.getElementsByClassName("dot");
        
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
        clearInterval(autoSlideTimer);
        slideIndex = n;
        showSlides(slideIndex);
        startAutoSlide();
    }

    function startAutoSlide() {
        autoSlideTimer = setInterval(() => {
            slideIndex++;
            showSlides(slideIndex);
        }, 5000);
    }

    window.onload = function() {
        startAutoSlide();
    };
</script>
@endsection