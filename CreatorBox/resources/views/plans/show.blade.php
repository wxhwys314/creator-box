@extends('layouts.app')

@section('content')
    <div class="container main-content py-4"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin: auto !important;"
        @endif
    >
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="card">
                    @if($plan->cover)
                        <img src="{{ Storage::url($plan->cover) }}" class="card-img-top" alt="{{ $plan->plan_name }}" style="max-height: 300px; object-fit: cover;">
                    @endif
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h1 class="card-title h3 mb-1">{{ $plan->plan_name }}</h1>
                                <p class="text-muted mb-0">by {{ $plan->creator->name }}</p>
                            </div>
                        </div>

                        <div class="plan-price h2 text-primary mb-4">
                            {{ number_format($plan->monthly_amount) }} coins/month
                        </div>

                        @if($plan->description)
                            <div class="mb-4">
                                <h5 class="mb-2">Description</h5>
                                <p class="card-text">{{ $plan->description }}</p>
                            </div>
                        @endif

                        <div class="plan-meta text-muted small mb-4">
                            <div class="row">
                                <div class="col-6">
                                    Created {{ $plan->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        @auth
                            @if(Auth::id() === $plan->creator_id)
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('plans.edit', $plan) }}" class="btn btn-outline-primary me-md-2">Edit Plan</a>
                                    <form action="{{ route('plans.destroy', $plan) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this plan?{{ $plan->active_sponsors_count > 0 ? '\\n\\nNote: This plan has ' . $plan->active_sponsors_count . ' active supporters. It will be deactivated instead of deleted.' : '' }}')">
                                            Delete Plan
                                        </button>
                                    </form>
                                </div>
                            @else
                                @php
                                    $isSupporting = Auth::user()->isSupportingPlan($plan->id);
                                    $userSponsorship = Auth::user()->sponsorships()
                                        ->where('plan_id', $plan->id)
                                        ->where(function($query) {
                                            $query->where('status', 'active')
                                                ->orWhere(function($q) {
                                                    $q->where('status', 'cancelled')
                                                        ->where('expires_at', '>', now());
                                                });
                                        })
                                        ->first();
                                @endphp

                                @if($isSupporting)
                                    <div class="alert alert-info mb-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>You are supporting this creator!</strong>
                                                <div class="small mt-1">
                                                    @if($userSponsorship->status === 'active')
                                                        Your support will renew automatically.
                                                    @else
                                                        Your access continues until {{ $userSponsorship->expires_at->format('M j, Y') }}
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="badge bg-{{ $userSponsorship->status === 'active' ? 'success' : 'warning' }}">
                                                {{ ucfirst($userSponsorship->status) }}
                                            </span>
                                        </div>
                                    </div>

                                    @if($userSponsorship->status === 'active')
                                        <form action="{{ route('sponsorships.cancel', $userSponsorship) }}" method="POST" class="d-grid">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-lg">Cancel Support</button>
                                        </form>
                                    @endif
                                @else
                                    @if($plan->is_active)
                                        <form action="{{ route('sponsorships.store') }}" method="POST" class="d-grid">
                                            @csrf
                                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                            <button type="submit" class="btn btn-primary btn-lg py-3">
                                                Support This Plan - {{ number_format($plan->monthly_amount) }} coins/month
                                            </button>
                                        </form>
                                        
                                        <div class="text-center mt-2">
                                            <small class="text-muted">
                                                You'll be charged immediately and get access until the end of the month
                                            </small>
                                        </div>
                                    @else
                                        <button class="btn btn-secondary btn-lg py-3 w-100" disabled>
                                            Plan Not Available
                                        </button>
                                    @endif
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg py-3 w-100">
                                Login to Support
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection