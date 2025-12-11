@extends('layouts.app')

@section('content')
<div class="container login-content">
    <div class="row justify-content-center align-items-center h-100">
        <div class="login-wrapper">

            <div class="login-title">CreatorBox</div>
            <div class="login-subtitle">Your creative journey starts here</div>

            <hr class="login-divider">

            <form method="POST" action="{{ route('login') }}" style="margin: 0;">
                @csrf

                <div class="form-group">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                        name="email" value="{{ old('email') }}" required autocomplete="email" 
                        placeholder="E-mail address" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                        name="password" required autocomplete="current-password" placeholder="Password">
                    @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>

            <a href="{{ route('register') }}" class="btn-register">Register</a>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-password">Forgot Your Password?</a>
            @endif

            <hr class="login-divider">

            <div class="recaptcha-note">
                This site is protected, and applicable privacy and terms of service apply as required.
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.style.backgroundColor = 'rgba(240, 240, 240, 1)';
    });    
</script>