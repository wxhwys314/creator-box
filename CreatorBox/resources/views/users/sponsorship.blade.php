@extends('layouts.app')

@section('content')
    <div class="container main-content py-4"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin: auto !important;"
        @endif
    >
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>My Supported Creators</h1>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($sponsorships->count() > 0)
                    <div class="row">
                        @foreach($sponsorships as $sponsorship)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 {{ $sponsorship->status === 'cancelled' ? 'border-warning' : '' }}">
                                    @if($sponsorship->status === 'cancelled')
                                        <div class="card-header bg-warning text-dark py-2">
                                            <small>Cancelled - Access until {{ $sponsorship->expires_at->format('M j') }}</small>
                                        </div>
                                    @endif
                                    
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            @if($sponsorship->plan->creator->avatar)
                                                <img src="{{ Storage::url($sponsorship->plan->creator->avatar) }}" 
                                                    alt="{{ $sponsorship->plan->creator->name }}" 
                                                    class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3"
                                                    style="width: 60px; height: 60px;">
                                                    <span class="text-white fw-bold">{{ Str::substr($sponsorship->plan->creator->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-1">{{ $sponsorship->plan->creator->name }}</h5>
                                                <p class="text-muted mb-1"><span>@</span>{{ $sponsorship->plan->creator->creator_id }}</p>
                                                <a href="{{ route('creators.profile', $sponsorship->plan->creator->creator_id) }}" 
                                                    class="btn btn-outline-primary btn-sm">
                                                    View Profile
                                                </a>
                                            </div>
                                        </div>

                                        <div class="plan-info bg-light p-3 rounded mb-3">
                                            <h6 class="mb-2">{{ $sponsorship->plan->plan_name }}</h6>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="h5 text-primary mb-0">
                                                    {{ number_format($sponsorship->monthly_amount) }} coins/month
                                                </span>
                                            </div>
                                            @if($sponsorship->plan->description)
                                                <p class="small text-muted mt-2 mb-0" style="white-space: pre-line;">
                                                    {{ $sponsorship->plan->description }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="sponsorship-details text-muted small">
                                            <div class="row">
                                                <div class="col-6">
                                                    Started {{ $sponsorship->created_at->diffForHumans() }}
                                                </div>
                                                <div class="col-6 text-end">
                                                    @if($sponsorship->status === 'active')
                                                        Renews {{ $sponsorship->expires_at->diffForHumans() }}
                                                    @else
                                                        Expires {{ $sponsorship->expires_at->diffForHumans() }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        @if($sponsorship->status === 'active')
                                            <div class="d-grid gap-2">
                                                <form action="{{ route('sponsorships.cancel', $sponsorship) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Are you sure you want to cancel your support for {{ $sponsorship->plan->creator->name }}? You will retain access until the end of this month.')">
                                                        Cancel Support
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <small class="text-muted">
                                                    Access available until {{ $sponsorship->expires_at->format('M j, Y') }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <h4 class="text-muted">No Active Sponsorships</h4>
                        <p class="text-muted mb-4">You haven't supported any creators yet. Discover amazing creators and show your support!</p>
                        <a href="{{ route('creators.find') }}" class="btn btn-primary">Discover Creators</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection