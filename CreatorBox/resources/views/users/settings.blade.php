@extends('layouts.app')

@section('content')
    <div class="container main-content"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="max-width: 100% !important; width: 100% !important; margin: auto !important;"
        @endif
    >
        <div class="row justify-content-center">
            <div style="width: 100%; max-width: 700px; margin: 40px 0px;">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">User Settings</h4>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="delete-cover-form" action="{{ route('users.settings.cover.delete') }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>

                        <form method="POST" action="{{ route('users.settings.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Avatar Section -->
                            <div class="row mb-3 mt-3">
                                <label for="avatar" class="col-md-3 col-form-label text-md-end">Avatar</label>
                                <div class="col-md-9">
                                    <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
                                    @if(Auth::user()->avatar)
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="document.getElementById('delete-avatar-form').submit()">
                                            Remove Avatar
                                        </button>
                                    @endif
                                </div>
                                @error('avatar')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Cover Section -->
                            <div class="row mb-3">
                                <label for="cover" class="col-md-3 col-form-label text-md-end">Cover</label>
                                <div class="col-md-9">
                                    <input type="file" name="cover" id="cover" class="form-control">
                                    @if(Auth::user()->cover)
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="document.getElementById('delete-cover-form').submit()">
                                            Remove Cover
                                        </button>
                                    @endif
                                </div>
                                @error('cover')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Basic Information -->
                            <div class="row mb-3">
                                <label for="name" class="col-md-3 col-form-label text-md-end">Username</label>
                                <div class="col-md-9">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', Auth::user()->name) }}" required autocomplete="name">
                                    @error('name')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email (Read-only) -->
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label text-md-end">Email</label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                                    <small class="text-muted">Email cannot be changed</small>
                                </div>
                            </div>

                            <!-- Role (Read-only) -->
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label text-md-end">Role</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control text-capitalize" value="{{ Auth::user()->role }}" disabled>
                                </div>
                            </div>

                            <!-- Creator ID (Only for creators) -->
                            @if(Auth::user()->isCreator())
                            <div class="row mb-3">
                                <label for="creator_id" class="col-md-3 col-form-label text-md-end">Creator ID</label>
                                <div class="col-md-9">
                                    <input id="creator_id" 
                                        type="text" 
                                        class="form-control @error('creator_id') is-invalid @enderror" 
                                        name="creator_id" 
                                        value="{{ old('creator_id', Auth::user()->creator_id) }}" 
                                        required>
                                    <small class="form-text text-muted">
                                        Your profile URL: {{ config('app.url') }}/@<span id="creatorIdPreview">{{ Auth::user()->creator_id ?? 'your-id' }}</span>
                                    </small>

                                    @error('creator_id')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @endif

                            <!-- Bio -->
                            <div class="row mb-3">
                                <label for="bio" class="col-md-3 col-form-label text-md-end">Bio</label>
                                <div class="col-md-9">
                                    <textarea id="bio" 
                                            class="form-control @error('bio') is-invalid @enderror" 
                                            name="bio" 
                                            rows="4" 
                                            placeholder="Tell us about yourself...">{{ old('bio', Auth::user()->bio) }}</textarea>
                                    <small class="form-text text-muted">{{ __('Max 500 characters') }}</small>

                                    @error('bio')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Coin Balance (Read-only) -->
                            <div class="row mb-4">
                                <label class="col-md-3 col-form-label text-md-end">Coin Balance</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input type="text" 
                                            class="form-control" 
                                            value="{{ number_format(Auth::user()->coin_balance) }}" 
                                            disabled>
                                        <span class="input-group-text">coins</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-9 offset-md-3">
                                    <button type="submit" class="btn btn-primary px-4">
                                        Update Profile
                                    </button>
                                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </form>

                        <!-- Hidden form for avatar deletion -->
                        @if(Auth::user()->avatar)
                            <form id="delete-avatar-form" 
                                action="{{ route('users.settings.avatar.delete') }}" 
                                method="POST" 
                                class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif

                        <hr class="my-5">

                        <!-- Danger Zone -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-danger">
                                    <div class="card-header bg-danger text-white">
                                        <h5 class="mb-0">Danger Zone</h5>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="text-danger">Delete Account</h6>
                                        <p class="text-muted">
                                            Once you delete your account, there is no going back. This action cannot be undone.
                                            All your data, including posts, sponsorships, and coins will be permanently deleted.
                                        </p>
                                        
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteAccountModal">
                                            Delete My Account
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Account Modal -->
                        <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-danger" id="deleteAccountModalLabel">Delete Account</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-danger">
                                            <h6 class="alert-heading">This action cannot be undone!</h6>
                                            <p class="mb-0">All your data will be permanently deleted including:</p>
                                            <ul class="mb-0 mt-2">
                                                <li>Your profile information</li>
                                                <li>All posts and content</li>
                                                <li>Sponsorship plans and subscriptions</li>
                                                <li>Your coin balance ({{ number_format(Auth::user()->coin_balance) }} coins)</li>
                                                <li>All interactions and history</li>
                                            </ul>
                                        </div>

                                        <form id="deleteAccountForm" action="{{ route('users.settings.destroy') }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            
                                            <div class="mb-3">
                                                <label for="confirmPassword" class="form-label">
                                                    Please enter your password to confirm account deletion:
                                                </label>
                                                <input type="password" 
                                                    class="form-control @error('password') is-invalid @enderror" 
                                                    id="confirmPassword" 
                                                    name="password" 
                                                    required
                                                    placeholder="Enter your current password">
                                                @error('password')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-check mb-3">
                                                <input class="form-check-input @error('confirm_delete') is-invalid @enderror" 
                                                    type="checkbox" 
                                                    name="confirm_delete" 
                                                    id="confirmDelete" 
                                                    required>
                                                <label class="form-check-label text-danger" for="confirmDelete">
                                                    I understand that this action cannot be undone and I want to permanently delete my account.
                                                </label>
                                                @error('confirm_delete')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" form="deleteAccountForm" class="btn btn-danger">Permanently Delete Account</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const creatorIdInput = document.getElementById('creator_id');
            const creatorIdPreview = document.getElementById('creatorIdPreview');

            const avatarInput = document.getElementById('avatar');
            const avatarPreview = document.querySelector('.avatar-preview');

            if (avatarPreview) {
                avatarPreview.addEventListener('click', function() {
                    avatarInput.click();
                });
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
        });
    </script>
@endsection