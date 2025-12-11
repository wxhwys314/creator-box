@extends('layouts.app')

@section('content')
    <div class="container main-content"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin-left: 0 !important;"
        @endif
    >
        <div class="row justify-content-center">
            <div class="col-lg-8 my-4">
                <!-- Page Title -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Edit Content</h1>
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">Back to List</a>
                </div>

                <!-- Edit Form -->
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Current Cover Preview -->
                            @if($post->cover)
                                <div class="mb-3">
                                    <label class="form-label">Current Cover</label>
                                    <div>
                                        <img src="{{ Storage::url($post->cover) }}" alt="Current Cover" class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" name="remove_cover" id="remove_cover" class="form-check-input">
                                        <label for="remove_cover" class="form-check-label">Remove Cover</label>
                                    </div>
                                </div>
                            @endif

                            <!-- New Cover Image -->
                            <div class="mb-3">
                                <label for="cover" class="form-label">Update Cover Image</label>
                                <input type="file" name="cover" id="cover" class="form-control" accept="image/*">
                                <div class="form-text">Supports JPG, PNG format, max 2MB</div>
                            </div>

                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Title <span class="required-asterisk">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title) }}" required maxlength="255">
                            </div>

                            <!-- Text Content -->
                            <div class="mb-3">
                                <label for="content_text" class="form-label">Content</label>
                                <textarea name="content_text" id="content_text" class="form-control" rows="6">{{ old('content_text', $post->content_text) }}</textarea>
                            </div>

                            <!-- Media Files Preview -->
                            @if($post->media_assets && count($post->media_assets) > 0)
                                <div class="mb-3">
                                    <label class="form-label">Current Media Files</label>
                                    <div class="list-group">
                                        @foreach($post->media_assets as $index => $asset)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    @if($post->type === 'image')
                                                        <img src="{{ Storage::url($asset['path']) }}" alt="Image {{ $index + 1 }}" class="img-thumbnail me-2" style="max-height: 50px;">
                                                    @endif
                                                    <span>{{ $asset['original_name'] }}</span>
                                                    <small class="text-muted ms-2">
                                                        ({{ number_format($asset['size'] / 1024 / 1024, 2) }} MB)
                                                    </small>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" name="remove_media[]" value="{{ $index }}" class="form-check-input" id="remove_media_{{ $index }}">
                                                    <label for="remove_media_{{ $index }}" class="form-check-label">Remove</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Add New Media Files -->
                            @if($post->type === 'image')
                                <div class="mb-3">
                                    <label for="media_images" class="form-label">New images</label>
                                    <input type="file" name="media_images[]" id="media_images" class="form-control" multiple accept="image/*">
                                    <div class="form-text">Supports JPG, PNG formats, maximum 10MB per image, multiple selection allowed</div>
                                    <div id="image-preview" class="mt-2"></div>
                                </div>
                            @elseif($post->type === 'file')
                                <div class="mb-3">
                                    <label for="media_files" class="form-label">New files</label>
                                    <input type="file" name="media_files[]" id="media_files" class="form-control" multiple>
                                    <div class="form-text">Supports all file formats, maximum 300MB per file, multiple selection allowed</div>
                                </div>
                            @endif

                            <!-- Visibility Settings -->
                            <div class="mb-3">
                                <label for="visibility" class="form-label">Publish to <span class="required-asterisk">*</span></label>
                                <select name="visibility" id="visibility" class="form-select" required>
                                    <option value="all" {{ old('visibility', $post->visibility) == 'all' ? 'selected' : '' }}>All users</option>
                                    @if($creatorPlans->count() > 0)
                                        <option value="supporters" {{ old('visibility', $post->visibility) == 'supporters' ? 'selected' : '' }}>Supporters Only</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Supporter amount selection -->
                            <div class="mb-3" id="plan-selection" style="{{ old('visibility', $post->visibility) == 'supporters' ? '' : 'display: none;' }}">
                                <label class="form-label">Minimum Support Amount <span class="required-asterisk">*</span></label>
                                
                                @foreach($creatorPlans as $plan)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="supporter_min_amount" 
                                               id="plan_{{ $plan->id }}" value="{{ $plan->monthly_amount }}"
                                               {{ (old('supporter_min_amount', $post->supporter_min_amount) == $plan->monthly_amount) ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="plan_{{ $plan->id }}">
                                            <strong>{{ $plan->plan_name }}</strong> - {{ number_format($plan->monthly_amount) }} coins/month
                                            @if($plan->description)
                                                <br><small class="text-muted">{{ Str::limit($plan->description, 80) }}</small>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                                
                                <div class="form-text">
                                    Supporters who have sponsored this plan or higher-tier plans will be able to access this content.
                                </div>
                            </div>

                            <!-- Comment Permission -->
                            <div class="mb-3">
                                <label for="comment_permission" class="form-label">Comment Permission <span class="required-asterisk">*</span></label>
                                <select name="comment_permission" id="comment_permission" class="form-select" required>
                                    <option value="all" {{ old('comment_permission', $post->comment_permission) == 'all' ? 'selected' : '' }}>Everyone</option>
                                    @if($creatorPlans->count() > 0)
                                        <option value="supporters" {{ old('comment_permission', $post->comment_permission) == 'supporters' ? 'selected' : '' }}>Supporters Only</option>
                                    @endif
                                    <option value="none" {{ old('comment_permission', $post->comment_permission) == 'none' ? 'selected' : '' }}>No one</option>
                                </select>
                            </div>

                            <!-- Publish Status -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Publish Status <span class="required-asterisk">*</span></label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">Update Content</button>
                                <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Dynamically show/hide plan selection
    document.getElementById('visibility').addEventListener('change', function() {
        const visibility = this.value;
        const planSelection = document.getElementById('plan-selection');
        
        if (visibility === 'supporters') {
            planSelection.style.display = 'block';
            const firstRadio = document.querySelector('input[name="supporter_min_amount"]');
            const checkedRadio = document.querySelector('input[name="supporter_min_amount"]:checked');
            if (firstRadio && !checkedRadio) {
                firstRadio.checked = true;
            }
        } else {
            planSelection.style.display = 'none';
        }
    });

    // Images preview for new images
    document.getElementById('media_images')?.addEventListener('change', function(e) {
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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Trigger visibility change to show/hide plan selection
        document.getElementById('visibility').dispatchEvent(new Event('change'));
        
        const commentPermissionSelect = document.getElementById('comment_permission');
        const hasPlans = {{ $creatorPlans->count() > 0 ? 'true' : 'false' }};
        
        if (!hasPlans) {
            // Disable supporters option in comment permission
            const supportersOption = Array.from(commentPermissionSelect.options).find(option => 
                option.value === 'supporters'
            );
            if (supportersOption) {
                supportersOption.disabled = true;
                // If current selection is supporters, fallback to 'all'
                if (commentPermissionSelect.value === 'supporters') {
                    commentPermissionSelect.value = 'all';
                }
            }
        }
    });
    </script>
@endsection