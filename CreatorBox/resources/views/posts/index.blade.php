@extends('layouts.app')

@section('content')
    <div class="container main-content py-4 px-5"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin-left: 0 !important;"
        @endif
    >
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1>My posts</h1>
                    </div>
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">New post</a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="btn-group">
                    <a href="{{ route('posts.index', ['type' => 'all']) }}" 
                    class="btn btn-outline-primary {{ $type === 'all' ? 'active' : '' }}">All</a>
                    <a href="{{ route('posts.index', ['type' => 'blog']) }}" 
                    class="btn btn-outline-primary {{ $type === 'blog' ? 'active' : '' }}">Blogs</a>
                    <a href="{{ route('posts.index', ['type' => 'image']) }}" 
                    class="btn btn-outline-primary {{ $type === 'image' ? 'active' : '' }}">Images</a>
                    <a href="{{ route('posts.index', ['type' => 'file']) }}" 
                    class="btn btn-outline-primary {{ $type === 'file' ? 'active' : '' }}">Files</a>
                </div>
            </div>
        </div>

        <!-- Content list -->
        @if($posts->count() > 0)
            <div class="row">
                @foreach($posts as $post)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            @if($post->cover)
                                <img src="{{ Storage::url($post->cover) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <span class="fas fa-{{ $post->type === 'blog' ? 'file-alt' : ($post->type === 'image' ? 'images' : 'file-download') }} fa-3x text-muted"></span>
                                </div>
                            @endif

                            <div class="card-body">
                                <!-- Status labels -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-{{ $post->status === 'published' ? 'success' : ($post->status === 'draft' ? 'warning' : 'secondary') }}">
                                        @if($post->status === 'published') Published
                                        @elseif($post->status === 'draft') Draft
                                        @endif
                                    </span>
                                    <span class="badge bg-primary">
                                        @if($post->type === 'blog') Blog
                                        @elseif($post->type === 'image') Image
                                        @else File
                                        @endif
                                    </span>
                                </div>

                                <!-- Title -->
                                <h5 class="card-title">{{ Str::limit($post->title, 50) }}</h5>

                                <!-- Statistics -->
                                <div class="d-flex justify-content-between text-muted small mb-3">
                                    <span>{{ $post->likes_count }}</span>
                                    <span>{{ $post->comments_count }}</span>
                                    <span>{{ $post->views_count ?? 0 }}</span>
                                </div>

                                <!-- Publish time -->
                                @if($post->published_at)
                                    <small class="text-muted">
                                        published at {{ $post->published_at->diffForHumans() }}
                                    </small>
                                @else
                                    <small class="text-warning">not published</small>
                                @endif
                            </div>

                            <div class="card-footer bg-transparent">
                                <div class="btn-group w-100">
                                    <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary btn-sm">
                                        Check
                                    </a>
                                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-outline-secondary btn-sm">
                                        Edit
                                    </a>
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-center">
                    {{ $posts->links() }}
                </div>
            </div>
        @else
            <!-- If no posts -->
            <div class="row">
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">No Posts yet</h4>
                </div>
            </div>
        @endif
    </div>
@endsection