@extends('layouts.app')

@section('content')
    <div class="container main-content"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin-left: 0 !important;"
        @endif
    >
        <div class="row justify-content-center">
            <div class="col-lg-8 my-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>New Post</h1>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Content type -->
                            <div class="mb-3">
                                <label for="type" class="form-label">Post Type <span class="required-asterisk">*</span></label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="blog" {{ old('type') == 'blog' ? 'selected' : '' }}>Blog</option>
                                    <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Images</option>
                                    <option value="file" {{ old('type') == 'file' ? 'selected' : '' }}>Files</option>
                                </select>
                            </div>

                            <!-- Cover image -->
                            <div class="mb-3">
                                <label for="cover" class="form-label">Cover</label>
                                <input type="file" name="cover" id="cover" class="form-control" accept="image/*">
                                <div class="form-text">Max 2MB</div>
                            </div>

                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Title <span class="required-asterisk">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required maxlength="255">
                            </div>

                            <!-- Text content -->
                            <div class="mb-3">
                                <label for="content_text" class="form-label">Description</label>
                                <textarea name="content_text" id="content_text" class="form-control" rows="4">{{ old('content_text') }}</textarea>
                            </div>

                            <!-- Image upload area -->
                            <div class="mb-3" id="image-upload" style="display: none;">
                                <label for="media_images" class="form-label">Upload images <span class="required-asterisk">*</span></label>
                                <input type="file" name="media_images[]" id="media_images" class="form-control" multiple accept="image/*" required>
                                <div class="form-text">Max 10MB, Multiple</div>
                                <div id="image-preview" class="mt-2"></div>
                            </div>

                            <!-- File upload area -->
                            <div class="mb-3" id="file-upload" style="display: none;">
                                <label for="media_files" class="form-label">Upload file <span class="required-asterisk">*</span></label>
                                <input type="file" name="media_files[]" id="media_files" class="form-control" multiple required>
                                <div class="form-text">Max 300MB, Multiple</div>
                            </div>

                            <!-- Visibility settings -->
                            <div class="mb-3">
                                <label for="visibility" class="form-label">Publish to <span class="required-asterisk">*</span></label>
                                <select name="visibility" id="visibility" class="form-select" required>
                                    <option value="all" {{ old('visibility') == 'all' ? 'selected' : '' }}>All users</option>
                                    @if($creatorPlans->count() > 0)
                                        <option value="supporters" {{ old('visibility') == 'supporters' ? 'selected' : '' }}>Supporters Only</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Supporter amount selection -->
                            <div class="mb-3" id="supporter-amount" style="display: none;">                                
                                @foreach($creatorPlans as $plan)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="supporter_min_amount" 
                                            id="monthly_amount plan_{{ $plan->id }}" value="{{ $plan->monthly_amount }}"
                                            {{ old('supporter_min_amount') == $plan->monthly_amount ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="plan_{{ $plan->id }}">
                                            <strong>{{ $plan->monthly_amount }} coins plan or higher</strong>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Comment permission -->
                            <div class="mb-3">
                                <label for="comment_permission" class="form-label">Comment Permission <span class="required-asterisk">*</span></label>
                                <select name="comment_permission" id="comment_permission" class="form-select" required>
                                    <option value="all" {{ old('comment_permission') == 'all' ? 'selected' : '' }}>Everyone</option>
                                    @if($creatorPlans->count() > 0)
                                        <option value="supporters" {{ old('comment_permission') == 'supporters' ? 'selected' : '' }}>Supporters Only</option>
                                    @endif
                                    <option value="none" {{ old('comment_permission') == 'none' ? 'selected' : '' }}>No one</option>
                                </select>
                            </div>

                            <!-- Publish status -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="required-asterisk">*</span></label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">Create</button>
                                <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // no posts creator can only choose for Publish to all user and Comment Permission for everyone
    document.addEventListener('DOMContentLoaded', function() {
        const commentPermissionSelect = document.getElementById('comment_permission');
        const hasPlans = {{ $creatorPlans->count() > 0 ? 'true' : 'false' }};
        
        if (!hasPlans) {
            const supportersOption = Array.from(commentPermissionSelect.options).find(option => 
                option.value === 'supporters'
            );
            if (supportersOption) {
                supportersOption.disabled = true;
                if (commentPermissionSelect.value === 'supporters') {
                    commentPermissionSelect.value = 'all';
                }
            }
        }

        document.getElementById('type').dispatchEvent(new Event('change'));
        document.getElementById('visibility').dispatchEvent(new Event('change'));
    });

    //
    document.getElementById('type').addEventListener('change', function() {
        const type = this.value;
        const imageUpload = document.getElementById('image-upload');
        const fileUpload = document.getElementById('file-upload');
        
        document.getElementById('media_images').required = false;
        document.getElementById('media_files').required = false;
        
        if (type === 'image') {
            imageUpload.style.display = 'block';
            fileUpload.style.display = 'none';
            document.getElementById('media_images').required = true;
        } else if (type === 'file') {
            imageUpload.style.display = 'none';
            fileUpload.style.display = 'block';
            document.getElementById('media_files').required = true;
        } else {
            imageUpload.style.display = 'none';
            fileUpload.style.display = 'none';
        }
    });

    // Images preview
    document.getElementById('media_images').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';
        
        const files = e.target.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail me-2 mb-2';
                    img.style.maxHeight = '100px';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        }
    });

    // Visibility and comment permission change
    document.getElementById('visibility').addEventListener('change', function() {
        const visibility = this.value;
        const commentPermission = document.getElementById('comment_permission');
        const supporterAmount = document.getElementById('supporter-amount');
        const radioInputs = document.querySelectorAll('input[name="supporter_min_amount"]');

        // Reset comment_permission options visibility
        Array.from(commentPermission.options).forEach(option => {
            option.style.display = 'none';
        });

        if (visibility === 'supporters') {
            // Show supporter amount selection
            supporterAmount.style.display = 'block';
            radioInputs.forEach(input => {
                input.required = true;
            });
            if (!document.querySelector('input[name="supporter_min_amount"]:checked')) {
                const firstRadio = document.querySelector('input[name="supporter_min_amount"]');
                if (firstRadio) {
                    firstRadio.checked = true;
                }
            }

            // Allow only "supporters only" or "no one"
            if (commentPermission.querySelector('option[value="supporters"]')) {
                commentPermission.querySelector('option[value="supporters"]').style.display = 'block';
            }
            if (commentPermission.querySelector('option[value="none"]')) {
                commentPermission.querySelector('option[value="none"]').style.display = 'block';
            }
            // Default select "supporters only"
            commentPermission.value = 'supporters';

        } else {
            // Hide supporter amount selection
            supporterAmount.style.display = 'none';
            radioInputs.forEach(input => {
                input.required = false;
            });

            // Allow only "everyone" or "no one"
            if (commentPermission.querySelector('option[value="all"]')) {
                commentPermission.querySelector('option[value="all"]').style.display = 'block';
            }
            if (commentPermission.querySelector('option[value="none"]')) {
                commentPermission.querySelector('option[value="none"]').style.display = 'block';
            }
            // Default select "everyone"
            commentPermission.value = 'all';
        }
    });
    </script>
@endsection