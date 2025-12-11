<div class="creator-content main-content">
    <!-- main content -->
    <div class="main-content-wrapper">
        <div class="left-content">
            <!-- tabs -->
            <div class="tablist-wrapper">
                <a href="{{ route('home') }}" class="innertab">Posts</a>
                <a href="?list=suggested_creators" class="innertab">Suggested Creators</a>
            </div>
            <!-- suggested creators -->
            @if(request()->has('list') && request()->get('list') === 'suggested_creators')
                @if($randomCreators->count() > 0)
                    @include('components.creators-wrapper', ['creators' => $randomCreators])
                @else
                    <div class="no-content">No suggested creators available at the moment.</div>
                @endif
            <!-- posts -->
            @else
                @if($posts->count() > 0)
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
                    <div class="no-content">No posts available at the moment.</div>
                @endif
            @endif
        </div>
        <div class="right-content">
            <div class="subtitle">Announcements</div>
            <div class="announcement">
                <div class="announcement-image">
                    <span>CreatorBox</span>
                </div>
                <div class="announcement-info">
                    <div class="announcement-title">
                        Join the CreatorBox Creator Community! Connect with creators worldwide, share your vision, and grow your creative impact.
                    </div>
                    <div class="announcement-text">
                        Thank you for being a valued member of CreatorBox! We're excited to announce the launch of our Creator Community, a dedicated space for creators like you to connect, share insights, and grow together. As a member, you'll have access to exclusive resources, networking opportunities, and the chance to showcase your work to a broader audience. Join us today and take your creative journey to the next level!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const box = document.getElementById('body');
        box.style.backgroundColor = 'rgb(243, 245, 248)';
        
        const url = window.location.href;

        const tabs = document.querySelectorAll('.innertab');

        if (url.includes('?list=suggested_creators')) {
            tabs[1].style.color = '#333333';
            tabs[1].style.borderBottom = '4px solid #ffbb28';
            tabs[0].style.paddingBottom = '4px';
        } else {
            tabs[0].style.color = '#333333';
            tabs[0].style.borderBottom = '4px solid #ffbb28';
            tabs[1].style.paddingBottom = '4px';
        }
    });    
</script>