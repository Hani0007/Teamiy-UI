@extends('auth.main')

@section('title', __('registration_success'))

@section('page-styles')
<style>
.success-wrapper{
    min-height:100vh;
    background:linear-gradient(135deg,#057db0,#045a80);
    display:flex;align-items:center;justify-content:center;padding:20px
}
.success-card{
    max-width:480px;width:100%;
    padding:40px;border-radius:18px;text-align:center;
    background:rgba(255,255,255,.15);
    backdrop-filter:blur(18px);
    border:1px solid rgba(255,255,255,.25);
}
.success-icon{
    font-size:64px;color:#28a745;margin-bottom:20px
}
.success-title{color:#fff;font-size:24px;margin-bottom:15px;font-weight:600}
.success-message{color:#dff3ff;font-size:16px;margin-bottom:30px;line-height:1.5}
.login-btn{
    width:100%;margin-top:20px;padding:14px;border:none;border-radius:40px;
    background:#fb7633;color:#fff;font-size:16px;font-weight:600;
    text-decoration:none;display:inline-block;transition:all 0.3s ease
}
.login-btn:hover{
    background:#e85d3c;transform:translateY(-2px);box-shadow:0 4px 8px rgba(0,0,0,.2)
}
</style>
@endsection

@section('auth-content')
<section class="success-wrapper">
    <div class="success-card">
        <div class="success-icon">
            <i class="fa fa-check-circle"></i>
        </div>
        <h3 class="success-title">{{ __('registration_success') }}</h3>
        <p class="success-message">
            {{ __('verification_successful_welcome') }}<br>
            <small>{{ __('click_below_to_login') }}</small>
        </p>
        <a href="{{ route('admin.login') }}" class="login-btn">
            {{ __('login_to_dashboard') }}
        </a>
    </div>
</section>
@endsection
