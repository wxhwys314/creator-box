@extends('layouts.app')

@section('content')
    <div class="container main-content"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin-left: 0 !important;"
        @endif
    >
        <div class="row justify-content-center">
            <div class="col-lg-8 my-4">
                <!-- Page Title and Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Content Details</h1>
                    <div class="btn-group">
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-outline-primary">Edit</a>
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">Back to List</a>
                    </div>
                </div>

                <!-- Content Details Card -->
                <div class="card">
                    <!-- Cover -->
                    @if($post->cover)
                        <img src="{{ Storage::url($post->cover) }}" class="card-img-top" alt="{{ $post->title }}">
                    @endif

                    <div class="card-body">
                        <!-- Status Labels -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="badge bg-{{ $post->status === 'published' ? 'success' : ($post->status === 'draft' ? 'warning' : 'secondary') }} me-2">
                                    @if($post->status === 'published') Published
                                    @elseif($post->status === 'draft') Draft
                                    @else Archived
                                    @endif
                                </span>
                                <span class="badge bg-primary me-2">
                                    @if($post->type === 'blog') Blog
                                    @elseif($post->type === 'image') Image
                                    @else File
                                    @endif
                                </span>
                                <span class="badge bg-{{ $post->visibility === 'all' ? 'info' : 'warning' }}">
                                    {{ $post->visibility === 'all' ? 'Visible to Everyone' : 'Visible to Supporters Only' }}
                                </span>
                            </div>
                        </div>

                        <!-- Title -->
                        <h2 class="card-title">{{ $post->title }}</h2>

                        <!-- Meta Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    Created At: {{ $post->created_at->format('Y-m-d H:i') }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                @if($post->published_at)
                                    <small class="text-muted">
                                        Published At: {{ $post->published_at->format('Y-m-d H:i') }}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <!-- Text Content -->
                        @if($post->content_text)
                            <div class="content-text mb-4">
                                <h5>Content Details</h5>
                                <div class="border p-3 bg-light rounded">
                                    {!! nl2br(e($post->content_text)) !!}
                                </div>
                            </div>
                        @endif

                        <!-- Image Preview -->
                        @if($post->type === 'image' && $post->media_assets)
                            <div class="media-gallery mb-4">
                                <h5>Image Gallery ({{ count($post->media_assets) }} items)</h5>
                                <div class="row">
                                    @foreach($post->media_urls as $index => $url)
                                        <div class="col-md-4 mb-3">
                                            <img src="{{ $url }}" 
                                                alt="Image {{ $index + 1 }}"
                                                class="img-fluid rounded border">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- File List -->
                        @if($post->type === 'file' && $post->media_assets)
                            <div class="file-list mb-4">
                                <h5>File List ({{ count($post->media_assets) }} items)</h5>
                                <div class="list-group">
                                    @foreach($post->media_assets as $index => $asset)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $asset['original_name'] }}</strong>
                                                    <small class="text-muted ms-2">
                                                        ({{ number_format($asset['size'] / 1024 / 1024, 2) }} MB)
                                                    </small>
                                                </div>
                                                <a href="{{ Storage::url($asset['path']) }}" 
                                                class="btn btn-sm btn-outline-primary"
                                                download="{{ $asset['original_name'] }}">
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Settings Info -->
                        <div class="settings-info mb-4">
                            <h5>Content Settings</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Comment Permission:</strong>
                                    @if($post->comment_permission === 'all') Everyone Can Comment
                                    @elseif($post->comment_permission === 'supporters') Supporters Only
                                    @else Comments Disabled
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <strong>Minimum Supporter Amount:</strong> {{ $post->supporter_min_amount }} Coins
                                </div>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="stats-info">
                            <h5>Statistics</h5>
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h4 class="text-primary">{{ $post->likes_count }}</h4>
                                            <small>Likes</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h4 class="text-success">{{ $post->comments_count }}</h4>
                                            <small>Comments</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h4 class="text-info">{{ $post->views_count ?? 0 }}</h4>
                                            <small>Views</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h4 class="text-warning">{{ $post->downloads_count ?? 0 }}</h4>
                                            <small>Downloads</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
