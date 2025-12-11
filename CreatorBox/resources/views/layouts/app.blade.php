<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CreatorBox</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Style -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body id="body">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <b>CreatorBox</b>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Search Form in Navbar -->
                        <form action="{{ route('search.index') }}" method="GET" class="d-flex me-2">
                            <div class="input-group input-group-sm">
                                <input type="text" 
                                    name="q" 
                                    class="form-control search-bar" 
                                    placeholder="Search creators..."
                                    value="{{ request('q') }}"
                                    pattern="\S+.*"
                                    required>
                            </div>
                        </form>

                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('home') }}">Dashboard</a>
                                    <a class="dropdown-item" href="{{ route('sponsorships.index') }}">Supported Creators</a>
                                    <a class="dropdown-item" href="{{ route('follow.index') }}">Followed Creators</a>
                                    <a class="dropdown-item" href="{{ route('creators.find') }}">Find Creators</a>
                                    <a class="dropdown-item" href="{{ route('daily.index') }}">Daily check-in
                                        @if(!Auth::user()->hasCheckedInToday())
                                            <span class="badge bg-success">New</span>
                                        @endif
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    @if (Auth::user()->isCreator())
                                        <a class="dropdown-item" href="{{ route('posts.index') }}">Manage Posts</a>
                                        <a class="dropdown-item" href="{{ route('plans.index') }}">Manage Plans</a>
                                        <a class="dropdown-item" href="{{ route('creators.relationship') }}">Fans</a>
                                        <a class="dropdown-item" href="{{ route('creators.profile', Auth::user()->creator_id) }}">Creator's Page</a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('creators.register') }}">Become a Creator</a>
                                    @endif

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="{{ route('users.settings') }}">Settings</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="nav-placeholder"></div>

        <main>
            <!-- sidenav -->
            @include('layouts.sidenav')

            <!-- main -->
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>
</body>
</html>
