@extends('layouts.app')

@section('content')
    <div class="container main-content"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin: auto !important;"
        @endif
    >
        @if($query)
            <!-- Search Header -->
            <div class="row mb-2 search-header">
                <div style="margin-top: 30px;">
                    <div>
                        <b>Search Results for "{{ $query }}"</b>
                    </div>
                    <div>
                        <b>{{ $users->total() }} results found</b>
                    </div>
                    <hr>
                </div>
            </div>

            <!-- Search Results -->
            <div class="row mb-4 search-results">
                @if($users->count() > 0)
                    @include('components.creators-wrapper', ['creators' => $users])

                    <!-- Pagination -->
                    <div class="row mt-4">
                        <div class="col-12 d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    </div>
                @else
                    <!-- No Results -->
                    <div class="search-none">
                        <b>No results found</b>
                    </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div class="row">
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">Search Creators</h4>
                    <p class="text-muted">Enter a name or creator ID in the search box above</p>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const box = document.getElementById('body');
            box.style.backgroundColor = '#f3f5f8';
        });
    </script>
@endsection