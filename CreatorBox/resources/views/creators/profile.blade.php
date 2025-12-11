@extends('layouts.app')

@section('content')
    <div class="creator-profile-container main-content"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin-left: 0 !important;"
        @endif
    >
        <!-- main content -->
        <div class="creator-profile-wrapper">
            @if ($creator->cover)
                <div class="creator-profile-banner" style="background-image: url('{{ asset('storage/' . $creator->cover) }}');">
                    <img class="creator-profile-cover" src="{{ asset('storage/' . $creator->cover) }}"/>
                </div>
            @else
                <div class="creator-profile-no-cover">
                    @if (Auth::check() && Auth::user()->id === $creator->id)
                        <div class="cover-content">
                            <div class="cover-text">By setting a cover image,<br>you can make your page even more appealing.</div>
                            <a href="{{ route('users.settings') }}" class="cover-button">+ Set cover image</a>
                        </div>
                    @endif
                </div>
            @endif
            
            <div class="creator-profile-header">
                <div class="header-content">
                    <div class="creator-profile-info">
                        @if ($creator->avatar)
                            <div class="creator-profile-avatar has-avatar" style="background-image: url('{{ asset('storage/' . $creator->avatar) }}');"></div>
                        @else
                            <div class="creator-profile-avatar no-avatar">
                                <span>{{ Str::substr($creator->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="creator-profile-name">{{ $creator->name }}</div>
                        <div class="creator-profile-follow-content">
                            @auth
                                @if(auth()->user()->id !== $creator->id)
                                    @if(auth()->user()->isFollowing($creator->id))
                                        <form action="{{ route('follow.unfollow', $creator->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="unfollow-button">Following</button>
                                        </form>
                                    @else
                                        <form action="{{ route('follow.follow', $creator->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="follow-button">+ Follow</button>
                                        </form>
                                    @endif
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="follow-button">Login to Follow</a>
                            @endauth
                        </div>
                    </div>
                    <div class="creator-profile-tablist" id="creator-profile-tablist">
                        <a href="{{ route('creators.profile', $creator->creator_id) }}" class="creator-profile-innertab">Profile</a>
                        <a href="?list=posts" class="creator-profile-innertab">Posts</a>
                        <a href="?list=plans" class="creator-profile-innertab">Plans</a>
                    </div>
                </div>
            </div>
            
            <div class="creator-profile-tabcontent">
                <!-- posts list -->
                @if (request()->has('list') && request()->get('list') === 'posts')
                    <div class="creator-profile-posts-content">
                        @if ($posts->count() > 0)
                            @foreach($posts as $post)
                                <a href="{{ route('public.posts.show', $post) }}" class="post-wrapper">
                                    @if($post->cover)
                                        <div class="post-cover" style="background-image: url('{{ Storage::url($post->cover) }}');"></div>
                                    @else
                                        <div class="post-cover no-post-cover">
                                            <span>CreatorBox</span>
                                        </div>
                                    @endif
                                    <div class="post-content">
                                        <div class="post-header">
                                            <div class="post-user-wrapper">
                                                @if($post->creator->avatar)
                                                    <div class="user-avatar has-avatar" style="background-image: url('{{ Storage::url($post->creator->avatar) }}');"></div>
                                                @else
                                                    <div class="user-avatar no-avatar">
                                                        <span>{{ Str::substr($post->creator->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div class="user-info">
                                                    <div class="user-name">{{ $post->creator->name }}</div>
                                                    <div class="post-date">
                                                        @if($post->published_at)
                                                            published at {{ $post->published_at->format('M d, Y') }}
                                                        @else
                                                            not published
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="post-visibility">
                                                <div class="post-likes">
                                                    @if($post->visibility === 'all')
                                                        All users
                                                    @elseif($post->visibility === 'supporters')
                                                        {{ $post->supporter_min_amount }} Coins
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="post-title">{{ $post->title }}</div>
                                        <div class="post-footer">
                                            @if ($post->isVisibleTo(auth()->user()))
                                                <div class="post-stats">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
                                                    </svg>
                                                    {{ $post->likes_count }}
                                                </div>
                                            @else
                                                <div class="post-requirement">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                                                    </svg>
                                                    <div>Pledge <strong>{{ $post->supporter_min_amount }} Coins</strong>/month or more to see this content</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="no-content">no post</div>
                        @endif
                    </div>
                <!-- plans list -->
                @elseif (request()->has('list') && request()->get('list') === 'plans')
                    <div class="creator-profile-plans-content">
                        @if ($plans->count() > 0)
                            @foreach ($plans as $plan)
                                <div class="plan-wrapper">
                                    @if($plan->cover)
                                        <div class="plan-cover" style="background-image: url('{{ Storage::url($plan->cover) }}');"></div>
                                    @else
                                        <div class="plan-cover no-plan-cover">
                                            <span>CreatorBox</span>
                                        </div>
                                    @endif
                                    <div class="plan-info">
                                        <div class="plan-name">{{ $plan->plan_name }}</div>
                                        @if ($plan->description)
                                            <div class="plan-description">{{ $plan->description }}</div>
                                        @endif
                                        <div class="plan-amount">
                                            <span class="amount">{{ $plan->monthly_amount }} Coins</span>
                                            <span class="period">/month</span>
                                        </div>
                                        @if (Auth::check() && Auth::user()->isSupportingPlan($plan->id))
                                            <a class="plan-sponsorship-button" style="opacity: 0.5;">Supporting</a>
                                        @elseif (!Auth::check() || Auth::id() !== $creator->id)
                                            <a href="{{ route('plans.show', $plan->id) }}" class="plan-sponsorship-button">Support</a>
                                        @endif

                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="no-content">no plan</div>
                        @endif
                        @if (Auth::check() && Auth::user()->id === $creator->id)
                            <div class="add-plan-wrapper">
                                <a href="{{ route('plans.create') }}" class="add-plan-button">+ Add plan</a>
                            </div>
                        @endif
                    </div>
                <!-- profolio -->
                @else
                    <div class="creator-profile-posts">
                        @if ($creator->bio)
                            <div class="creator-bio">{{ $creator->bio }}</div>
                        @endif
                        @if ($posts->count() > 0)
                            @foreach($posts as $post)
                                <a href="{{ route('public.posts.show', $post) }}" class="post-wrapper">
                                    @if($post->cover)
                                        <div class="post-cover" style="background-image: url('{{ Storage::url($post->cover) }}');"></div>
                                    @endif
                                    <div class="post-content">
                                        <div class="post-header">
                                            <div class="post-user-wrapper">
                                                @if($post->creator->avatar)
                                                    <div class="user-avatar has-avatar" style="background-image: url('{{ Storage::url($post->creator->avatar) }}');"></div>
                                                @else
                                                    <div class="user-avatar no-avatar">
                                                        <span>{{ Str::substr($post->creator->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div class="user-info">
                                                    <div class="user-name">{{ $post->creator->name }}</div>
                                                    <div class="post-date">
                                                        @if($post->published_at)
                                                            published at {{ $post->published_at->format('M d, Y') }}
                                                        @else
                                                            not published
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="post-visibility">
                                                <div class="post-likes">
                                                    @if($post->visibility === 'all')
                                                        All users
                                                    @elseif($post->visibility === 'supporters')
                                                        {{ $post->supporter_min_amount }} Coins
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="post-title">{{ $post->title }}</div>
                                        <div class="post-footer">
                                            @if ($post->isVisibleTo(auth()->user()))
                                                <div class="post-stats">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
                                                    </svg>
                                                    {{ $post->likes_count }}
                                                </div>
                                            @else
                                                <div class="post-requirement">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                                                    </svg>
                                                    <div>Pledge <strong>{{ $post->supporter_min_amount }} Coins</strong>/month or more to see this content</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="no-content">no post</div>
                        @endif
                    </div>
                    <div class="creator-profile-plans">
                        @if ($plans->count() > 0)
                            @foreach ($plans as $plan)
                                <div class="plan-wrapper">
                                    @if($plan->cover)
                                        <div class="plan-cover" style="background-image: url('{{ Storage::url($plan->cover) }}');"></div>
                                    @endif
                                    <div class="plan-info">
                                        <div class="plan-name">{{ $plan->plan_name }}</div>
                                        @if ($plan->description)
                                            <div class="plan-description">{{ Str::limit($plan->description, 150) }}</div>
                                        @endif
                                        <div class="plan-amount">
                                            <span class="amount">{{ $plan->monthly_amount }} Coins</span>
                                            <span class="period">/month</span>
                                        </div>
                                        @if (Auth::check() && Auth::user()->isSupportingPlan($plan->id))
                                            <a class="plan-sponsorship-button" style="opacity: 0.5;">Supporting</a>
                                        @elseif (!Auth::check() || Auth::id() !== $creator->id)
                                            <a href="{{ route('plans.show', $plan->id) }}" class="plan-sponsorship-button">Support</a>
                                        @endif

                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="no-content">no plan</div>
                        @endif
                        @if (Auth::check() && Auth::user()->id === $creator->id)
                            <div class="add-plan-wrapper">
                                <a href="{{ route('plans.create') }}" class="add-plan-button">+ Add plan</a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const box = document.getElementById('body');
        box.style.backgroundColor = 'rgb(243, 245, 248)';

        const url = window.location.href;
        const tabs = document.querySelectorAll('.creator-profile-innertab');

        if (url.includes('?list=posts')) {
            tabs[1].style.color = '#333333';
            tabs[1].style.borderBottom = '4px solid #ffbb28';
            tabs[0].style.paddingBottom = '4px';
            tabs[2].style.paddingBottom = '4px';
        } else if (url.includes('?list=plans')) {
            tabs[2].style.color = '#333333';
            tabs[2].style.borderBottom = '4px solid #ffbb28';
            tabs[0].style.paddingBottom = '4px';
            tabs[1].style.paddingBottom = '4px';
        } else {
            tabs[0].style.color = '#333333';
            tabs[0].style.borderBottom = '4px solid #ffbb28';
            tabs[1].style.paddingBottom = '4px';
            tabs[2].style.paddingBottom = '4px';
        }
    });
</script>

<style>
.creator-profile-banner::before {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-image: url('{{ asset('storage/' . $creator->cover) }}');
    background-size: cover;
    background-position: center;
    filter: blur(8px);
    transform: scale(1.05);
    z-index: 0;
    background-color: rgba(0, 0, 0, 0.4); 
    background-blend-mode: darken; 
}
</style>