@extends('auth.main')

@section('title', __('registration_success'))

@section('page-styles')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<style>
    .split-success-container { display: flex; min-height: 100vh; background: #fff; }
    .success-left {flex: 1; display: flex; flex-direction: column; justify-content: center;padding: 60px; max-width: 600px; overflow-y: auto; text-align: center;}
    .logo-area { align-self: center; margin-bottom: 40px; }
    .success-icon-box {font-size: 80px; color: var(--success-green); margin-bottom: 20px;animation: scaleUp 0.5s ease-out;}
    @keyframes scaleUp { from { transform: scale(0); } to { transform: scale(1); } }
    .success-title { color: #057db0; font-size: 28px; font-weight: 800; margin-bottom: 15px; }
    .success-message { color: #64748b; font-size: 16px; margin-bottom: 35px; line-height: 1.6; }
    .success-message small { display: block; margin-top: 10px; opacity: 0.8; }
    .login-btn {width: 100%; padding: 16px; border-radius: 12px; border: none;background-color: #057db0; color: #fff; font-weight: 700;font-size: 16px; cursor: pointer; transition: 0.3s; text-decoration: none;display: inline-block; box-sizing: border-box;}
    .login-btn:hover { background-color: #fb8233; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    .success-right {flex: 1.2; background-color: #057db0;display: flex; flex-direction: column; align-items: center; justify-content: center;position: relative; color: #fff; padding: 40px;}
    .slider-container { width: 100%; max-width: 500px; text-align: center; }
    .lottie-box { height: 400px; margin-bottom: 30px; display: flex; justify-content: center; }
    .slide-content h3 { font-size: 26px; margin-bottom: 15px; }
    @media (max-width: 992px) { .success-right { display: none; } .success-left { max-width: 100%; padding: 40px 25px; } }
</style>
@endsection

@section('auth-content')
<div class="split-success-container">
    
    <div class="success-left">
        <div class="logo-area">
            <a href="https://teamiy.com/">
                <img src="{{ asset('assets/images/company-logo.png') }}" style="max-width: 200px;">
            </a>
        </div>

        <div class="success-icon-box">
            <i class="fas fa-check-circle"></i>
        </div>

        <h3 class="success-title">{{ __('registration_success') }}</h3>
        
        <p class="success-message">
            {{ __('verification_successful_welcome') }}
            <small>{{ __('click_below_to_login') }}</small>
        </p>

        <a href="{{ route('admin.login') }}" class="login-btn">
            {{ __('login_to_dashboard') }}
        </a>
    </div>

    <div class="success-right">
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
                    <h3>Welcome Aboard!</h3>
                    <p>Your account is now fully verified. You are ready to explore the most powerful HR management tools available.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Success page par slider auto-rotate ki zarurat nahi hoti lekin layout consistency ke liye rakha hai
    window.onload = function() {
        if(typeof feather !== 'undefined') { feather.replace(); }
    };
</script>
@endsection