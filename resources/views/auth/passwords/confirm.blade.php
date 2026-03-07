@extends('auth.main')

@section('title', __('Confirm Password'))

@section('page-styles')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<style>
    :root {
        --primary-blue: #057db0;
        --accent-orange: #fb8233;
        --text-dark: #1e293b;
    }

    body, html { margin: 0; padding: 0; font-family: 'Inter', sans-serif; height: 100%; overflow: hidden; }
    .split-confirm-container { display: flex; min-height: 100vh; background: #fff; }

    /* LEFT SECTION (FORM) */
    .confirm-left {
        flex: 1; display: flex; flex-direction: column; justify-content: center;
        padding: 60px; max-width: 600px; overflow-y: auto;
    }
    .logo-area { align-self: center; margin-bottom: 25px; }
    .confirm-header { text-align: center; margin-bottom: 30px; }
    .confirm-header h3 { color: var(--primary-blue); font-weight: 800; font-size: 24px; margin-bottom: 10px; }
    .confirm-header p { color: #64748b; font-size: 15px; line-height: 1.6; }

    /* Form Styles from Login Design */
    .form-group { margin-bottom: 20px; position: relative; }
    .confirm-label { font-weight: 600; color: var(--accent-orange); margin-bottom: 8px; display: block; font-size: 14px; }
    .confirm-input {
        width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid #e2e8f0;
        background: #fff; font-size: 14px; transition: 0.3s; box-sizing: border-box;
    }
    .confirm-input:focus { outline: none; border-color: var(--primary-blue); box-shadow: 0 0 0 4px rgba(5, 125, 176, 0.1); }
    
    /* Error Styling */
    .is-invalid { border-color: #ef4444 !important; }
    .error-msg { color: #ef4444; font-size: 13px; margin-top: 5px; display: block; font-weight: 500; }

    .password-wrapper { position: relative; }
    .password-toggle { 
        position: absolute; right: 15px; top: 50%; transform: translateY(-50%); 
        cursor: pointer; color: #94a3b8; width: 20px; z-index: 10;
    }

    .confirm-btn {
        width: 100%; padding: 14px; border-radius: 12px; border: none;
        background-color: var(--primary-blue); color: #fff; font-weight: 700;
        font-size: 16px; cursor: pointer; transition: 0.3s; margin-top: 10px;
    }
    .confirm-btn:hover { background-color: var(--accent-orange); transform: translateY(-1px); }

    /* RIGHT SECTION (SLIDER) */
    .confirm-right {
        flex: 1.2; background-color: var(--primary-blue);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        position: relative; color: #fff; padding: 40px;
    }
    .slider-container { width: 100%; max-width: 500px; text-align: center; }
    
    .lottie-box { height: 320px; margin-bottom: 30px; display: flex; justify-content: center; }
    .slide-content h3 { font-size: 26px; font-weight: 700; margin-bottom: 15px; }
    .slide-content p { font-size: 16px; opacity: 0.8; line-height: 1.6; }

    @media (max-width: 992px) { .confirm-right { display: none; } .confirm-left { max-width: 100%; padding: 40px 25px; } }
</style>
@endsection

@section('auth-content')
<div class="split-confirm-container">
    
    <div class="confirm-left">
        <div class="logo-area">
            <a href="https://teamiy.com/">
                <img src="{{ asset('assets/images/company-logo.png') }}" style="max-width: 200px;">
            </a>
        </div>

        <div class="confirm-header">
            <h3>{{ __('Confirm Password') }}</h3>
            <p>{{ __('Please confirm your password before continuing.') }}</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="form-group">
                <label class="confirm-label">{{ __('Password') }}</label>
                <div class="password-wrapper">
                    <input id="password" type="password" class="confirm-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
                    <i data-feather="eye" class="password-toggle" id="toggleIcon" onclick="togglePassword()"></i>
                </div>
                @error('password')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="confirm-btn">
                {{ __('Confirm Password') }}
            </button>

            @if (Route::has('password.request'))
                <div class="text-center mt-4">
                    <a href="{{ route('password.request') }}" style="color: #057db0; text-decoration:none; font-size:14px; font-weight:600;">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </div>
            @endif
        </form>
    </div>

    <div class="confirm-right">
        <div class="slider-container">
            <div class="slide active">
                <div class="lottie-box">
                    <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_ai769uun.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></lottie-player>
                </div>
                <div class="slide-content">
                    <h3>Security First</h3>
                    <p>We need to verify it's really you before accessing sensitive account settings.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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

    window.onload = function() {
        if(typeof feather !== 'undefined') { feather.replace(); }
    };
</script>
@endsection