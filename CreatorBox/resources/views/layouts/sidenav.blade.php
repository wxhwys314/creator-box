@if (Auth::check() && Auth::user()->role == 'creator')
    <div class="sidebar-wrapper">
        <div class="sidebar-header">
            <a href="{{ route('creators.profile', Auth::user()->creator_id) }}">
                @if(Auth::user()->avatar)
                    <div class="sidebar-user-avatar" style="background-image: url('{{ Storage::url(Auth::user()->avatar) }}'); background-size: cover; background-position: center;"></div>
                @else
                    <div class="sidebar-user-avatar">
                        <span>{{ Str::substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                @endif
                <div class="sidebar-username">{{ Auth::user()->name }}</div>
                <div class="sidebar-user-page">Creator's Page</div>
            </a>
        </div>
        <div class="sidebar-content">
            <div class="sidebar-button-wrapper">
                <a href="{{ route('posts.create') }}"><button>Post</button></a>
            </div>
            <div class="sidebar-link-wrapper">
                <a href="{{ route('home') }}">Dashboard</a>
            </div>
            <div class="sidebar-link-wrapper">
                <a href="{{ route('posts.index') }}">Manage Posts</a>
            </div>
            <div class="sidebar-link-wrapper">
                <a href="{{ route('plans.index') }}">Manage Plans</a>
            </div>
            <div class="sidebar-link-wrapper">
                <a href="{{ route('creators.relationship') }}">Fans</a>
            </div>
        </div>
        <div class="sidebar-content">
            <div class="sidebar-link-wrapper">
                <a href="{{ route('sponsorships.index') }}">Supported Creators</a>
            </div>
            <div class="sidebar-link-wrapper">
                <a href="{{ route('follow.index') }}">Followed Creators</a>
            </div>
            <div class="sidebar-link-wrapper">
                <a href="{{ route('creators.find') }}">Find Creators</a>
            </div>
        </div>
        <div class="sidebar-content">
            <div class="sidebar-link-wrapper">
                <a href="{{ route('users.settings') }}">Settings</a>
            </div>
        </div>
    </div>
@endif