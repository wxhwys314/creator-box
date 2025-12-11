@extends('layouts.app')

@section('content')
    <div class="main-content p-4"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin: auto !important;"
        @endif
    >
        <div class="row justify-content-center">
            <div class="col-lg-8 my-4">
                <div class="card">
                    @if($post->cover)
                        <img src="{{ Storage::url($post->cover) }}" class="card-img-top" alt="{{ $post->title }}">
                    @endif

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary">
                                @if($post->type === 'blog') Blog
                                @elseif($post->type === 'image') Image
                                @else File
                                @endif
                            </span>
                            @if($post->visibility === 'supporters')
                                <span class="badge bg-warning">
                                    Supporter only
                                </span>
                            @endif
                        </div>

                        <h1 class="card-title h2 mb-3">{{ $post->title }}</h1>

                        <div class="d-flex align-items-center mb-4">
                            @if($post->user->avatar)
                                <img src="{{ Storage::url($post->user->avatar) }}" 
                                    alt="{{ $post->user->name }}"
                                    class="rounded-circle me-3"
                                    style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3"
                                    style="width: 50px; height: 50px;">
                                    <span class="text-white">{{ Str::substr($post->user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-0">{{ $post->user->name }}</h5>
                                <small class="text-muted">
                                    <span>@</span>{{ $post->user->creator_id }} · 
                                    {{ $post->published_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>

                        @if($post->content_text)
                            <div class="content-text mb-4">
                                {!! nl2br(e($post->content_text)) !!}
                            </div>
                        @endif

                        @if($post->type === 'image' && $post->media_assets)
                            <div class="media-gallery mb-4">
                                <h5 class="mb-3">Images</h5>
                                <div class="row">
                                    @foreach($post->media_urls as $index => $url)
                                        <div class="col-md-6 mb-3">
                                            <img src="{{ $url }}" 
                                                class="img-fluid rounded"
                                                style="cursor: pointer"
                                                onclick="openLightbox('{{ $url }}')">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- File download -->
                        @if($post->type === 'file' && $post->media_assets)
                            <div class="file-downloads mb-4">
                                <h5 class="mb-3">File Download</h5>
                                <div class="list-group">
                                    @foreach($post->media_assets as $index => $asset)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <span>{{ $asset['original_name'] }}</span>
                                                <small class="text-muted ms-2">
                                                    ({{ number_format($asset['size'] / 1024 / 1024, 2) }} MB)
                                                </small>
                                            </div>
                                            <a href="{{ Storage::url($asset['path']) }}" class="btn btn-primary btn-sm" download="{{ $asset['original_name'] }}">
                                                Download
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="interaction-section border-top pt-4">
                            <!-- Like -->
                            @if (auth()->id() !== $post->user_id)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <form action="{{ route('posts.like.toggle', $post) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn {{ $post->isLikedBy(auth()->user()) ? 'btn-danger' : 'btn-outline-danger' }}" {{ $post->isLikedBy(auth()->user()) ? 'disabled' : '' }}>
                                            Like <span class="badge bg-light text-dark">{{ $post->likes_count }}</span>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Comments section -->
                @if($post->canComment(auth()->user()))
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Comment ({{ $post->comments->count() }})</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="form-group">
                                    <textarea name="comment_text" class="form-control" rows="3" placeholder="Write your comment..." required maxlength="1000"></textarea>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary">Post Comment</button>
                                </div>
                            </form>

                            <!-- Comment list -->
                            <div class="comments-list">
                                @foreach($post->comments as $comment)
                                    <div class="comment-item border-bottom pb-3 mb-3">
                                        <div class="d-flex">
                                            @if($comment->user->avatar)
                                                <img src="{{ Storage::url($comment->user->avatar) }}" 
                                                    alt="{{ $comment->user->name }}"
                                                    class="rounded-circle me-3"
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3"
                                                    style="width: 40px; height: 40px;">
                                                    <span class="text-white small">{{ Str::substr($comment->user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-0">{{ $comment->user->name }}</h6>
                                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    @if(auth()->user()->id === $post->user_id || auth()->user()->id === $comment->user_id)
                                                        <form action="{{ route('posts.comments.destroy', $comment) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                    onclick="return confirm('Are you sure you want to delete this comment?')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                                <p class="mb-0 mt-2">{{ $comment->comment_text }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card mt-4">
                        <div class="card-body text-center">
                            @if(!auth()->check())
                                <p class="text-muted mb-0">Please <a href="{{ route('login') }}">log in</a> to post a comment</p>
                            @else
                                @if($post->comment_permission === 'none')
                                    <p class="text-muted mb-0">Comments are disabled for this content</p>
                                @elseif($post->comment_permission === 'supporters')
                                    <p class="text-muted mb-0">Supporters only can comment</p>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <span class="close-btn" onclick="closeLightbox()">&times;</span>
        <img class="lightbox-content" id="lightbox-img">
    </div>

    <script>
        function openLightbox(src) {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox').style.display = 'block';
        }

        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
        }
    </script>

    <style>
        .lightbox {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
        }

        .lightbox-content {
            display: block;
            margin: 5% auto;
            max-width: 80%; 
            max-height: 80%;
            box-shadow: 0 0 20px rgba(0,0,0,0.8);
            border-radius: 6px;
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close-btn:hover {
            color: #ccc;
        }
    </style>
@endsection