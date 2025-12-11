<div class="creators-wrapper">
    @foreach($creators as $creator)
        <a href="{{ route('creators.profile', $creator->creator_id) }}">
            <div class="creator-wrapper">
                <div class="creator-info">
                    <!-- Creator Avatar -->
                    @if($creator->avatar)
                        <div class="creator-avatar" style="background-image: url('{{ Storage::url($creator->avatar) }}');"></div>
                    @else
                        <div class="creator-avatar bg-secondary d-flex align-items-center justify-content-center">
                            <span class="text-white">{{ Str::substr($creator->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div class="creator-text">
                        <div>{{ $creator->name }}</div>
                        <div><span>@</span>{{ $creator->creator_id }}</div>
                        <div>{{ $creator->bio }}</div>
                    </div>
                </div>
                <div class="posts-wrapper">
                    @if($creator->posts->count() > 0)
                        @foreach($creator->posts as $post)
                            @if($post->cover)
                                <div class="post-cover" style="background-image: url('{{ Storage::url($post->cover) }}');"></div>
                            @else
                                <div class="post-cover"></div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </a>
    @endforeach
</div>