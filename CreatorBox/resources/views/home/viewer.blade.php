<div class="viewer-content">
    <div class="banner" style="background: linear-gradient(rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.3)), url('{{ asset('images/background.png') }}') center/cover no-repeat;">
        <div class="switch-button-wrapper">
            <div class="gradient-shadow">
                <div class="switch-button-button active"><b>About CreatorBox</b></div>
                @guest
                    <a class="switch-button-button link" href="{{ route('register') }}"><b>Creator Sign Up</b></a>
                @else
                    <a class="switch-button-button link" href="{{ route('creators.register') }}"><b>Creator Sign Up</b></a>
                @endguest
            </div>
        </div>

        <div class="title">
            <b>CreatorBox</b>
        </div>

        <div class="subtext">
            <b>CreatorBox is a community where creators can receive continuous support from their fans.</b>
        </div>
    </div>

    <div class="creators-container">
        @foreach($randomCreators as $index => $creator)
            <div class="creator-wrapper {{ $index >= 3 ? 'hide-on-small' : '' }}">
                <a href="{{ route('creators.profile', $creator->creator_id) }}">
                    @if ($creator->cover)
                    <div class="creator-cover" style="background-image: url('{{ Storage::url($creator->cover) }}')"></div>
                    @else
                        <div class="creator-cover no-cover">
                            <span>CreatorBox</span>
                        </div>
                    @endif
                    <div class="creator-user">
                        <div>
                            <div class="creator-avatar" style="background-image: url('{{ Storage::url($creator->avatar ?? '#') }}')"></div>
                        </div>
                        <div class="creator-name">{{ $creator->name }}</div>
                    </div>
                </a>
            </div>
        @endforeach

        <a class="creator-find" href="{{ route('creators.find') }}">
            <b>Find Creators</b>
        </a>
    </div>

    <div class="poem-wrapper">
        <div class="poem-text animate-from-left" style="background: url('{{ asset('images/boubble.png') }}') 35% / contain no-repeat;">
            <b>Up close and personal</b>
        </div>
        <div class="poem-description animate-from-right">
            What are they feeling?<br>
            How do they create such amazing work? If you've ever wanted to learn more<br>
            about your favorite Creators and their unique perspectives, CreatorBox is here to grant your wish.<br>
            You may be let in on secrets you couldn't have learned any other way.<br>
            Your reply may be the idea that sparks their next creation.<br>
            Support your favorite creators.<br>
            Open the door to a whole new world of creativity.
        </div>
    </div>

    <div class="counter-wrapper">
        <div class="counter-title">
            <b>CreatorBox by the numbers</b>
        </div>
        <div class="counter-panel-wrapper">
            <div class="counter-panel">
                <div class="counter-panel-image"></div>
                <div class="counter-panel-text">
                    <div>
                        <b>Users</b>
                    </div>
                    <div>
                        <b>{{ $usersCount }}</b>
                    </div>
                </div>
            </div>
            <div class="counter-panel">
                <div class="counter-panel-image"></div>
                <div class="counter-panel-text">
                    <div>
                        <b>Posts</b>
                    </div>
                    <div>
                        <b>{{ $postsCount }}</b>
                    </div>
                </div>
            </div>
            <div class="counter-panel">
                <div class="counter-panel-image"></div>
                <div class="counter-panel-text">
                    <div>
                        <b>Creators</b>
                    </div>
                    <div>
                        <b>{{ $creatorsCount }}</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const poemText = entry.target.querySelector('.poem-text');
                const poemDescription = entry.target.querySelector('.poem-description');
                
                setTimeout(() => {
                    poemText.classList.add('animate-in');
                }, 50);
                
                setTimeout(() => {
                    poemDescription.classList.add('animate-in');
                }, 150);
            }
        });
    }, {
        threshold: 0.3,
        rootMargin: '0px 0px -100px 0px'
    });

    const poemWrapper = document.querySelector('.poem-wrapper');
    if (poemWrapper) {
        observer.observe(poemWrapper);
    }
</script>