@extends('layouts.app')

@section('content')
<div class="container register-content">
    <div class="row justify-content-center align-items-center h-100">
        <div class="register-wrapper">

            <div class="register-title">Join CreatorBox</div>
            <div class="register-subtitle">Create your account and start your journey</div>

            <hr class="register-divider">

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <input id="name" type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus 
                           placeholder="Username">
                    @error('name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="email" type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email" 
                           placeholder="E-mail address">
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <select id="role" name="role" 
                            class="form-control @error('role') is-invalid @enderror" required>
                        <option value="fan" {{ old('role') == 'fan' ? 'selected' : '' }}>Fan</option>
                        <option value="creator" {{ old('role') == 'creator' ? 'selected' : '' }}>Creator</option>
                    </select>
                    @error('role')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group" id="creatorIdField" style="display: none;">
                    <input id="creator_id" type="text" 
                           class="form-control @error('creator_id') is-invalid @enderror" 
                           name="creator_id" value="{{ old('creator_id') }}" autocomplete="off" 
                           placeholder="Creator id (lowercase letters only)">
                    <small class="form-text text-muted">
                        Your unique creator URL: creatorbox.com/<span id="creatorIdPreview">your-id</span>
                    </small>
                    @error('creator_id')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="password" type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="new-password" 
                           placeholder="Password">
                    @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="password-confirm" type="password" 
                           class="form-control" name="password_confirmation" required autocomplete="new-password" 
                           placeholder="Confirm your password">
                </div>

                <button type="submit" class="btn-primary">Create Account</button>
                <a href="{{ route('login') }}" class="btn-secondary">Already have an account? Login</a>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.body.style.backgroundColor = 'rgba(240, 240, 240, 1)';

    const roleSelect = document.getElementById('role');
    const creatorIdField = document.getElementById('creatorIdField');
    const creatorIdInput = document.getElementById('creator_id');
    const creatorIdPreview = document.getElementById('creatorIdPreview');

    function toggleCreatorIdField() {
        if (roleSelect.value === 'creator') {
            creatorIdField.style.display = 'block';
            creatorIdInput.required = true;
        } else {
            creatorIdField.style.display = 'none';
            creatorIdInput.required = false;
        }
    }

    function updateCreatorIdPreview() {
        creatorIdPreview.textContent = creatorIdInput.value || 'your-id';
    }

    function validateCreatorId() {
        const value = creatorIdInput.value;
        if (value && !/^[a-z0-9-]+$/.test(value)) {
            creatorIdInput.classList.add('is-invalid');
            return false;
        } else {
            creatorIdInput.classList.remove('is-invalid');
            return true;
        }
    }

    roleSelect.addEventListener('change', toggleCreatorIdField);
    creatorIdInput.addEventListener('input', updateCreatorIdPreview);
    creatorIdInput.addEventListener('blur', validateCreatorId);

    toggleCreatorIdField();
    updateCreatorIdPreview();
});
</script>
@endsection