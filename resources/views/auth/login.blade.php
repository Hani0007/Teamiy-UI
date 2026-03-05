@extends('auth.main')

@section('title', __('auth.login'))

@section('page-styles')
<style>
.login-wrapper{min-height:100vh;background:linear-gradient(135deg,#057db0,#045a80);display:flex;align-items:center;justify-content:center;padding:20px}
.login-card{max-width:440px;width:100%;padding:45px 40px;border-radius:18px;text-align:center;background:rgba(255,255,255,.15);backdrop-filter:blur(18px);border:1px solid rgba(255,255,255,.25)}
.logo-area img{max-width:190px;margin-bottom:35px}
.login-label{color:#e9f7ff;font-size:15px;display:block;margin-bottom:8px;text-align:left}
.login-input{width:100%;padding:15px 16px;border:none;border-radius:12px;background:rgba(255,255,255,.22);color:#fff}
.login-input:focus{outline:none;box-shadow:0 0 0 2px rgba(251,118,51,.5)}
.password-wrapper{position:relative}
.password-toggle{position:absolute;right:14px;top:50%;transform:translateY(-50%);width:20px;height:20px;stroke:#ffffff99;stroke-width:1.6;fill:none;cursor:pointer}
.password-toggle.active{stroke:#fff}
.password-toggle.active line{display:none}
.login-btn{width:100%;margin-top:18px;padding:12px;border:none;border-radius:40px;background:#fb7633;color:#fff;font-size:17px;font-weight:600}
.login-links{margin-top:24px;font-size:14px}
.login-links a{color:#e0f3ff;text-decoration:none;display:block;margin-top:8px}
.error-text{color:#ffd2d2;font-size:13px;margin-top:6px;text-align:left}

/* ✅ GLOBAL ERROR ALERT (ADDED) */
.login-alert-error{
background:#ffffff21;
border:1px solid #fff;
color:#ffffff;
padding:12px 15px;
border-radius:14px;
margin-bottom:15px;
text-align:left;
animation:fadeIn .5s ease;
}
.login-alert-error ul{margin:0;padding-left:18px}

@keyframes fadeIn{from{opacity:0;transform:translateY(-5px)}to{opacity:1;transform:translateY(0)}}

/* ===== TABLETS ===== */
@media (max-width:768px){.login-card{padding:40px 32px}}
/* ===== MOBILE ===== */
@media (max-width:480px){
.login-wrapper{padding:14px}
.login-card{padding:32px 24px;border-radius:16px}
.logo-area img{max-width:155px;margin-bottom:28px}
.login-label{font-size:14px}
.login-input{padding:13px 14px;font-size:14px}
.password-toggle{width:18px;height:18px;right:12px}
.login-btn{padding:12px;font-size:16px}
.login-links{font-size:13px}
}
</style>
@endsection

@section('auth-content')
<section class="login-wrapper">
<div class="login-card">

    <div class="logo-area">
        <a href="https://teamiy.com/">
            <img src="{{ asset('assets/images/teamiy-wh-logo.webp') }}">
        </a>
    </div>

    {{-- ✅ GLOBAL ERRORS ADDED --}}
    @if ($errors->any())
        <div class="login-alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.process') }}" novalidate>
        @csrf
        <input type="hidden" name="user_type" value="admin">

        <!-- EMAIL -->
        <div class="mb-4">
            <label class="login-label">{{ __('auth.email_username') }}</label>
            <input type="email" name="email" class="login-input"
                   value="{{ old('email') }}" required>
            @error('email')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <!-- PASSWORD -->
        <div class="mb-4">
            <label class="login-label">{{ __('auth.password') }}</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password"
                       class="login-input" required>

                <svg class="password-toggle" id="passwordIcon"
                     onclick="togglePassword()" viewBox="0 0 24 24">
                    <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/>
                    <circle cx="12" cy="12" r="3"/>
                    <line x1="3" y1="21" x2="21" y2="3"/>
                </svg>
            </div>
            @error('password')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <button class="login-btn">{{ __('auth.login') }}</button>

        <div class="login-links">
            <a href="{{ route('admin.company-register') }}">Register new company</a>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}">{{ __('auth.forgot_password') }}</a>
            @endif
        </div>
    </form>
</div>
</section>

<script>
function togglePassword(){
    const p=document.getElementById('password');
    const icon=document.getElementById('passwordIcon');

    if(p.type==='password'){
        p.type='text';
        icon.classList.add('active'); // OPEN EYE
    }else{
        p.type='password';
        icon.classList.remove('active'); // CLOSED EYE
    }
}
</script>
@endsection
