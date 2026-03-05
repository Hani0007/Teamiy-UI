@extends('auth.main')

@section('title', __('Reset Password'))

@section('page-styles')
<style>
.reset-wrapper{min-height:100vh;display:flex;justify-content:center;align-items:center;background:linear-gradient(135deg,#057db0,#045a80);padding:20px}
.reset-card{width:100%;max-width:500px;padding:40px;border-radius:20px;text-align:center}
.reset-glass{background:rgba(255,255,255,.15);backdrop-filter:blur(18px);border:1px solid rgba(255,255,255,.25);box-shadow:0 25px 55px rgba(0,0,0,.3)}
.reset-logo img{max-width:180px;margin-bottom:25px}
.reset-title{color:#fff;font-size:20px;margin-bottom:10px}
.reset-subtitle{color:#dff3ff;font-size:14px;margin-bottom:25px}
.reset-field{margin-bottom:15px;text-align:left;position:relative}
.reset-field label{color:#e9f7ff;font-size:14px;margin-bottom:6px;display:block}
.reset-input{width:100%;padding:12px 16px;border-radius:12px;border:none;background:rgba(255,255,255,.22);color:#fff;outline:none}
.reset-input::placeholder{color:#e0f3ff}
.reset-input:focus{background:rgba(255,255,255,.32);box-shadow:0 0 0 2px rgba(251,118,51,.5)}
.password-toggle{position:absolute;right:14px;top:38px;width:20px;height:20px;stroke:rgba(255,255,255,.6);stroke-width:1.6;fill:none;cursor:pointer}
.password-toggle.active{stroke:#fff}
.password-toggle.active line{display:none}
.reset-input-error{border:1px solid #ff6b6b}
.reset-error{color:#ff6b6b;font-size:12px;margin-top:4px;display:block}
.reset-alert-error{background:#ffffff21;border:1px solid #fff;color:#fff;padding:12px 15px;border-radius:14px;margin-bottom:15px;text-align:left;animation:fadeIn .5s ease}
.reset-alert-error ul{margin:0;padding-left:18px}
.reset-password-rules{background:#ffffff1a;border:1px solid #ffffff40;color:#fff;font-size:13px;border-radius:12px;padding:10px 12px;margin-bottom:15px;text-align:left}
.rule-valid{color:#7CFC9F}
@keyframes fadeIn{from{opacity:0;transform:translateY(-5px)}to{opacity:1;transform:translateY(0)}}
.reset-btn{width:100%;background:#fb7633;border:none;padding:12px 15px;border-radius:40px;color:#fff;font-size:16px;font-weight:600;cursor:pointer;margin-top:10px}
.reset-btn:hover{background:#ff8f5f}
.reset-links{margin-top:15px}
.reset-links a{color:#e0f3ff;font-size:14px;text-decoration:none}
@media screen and (max-width:768px){.reset-card{padding:30px}.reset-logo img{max-width:150px}.reset-title{font-size:18px}.reset-subtitle{font-size:13px}.reset-btn{font-size:15px;padding:10px}}
</style>
@endsection

@section('auth-content')
<section class="reset-wrapper">
    <div class="reset-card reset-glass">

        <!-- LOGO -->
        <div class="reset-logo">
            <a href="https://teamiy.com/">
                <img src="{{ asset('assets/images/teamiy-wh-logo.webp') }}" alt="Teamiy by TechVerdi"></a>
        </div>

        <h3 class="reset-title">{{ __('Reset Password') }}</h3>
        <p class="reset-subtitle">{{ __('Create a new password for your account') }}</p>

        {{-- GLOBAL ERRORS --}}
        @if ($errors->any())
        <div class="reset-alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- PASSWORD RULES (NEW) --}}
        <div class="reset-password-rules" id="passwordRules">
            <div id="ruleLength">• Minimum 8 characters</div>
            <div id="ruleMatch">• Passwords must match</div>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <!-- EMAIL -->
            <div class="reset-field">
                <label>Email Address</label>
                <input type="email" class="reset-input @error('email') reset-input-error @enderror" name="email"
                    value="{{ $email ?? old('email') }}" required autofocus>

                @error('email')
                <span class="reset-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div class="reset-field">
                <label>New Password</label>
                <input type="password" id="newPassword"
                    class="reset-input @error('password') reset-input-error @enderror" name="password" required
                    autocomplete="new-password">

                <svg class="password-toggle" id="newPassIcon" onclick="toggleNewPassword()" viewBox="0 0 24 24">
                    <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z" />
                    <circle cx="12" cy="12" r="3" />
                    <line x1="3" y1="21" x2="21" y2="3" />
                </svg>

                @error('password')
                <span class="reset-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="reset-field">
                <label>Confirm Password</label>
                <input type="password" id="confirmPassword" class="reset-input" name="password_confirmation" required
                    autocomplete="new-password">

                <svg class="password-toggle" id="confirmPassIcon" onclick="toggleConfirmPassword()" viewBox="0 0 24 24">
                    <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z" />
                    <circle cx="12" cy="12" r="3" />
                    <line x1="3" y1="21" x2="21" y2="3" />
                </svg>
            </div>

            <!-- SUBMIT -->
            <button type="submit" class="reset-btn">
                {{ __('Reset Password') }}
            </button>

            <!-- BACK TO LOGIN -->
            <div class="reset-links">
                <a href="{{ route('admin.login') }}">Back to Login</a>
            </div>
        </form>

    </div>
</section>

<script>
    function toggleNewPassword(){
const p=document.getElementById('newPassword');
const i=document.getElementById('newPassIcon');
p.type=p.type==='password'?'text':'password';
i.classList.toggle('active');
}
function toggleConfirmPassword(){
const p=document.getElementById('confirmPassword');
const i=document.getElementById('confirmPassIcon');
p.type=p.type==='password'?'text':'password';
i.classList.toggle('active');
}

/* LIVE PASSWORD VALIDATION (NEW) */
const newPass=document.getElementById('newPassword');
const confirmPass=document.getElementById('confirmPassword');
const ruleLength=document.getElementById('ruleLength');
const ruleMatch=document.getElementById('ruleMatch');

function validatePassword(){
if(newPass.value.length>=8){
ruleLength.classList.add('rule-valid');
}else{
ruleLength.classList.remove('rule-valid');
}

if(confirmPass.value!=='' && newPass.value===confirmPass.value){
ruleMatch.classList.add('rule-valid');
}else{
ruleMatch.classList.remove('rule-valid');
}
}

newPass.addEventListener('input',validatePassword);
confirmPass.addEventListener('input',validatePassword);
</script>
@endsection
