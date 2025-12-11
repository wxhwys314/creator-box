@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 100px;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="mb-0">{{ __('Become a Creator') }}</h4>
                    </div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('creators.register.store') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="creator_id" class="form-label">
                                    {{ __('Choose Your Creator ID') }} <span class="text-danger">*</span>
                                </label>
                                
                                <input id="creator_id" 
                                    type="text" 
                                    class="form-control @error('creator_id') is-invalid @enderror" 
                                    name="creator_id" 
                                    value="{{ old('creator_id') }}" 
                                    required 
                                    autocomplete="off"
                                    autofocus
                                    placeholder="Enter your creator ID">

                                <div class="form-text">
                                    <strong>Requirements:</strong>
                                    <ul class="mb-0 mt-1">
                                        <li>Only lowercase letters (a-z) allowed</li>
                                        <li>No numbers, spaces, or special characters</li>
                                        <li>Must be unique</li>
                                    </ul>
                                </div>

                                @error('creator_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">{{ __('Become Creator') }}</button>
                                <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const creatorIdInput = document.getElementById('creator_id');

        function validateCreatorId() {
            const value = creatorIdInput.value;
            // 实时验证：只允许小写字母
            creatorIdInput.value = value.replace(/[^a-z]/g, '');
            
            if (value && !/^[a-z]+$/.test(value)) {
                creatorIdInput.classList.add('is-invalid');
                return false;
            } else {
                creatorIdInput.classList.remove('is-invalid');
                return true;
            }
        }

        creatorIdInput.addEventListener('input', validateCreatorId);
        creatorIdInput.addEventListener('blur', validateCreatorId);
    });
    </script>
@endsection