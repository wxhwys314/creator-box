@extends('layouts.app')

@section('content')
<div class="container register-content">
    <div class="row justify-content-center align-items-center vh-100">
        <div id="backgroundCarousel" class="background-container" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; overflow: hidden;">
            @foreach($backgroundImages as $index => $imageData)
                <div class="background-slide @if($index === 0) active @endif" style="background-image: url('{{ $imageData['url'] }}');" data-post-index="{{ $index }}"></div>
            @endforeach
        </div>
    
        <div class="register-wrapper">
            <div class="register-title">CreatorBox</div>
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

                <hr class="login-divider">

                <div class="recaptcha-note">
                    This site is protected, and applicable privacy and terms of service apply as required.
                </div>
            </form>
        </div>

        <div class="credit-container">
            @foreach($backgroundImages as $index => $imageData)
                @if(isset($imageData['post']))
                <div class="credit-box @if($index === 0) active @endif" data-post-index="{{ $index }}">
                    <div class="user-info" style="height: 48px; display: flex; align-items: center;">
                        @if(isset($imageData['post']->user->creator_id))
                        <a href="{{ route('creators.profile', $imageData['post']->user->creator_id) }}">
                            <img src="{{ $imageData['post']->user->avatar ? Storage::url($imageData['post']->user->avatar) : asset('images/default-avatar.png') }}" 
                                 style="display: inline-block; vertical-align: top; width: 48px; height: 48px; border-radius: 50%; overflow: hidden; object-fit: cover;"/>
                        </a>
                        @endif
                        <div class="discription" style="display: grid; margin-left: 8px;">
                            <div class="illust-title" style="font-size: 12px; line-height: 20px; font-weight: bold; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                @if(isset($imageData['post']->slug) || isset($imageData['post']->id))
                                <a href="{{ route('public.posts.show', $imageData['post']->slug ?? $imageData['post']->id) }}" style="color: white; text-decoration: none;">
                                    {{ $imageData['post']->title ?? 'Untitled' }}
                                </a>
                                @endif
                            </div>
                            <div class="user-name" style="font-size: 12px; line-height: 20px; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                @if(isset($imageData['post']->user->creator_id))
                                <a href="{{ route('creators.profile', $imageData['post']->user->creator_id) }}" style="color: white; text-decoration: none;">
                                    {{ $imageData['post']->user->name ?? 'Unknown User' }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="credit" style="display: grid; font-size: 12px; line-height: 20px; display: -webkit-box; -webkit-line-clamp: 2; overflow: hidden; text-overflow: ellipsis; word-break: break-all; color: rgba(255, 255, 255, 0.68); text-decoration: underline;">
                        Featured creator in ETERNAL VERSION 2026
                    </div>  
                </div>
                @endif
            @endforeach
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

    //slideshow script
    var carouselTimer

    const slides = document.querySelectorAll('.background-slide');
    const totalSlides = slides.length;
    
    const creditBoxes = document.querySelectorAll('.credit-box');

    let currentSlide = 0;
    const slideInterval = 7000;
    
    function preloadImages() {
        slides.forEach(slide => {
            const bgImage = slide.style.backgroundImage;
            if (bgImage) {
                const imgUrl = bgImage.replace(/url\(['"]?(.*?)['"]?\)/i, '$1');
                const img = new Image();
                img.src = imgUrl;
            }
        });
    }
    
    function showNextSlide() {
        slides[currentSlide].classList.remove('active');
        if (creditBoxes[currentSlide]) {
            creditBoxes[currentSlide].classList.remove('active');
        }
        
        currentSlide = (currentSlide + 1) % totalSlides;
        
        slides[currentSlide].classList.add('active');
        if (creditBoxes[currentSlide]) {
            creditBoxes[currentSlide].classList.add('active');
        }
    }
    
    function initCarousel() {
        slides.forEach((slide, index) => {
            slide.style.zIndex = totalSlides - index;
        });
        
        creditBoxes.forEach((box, index) => {
            box.style.zIndex = totalSlides - index + 10;
        });
        
        slides[0].classList.add('active');
        if (creditBoxes[0]) {
            creditBoxes[0].classList.add('active');
        }
        
        preloadImages();
        
        carouselTimer = setInterval(showNextSlide, slideInterval);
    }
    
    initCarousel();

    const creditContainer = document.querySelector('.credit-container');
    
    if (creditContainer) {
        creditContainer.addEventListener('mouseenter', function() {
            if (carouselTimer) {
                clearInterval(carouselTimer);
            }
        });
        creditContainer.addEventListener('mouseleave', function() {
            carouselTimer = setInterval(showNextSlide, slideInterval);
        });
    }
});
</script>
@endsection