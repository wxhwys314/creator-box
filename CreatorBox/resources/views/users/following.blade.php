@extends('layouts.app')

@section('content')
    <div class="container main-content py-4"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin: auto !important;"
        @endif"
    >
        <div class="row justify-content-center">
            <div style="width: 100%; max-width: 698px;">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Followed Creators</h2>
                    <span class="text-muted">
                        {{ $followings->total() }} creators
                    </span>
                </div>

                @if($followings->count() > 0)
                    @include('components.creators-wrapper', ['creators' => $followings])

                    <!-- Pagination -->
                    <div class="row mt-4">
                        <div class="col-12 d-flex justify-content-center">
                            {{ $followings->links() }}
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <h4 class="text-muted">You're not following anyone yet</h4>
                        <p class="text-muted mb-4">Discover and follow creators to see their content here.</p>
                        <a href="{{ route('creators.find') }}" class="btn btn-primary">Discover Creators</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection