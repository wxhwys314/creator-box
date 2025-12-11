@extends('layouts.app')

@section('content')
    <div class="main-content p-4"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin: auto !important;"
        @endif
    >
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Fans</h1>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Filter by Status</label>
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('creators.relationship', ['status' => 'all']) }}" 
                                    class="btn btn-{{ $selected_status == 'all' ? 'primary' : 'outline-primary' }}">
                                        All
                                    </a>
                                    <a href="{{ route('creators.relationship', ['status' => 'follower']) }}" 
                                    class="btn btn-{{ $selected_status == 'follower' ? 'primary' : 'outline-primary' }}">
                                        Followers
                                    </a>
                                    <a href="{{ route('creators.relationship', ['status' => 'supporter']) }}" 
                                    class="btn btn-{{ $selected_status == 'supporter' ? 'primary' : 'outline-primary' }}">
                                        Supporters
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if($selected_status == 'supporter' || $selected_status == 'all')
                                <label class="form-label">Filter by Plan</label>
                                <select class="form-select" onchange="window.location.href = this.value">
                                    <option value="{{ route('creators.relationship', ['status' => 'supporter']) }}">All Plans</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ route('creators.relationship', ['status' => 'supporter', 'planId' => $plan->id]) }}" 
                                                {{ $selected_plan_id == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->plan_name }} ({{ number_format($plan->monthly_amount) }} coins)
                                        </option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supporters Section -->
                @if(in_array($selected_status, ['all', 'supporter']) && $supporters->count() > 0)
                <div class="card mb-4">
                    <div class="card-header text-white" style="background-color: #ffbb28; border: 1px solid #ffbb28;">
                        <h5 class="mb-0">
                            Supporters 
                            @if($selected_plan_id)
                                - {{ $plans->firstWhere('id', $selected_plan_id)->plan_name ?? 'Selected Plan' }}
                            @endif
                            <span class="badge bg-light text-dark ms-2">{{ $supporters->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($supporters as $supporterData)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start mb-3">
                                                @if($supporterData['user']->avatar)
                                                    <img src="{{ Storage::url($supporterData['user']->avatar) }}" 
                                                        alt="{{ $supporterData['user']->name }}" 
                                                        class="rounded-circle me-3" 
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3"
                                                        style="width: 50px; height: 50px;">
                                                        <span class="text-white fw-bold">{{ Str::substr($supporterData['user']->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title mb-1">{{ $supporterData['user']->name }}</h6>
                                                    @if ($supporterData['user']->creator_id)
                                                        <p class="text-muted small mb-1"><span>@</span>{{ $supporterData['user']->creator_id }}</p>
                                                    @endif
                                                    <div class="text-success fw-bold">
                                                        {{ number_format($supporterData['total_monthly']) }} coins/month
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="support-plans">
                                                @foreach($supporterData['sponsorships'] as $sponsorship)
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="badge bg-primary">
                                                            {{ $sponsorship->plan->plan_name }}
                                                        </span>
                                                        <small class="text-muted">
                                                            {{ number_format($sponsorship->monthly_amount) }} coins
                                                        </small>
                                                    </div>
                                                @endforeach
                                            </div>
                                            
                                            <div class="mt-2 text-muted small">
                                                Supporting since {{ $supporterData['sponsorships']->first()->created_at->format('M j, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Followers Section -->
                @if(in_array($selected_status, ['all', 'follower']) && $followers->count() > 0)
                <div class="card">
                    <div class="card-header text-white" style="background-color: #0096fa; border: 1px solid #0096fa;">
                        <h5 class="mb-0">
                            Followers
                            <span class="badge bg-light text-dark ms-2">{{ $followers->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($followers as $follower)
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            @if($follower->avatar)
                                                <img src="{{ Storage::url($follower->avatar) }}" 
                                                    alt="{{ $follower->name }}" 
                                                    class="rounded-circle mx-auto mb-3" 
                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto mb-3"
                                                    style="width: 80px; height: 80px;">
                                                    <span class="text-white fw-bold h5">{{ Str::substr($follower->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            
                                            <h6 class="card-title mb-1">{{ $follower->name }}</h6>
                                            @if ($follower->creator_id)
                                                <p class="text-muted small mb-1"><span>@</span>{{ $follower->creator_id }}</p>
                                            @endif
                                            
                                            @if($follower->sponsorships_count > 0)
                                                <span class="badge bg-success mb-2">
                                                    Supporter ({{ $follower->sponsorships_count }} plans)
                                                </span>
                                            @else
                                                <span class="badge bg-secondary mb-2">Follower</span>
                                            @endif
                                            
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    Following {{ $follower->pivot->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Empty State -->
                @if($followers->count() === 0 && $supporters->count() === 0)
                <div class="text-center py-5">
                    <h4 class="text-muted">No fans yet</h4>
                    <p class="text-muted">Share your content to attract followers and supporters!</p>
                    <a href="{{ route('posts.create') }}" style="background-color: #FFbb28; color: white; height: 32px; padding: 8px 16px; font-size: 16px; border-radius: 8px;">Create Content</a>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection