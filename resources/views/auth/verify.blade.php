@extends('auth.main')

@section('title', __('verify_account'))

@section('page-styles')
<style>
.verify-wrapper{
    min-height:100vh;
    background:linear-gradient(135deg,#057db0,#045a80);
    display:flex;align-items:center;justify-content:center;padding:20px
}
.verify-card{
    max-width:480px;width:100%;
    padding:40px;border-radius:18px;text-align:center;
    background:rgba(255,255,255,.15);
    backdrop-filter:blur(18px);
    border:1px solid rgba(255,255,255,.25);
}
.verify-title{color:#fff;font-size:20px;margin-bottom:10px}
.verify-sub{color:#dff3ff;font-size:14px;margin-bottom:18px}
.verify-input{
    width:100%;padding:14px 16px;border:none;border-radius:12px;
    background:rgba(255,255,255,.22);color:#fff;letter-spacing:6px;text-align:center;font-size:20px
}
.verify-input:focus{outline:none;box-shadow:0 0 0 2px rgba(251,118,51,.5)}
.verify-btn{width:100%;margin-top:16px;padding:12px;border:none;border-radius:40px;background:#fb7633;color:#fff;font-size:16px;font-weight:600}
.verify-links{margin-top:16px;font-size:13px;color:#e0f3ff}
.verify-links button{color:#e0f3ff;background:none;border:none;text-decoration:underline;cursor:pointer}
.email-label{color:#e9f7ff;font-size:13px;margin-bottom:6px;display:block;text-align:left}
.email-display{background:rgba(255,255,255,.12);color:#fff;border-radius:10px;padding:10px 12px;text-align:left}
</style>
@endsection

@section('auth-content')
<section class="verify-wrapper">
    <div class="verify-card">
        <h3 class="verify-title">{{ __('registration_success') }}</h3>
        <p class="verify-sub">{{ __('verify_account') }}</p>

        @include('admin.section.flash_message')

        <div class="mb-3">
            <label class="email-label">Registered Email</label>
            <div class="email-display">{{ $email ?? old('email') }}</div>
        </div>

        <form method="POST" action="{{ route('admin.verify.perform') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
            <input type="text" name="otp" class="verify-input" maxlength="6" pattern="[0-9]{6}" placeholder="______" required>
            <button class="verify-btn">Verify</button>
        </form>

        <div class="verify-links" id="resend-block" data-expiry="{{ $expiresMs ?? '' }}">
            <div id="expiry-text">Code expires in 10:00</div>
            <form method="POST" action="{{ route('admin.verify.resend') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
                <button type="submit" id="resend-btn" disabled>Resend code</button>
            </form>
        </div>

        <div class="verify-links">
            <a href="{{ route('admin.login') }}">Back to login</a>
        </div>
    </div>
</section>
<script>
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
</script>
@endsection
