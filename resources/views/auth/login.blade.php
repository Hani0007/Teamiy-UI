@extends('auth.main')

@section('title', __('auth.login'))

@section('page-styles')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<style>
    .split-login-container { display: flex; min-height: 100vh; background: #fff; }
    .login-left {flex: 1; display: flex; flex-direction: column; justify-content: center;padding: 60px; max-width: 600px; overflow-y: auto;}
    .logo-area { align-self: center; margin-bottom: 25px; }
    .login-header { text-align: center; margin-bottom: 30px; }
    .login-header p { color: #64748b; font-size: 15px; line-height: 1.6; }
    .social-login-group { display: flex; flex-direction: column; gap: 12px; margin-bottom: 25px; }
    .btn-social {display: flex; align-items: center; justify-content: center; gap: 10px;padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;background: #f8fafc; text-decoration: none; color: var(--text-dark);font-weight: 500; font-size: 14px; transition: 0.3s;}
    .btn-social:hover { background: #f1f5f9; border-color: #cbd5e1; color: #fb8233; }
    .btn-social img { width: 20px; }
    .divider { display: flex; align-items: center; text-align: center; margin: 25px 0; color: #94a3b8; font-size: 13px; }
    .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid #e2e8f0; }
    .divider:not(:empty)::before { margin-right: .5em; }
    .divider:not(:empty)::after { margin-left: .5em; }
    .form-group { margin-bottom: 20px; }
    .login-label { font-weight: 600; color: #fb8233; margin-bottom: 8px; display: block; font-size: 14px; }
    .login-input {width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid #e2e8f0;background: #fff; font-size: 14px; transition: 0.3s; box-sizing: border-box;}
    .login-input:focus { outline: none; border-color: #057db0; box-shadow: 0 0 0 4px rgba(5, 125, 176, 0.1); }
    .password-wrapper { position: relative; }
    .password-toggle { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; width: 20px; }
    .login-btn {width: 100%; padding: 14px; border-radius: 12px; border: none; background-color: #057db0; color: #fff; font-weight: 700;font-size: 16px; cursor: pointer; transition: 0.3s; margin-top: 10px;}
    .login-btn:hover { background-color: #fb8233; transform: translateY(-1px); }
    .login-right {flex: 1.2; background-color: #057db0;display: flex; flex-direction: column; align-items: center; justify-content: center;position: relative; color: #fff; padding: 40px;}
    .slider-container { width: 100%; max-width: 500px; text-align: center; position: relative; }
    .slide { display: none; }
    .slide.active { display: block; animation: fadeEffect 0.6s ease-in-out; }
    @keyframes fadeEffect { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
    .lottie-box { height: 400px; margin-bottom: 30px; display: flex; justify-content: center; align-items: center; }
    .slide-content h3 { font-size: 26px;margin-bottom: 15px; }
    /*.slide-content p { font-size: 16px; opacity: 0.8; line-height: 1.6; }*/
    .dots-container { margin-top: 40px; display: flex; justify-content: center; gap: 8px; }
    .dot {height: 12px;width: 12px;background-color: rgba(255,255,255,0.3);border-radius: 50%;cursor: pointer;display: inline-block;transition: all 0.3s ease;margin: 0 5px; position: relative;z-index: 999; /* Taake koi overlay isay block na kare */}
    .dot.active {background-color: #fff;width: 30px;border-radius: 5px;}
    @media (max-width: 992px) { .login-right { display: none; } .login-left { max-width: 100%; padding: 40px 25px; } }
</style>
@endsection

@section('auth-content')
<div class="split-login-container">
    
    <div class="login-left">
        <div class="logo-area">
            <a href="https://teamiy.com/">
                <img src="{{ asset('assets/images/company-logo.png') }}" style="max-width: 200px;">
            </a>
        </div>

        <div class="login-header">
            <p>Ready to manage your workforce more efficiently? Log in to your account.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="border-radius:12px; font-size: 14px; background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; padding: 15px; margin-bottom: 20px;">
                <ul class="mb-0">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        <div class="social-login-group">
            <a href="{{ route('social.login', 'google') }}" class="btn-social">
                <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png" alt="Google">
                Login with Google
            </a>
            <a href="{{ route('social.login', 'facebook') }}" class="btn-social">
                <img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook">
                Login with Facebook
            </a>
        </div>

        <div class="divider">Or login manually with email</div>

        <form method="POST" action="{{ route('admin.login.process') }}" novalidate>
            @csrf
            <input type="hidden" name="user_type" value="admin">

            <div class="form-group">
                <label class="login-label">Email or Username*</label>
                <input type="email" name="email" class="login-input" placeholder="Enter your email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label class="login-label">Password*</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" class="login-input" placeholder="Enter your password" required>
                    <i data-feather="eye" class="password-toggle" id="toggleIcon" onclick="togglePassword()"></i>
                </div>
                <div class="text-end mt-2">
                    <a href="{{ route('password.request') }}" style="color: #057db0; text-decoration:none; font-size:13px; font-weight:600;">Forgot Password?</a>
                </div>
            </div>

            <button type="submit" class="login-btn">Login</button>

            <div class="text-center mt-4">
                <p style="font-size: 14px; color: #64748b;">Doesn't have an account? 
                    <a href="{{ route('admin.company-register') }}" style="color: #057db0; font-weight:700; text-decoration:none;">Sign Up</a>
                </p>
            </div>
        </form>
    </div>

<div class="login-right">
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
                <h3>Simplify Every Process</h3>
                <p>Experience a dashboard designed to simplify every HR task and improve productivity across your organization.</p>
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
                <h3>Track Performance</h3>
                <p>Monitor employee efficiency with real-time analytics and detailed performance reports at your fingertips.</p>
            </div>
        </div>

        <!--<div class="slide">
            <div class="lottie-box">
                <lottie-player 
                    src="{{ asset('assets/lottie/newstart.json') }}" 
                    background="transparent" speed="1" 
                     
                    loop autoplay>
                </lottie-player>
            </div>
            <div class="slide-content">
                <h3>Secure & Reliable</h3>
                <p>Your data is protected with enterprise-grade security. Focus on growing your team while we handle the safety.</p>
            </div>
        </div>-->

        <div class="dots-container">
            <span class="dot active" data-index="0"></span>
            <span class="dot" data-index="1"></span>
            <!--<span class="dot" data-index="2"></span>-->
        </div>
    </div>
</div>

<script>
    // Password Toggle
    function togglePassword() {
        const p = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (p.type === 'password') {
            p.type = 'text';
            icon.setAttribute('data-feather', 'eye-off');
        } else {
            p.type = 'password';
            icon.setAttribute('data-feather', 'eye');
        }
        feather.replace();
    }

    // Initialize
    window.onload = function() {
        showSlides(slideIndex);
        startAutoSlide();
        if(typeof feather !== 'undefined') { feather.replace(); }
    };
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let slideIndex = 0;
        let autoSlideTimer;
        const slides = document.querySelectorAll(".slide");
        const dots = document.querySelectorAll(".dot");

        function showSlides(n) {
            if (slides.length === 0) return;

            // Reset Index
            if (n >= slides.length) slideIndex = 0;
            else if (n < 0) slideIndex = slides.length - 1;
            else slideIndex = n;

            // Hide all and Remove Active
            slides.forEach(slide => {
                slide.style.display = "none";
                slide.classList.remove("active");
            });
            dots.forEach(dot => dot.classList.remove("active"));

            // Show Active
            slides[slideIndex].style.display = "block";
            slides[slideIndex].classList.add("active");
            dots[slideIndex].classList.add("active");
        }

        // --- DOTS CLICK LOGIC ---
        dots.forEach(dot => {
            dot.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                console.log("Dot manually clicked:", index);
                
                clearInterval(autoSlideTimer); // Auto stop
                showSlides(index);
                startAutoSlide(); // Restart timer
            });
        });

        function startAutoSlide() {
            autoSlideTimer = setInterval(() => {
                slideIndex++;
                showSlides(slideIndex);
            }, 5000);
        }

        // Initialize
        showSlides(slideIndex);
        startAutoSlide();

        // Feather Icons initialization (if used)
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection