@extends('layouts.app')

@section('content')
    <div class="main-content"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin-left: 0 !important;"
        @endif"
    >
        <!-- Page Header -->
        <div class="find-header">
            <div class="find-title">Find "featured" creators.</div>
            <div class="find-subtext">Discover featured creators to support and follow.</div>
        </div>
        <div class="container">
            <!-- Creators Results -->
            <div class="row mb-4 find-results">
                @if($creators->count() > 0)
                    <div class="find-results-subtitle">Suggested Creators</div>
                    @include('components.creators-wrapper', ['creators' => $creators])

                    <!-- Pagination -->
                    <div class="row mt-4">
                        <div class="col-12 d-flex justify-content-center">
                            {{ $creators->links() }}
                        </div>
                    </div>
                @else
                    <!-- No Creators Found -->
                    <div class="find-none">
                        <b>No creators found</b>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const box = document.getElementById('body');
            box.style.backgroundColor = '#f3f5f8';
        });
    </script>
@endsection