@extends('auth.main')

@section('title', __('Forget Password'))

@section('page-styles')
    <style>
    .reg-wrapper {min-height: 100vh;display: flex;justify-content: center;align-items: center;background: linear-gradient(135deg, #057db0, #045a80);padding: 20px;}
    .reg-card {width: 100%;max-width: 480px;padding: 40px;border-radius: 20px;text-align: center;}
    .glass-effect {background: rgba(255, 255, 255, 0.15);backdrop-filter: blur(18px);border: 1px solid rgba(255, 255, 255, 0.25);box-shadow: 0 25px 55px rgba(0, 0, 0, 0.3);}
    .reg-logo img {max-width: 180px;margin-bottom: 25px;}
    .reg-title {color: #fff;font-size: 20px;margin-bottom: 10px;}
    .reg-subtitle {color: #dff3ff;font-size: 14px;margin-bottom: 25px;}
    .reg-field {margin-bottom: 15px;text-align: left;}
    .reg-field label {color: #e9f7ff;font-size: 14px;margin-bottom: 5px;display: block;}
    .reg-input {width: 100%;padding: 12px 16px;border-radius: 12px;border: none;background: rgba(255, 255, 255, 0.22);color: #fff;outline: none;}
    .reg-input:focus {background: rgba(255, 255, 255, 0.32);box-shadow: 0 0 0 2px rgba(251, 118, 51, 0.5);}
    .reg-input-error {border: 1px solid #ff6b6b;}
    .reg-alert-error {background: #ffffff21;border: 1px solid #fff;color: #ffffff;padding: 12px 15px;border-radius: 14px;margin-bottom: 15px;text-align: left;animation: fadeIn 0.5s ease;}
    .reg-alert-error ul {padding-left: 18px;margin: 0;}
    .reg-alert-error li {font-size: 14px; color: red}
    .reg-alert-success {
        background: green; color: #dee7dd; border: 1px solid rgba(75, 181, 67, 0.4); padding: 10px 15px; border-radius: 12px;margin-bottom: 15px; text-align: left;
    }
    .reg-btn {width: 100%;background: #fb7633;border: none;padding: 12px 15px;border-radius: 40px;color: #fff;font-size: 16px;font-weight: 600;cursor: pointer;}
    .reg-btn:hover {background: #ff8f5f;}
    .reg-links a {color: #e0f3ff;text-decoration: none;font-size: 14px;}
    @keyframes fadeIn {from { opacity: 0; transform: translateY(-5px); }to { opacity: 1; transform: translateY(0); }}
    @media screen and (max-width:768px) {.reg-card {padding: 30px;}
    .reg-logo img {max-width: 150px;margin-bottom: 20px;}
    .reg-title {font-size: 18px;}
    .reg-subtitle {font-size: 13px;}
    .reg-btn {font-size: 15px;padding: 10px;}}
</style>
@endsection

@section('auth-content')
<section class="reg-wrapper">
    <div class="reg-card glass-effect">

        <!-- LOGO -->
        <div class="reg-logo">
            <a href="https://teamiy.com/">
            <img src="{{ asset('assets/images/teamiy-wh-logo.webp') }}" alt="Teamiy by TechVerdi"></a>
        </div>

        <h3 class="reg-title">{{ __('Forget Password') }}</h3>
        <p class="reg-subtitle">{{ __('Enter your email to receive password reset link') }}</p>

        {{-- GLOBAL ERRORS (VISIBLE + THEME MATCHED) --}}
        @if ($errors->any())
            <div class="reg-alert-error" id="autoHideError">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="reg-alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="reg-form">

            @csrf

            <!-- EMAIL -->
            <div class="reg-field">
                <label for="email">Email Address</label>
                <input id="email" type="email" class="reg-input @error('email') reg-input-error @enderror" name="email"
                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                {{-- @error('email')
                <span class="alert alert-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>

                @enderror --}}
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="reg-row" style="margin-top:20px; justify-content:center;">
                <button type="submit" class="reg-btn">

                    {{ __('Send Password Reset Link') }}
                </button>
            </div>

            <!-- LINK BACK TO LOGIN -->
            <div class="reg-links" style="margin-top:15px;">
                <a href="{{ route('admin.login') }}">Back to Login</a>
            </div>

        </form>
    </div>
</section>

@endsection

@section('scripts')
{{-- AUTO HIDE ERROR AFTER 15 SECONDS --}}
<script>
    setTimeout(() => {
        const errorBox = document.getElementById('autoHideError');
        if (errorBox) {
            errorBox.style.transition = 'opacity 0.6s ease';
            errorBox.style.opacity = '0';
            setTimeout(() => errorBox.remove(), 700);
        }
    }, 15000);
</script>
@endsection
