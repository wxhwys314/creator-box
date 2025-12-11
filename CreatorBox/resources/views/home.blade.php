@extends('layouts.app')

@section('content')
    <div style="width: 100%; overflow-x: hidden; min-height: calc(100vh - 120px);">
        <div class="row justify-content-center">
            <div class="col-md-12" style="display: block; height: 100%;">
                @guest
                    @include('home.viewer')
                @else
                    @if (Auth::user()->isFan())
                        @include('home.viewer')
                    @else
                        @include('home.creator')
                    @endif
                @endguest
            </div>
        </div>
    </div>
@endsection
