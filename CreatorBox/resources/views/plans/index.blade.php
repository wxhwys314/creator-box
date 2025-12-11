@extends('layouts.app')

@section('content')
    <div class="container main-content py-4 px-5"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin-left: 0 !important;"
        @endif
    >
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>My Support Plans</h1>
                    <a href="{{ route('plans.create') }}" class="btn btn-primary">Create New Plan</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($plans->count() > 0)
                    <div class="row">
                        @foreach($plans as $plan)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 {{ !$plan->is_active ? 'border-warning' : '' }}">                                
                                    @if($plan->cover)
                                        <img src="{{ Storage::url($plan->cover) }}" class="card-img-top" alt="{{ $plan->plan_name }}" style="height: 200px; object-fit: cover;">
                                    @endif
                                    
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $plan->plan_name }}</h5>
                                        <p class="card-text text-muted">{{ Str::limit($plan->description, 100) }}</p>
                                        
                                        <div class="plan-price h4 text-primary mb-3">
                                            {{ number_format($plan->monthly_amount) }} coins/month
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer bg-transparent">
                                        @if($plan->is_active)
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('plans.edit', $plan) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                                <form action="{{ route('plans.destroy', $plan) }}" method="POST" class="d-grid">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this plan?{{ $plan->active_sponsors_count > 0 ? '\\n\\nNote: This plan has ' . $plan->active_sponsors_count . ' active supporters. It will be deactivated instead of deleted.' : '' }}')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <small class="text-muted">
                                                    @if($plan->active_sponsors_count > 0)
                                                        Waiting for {{ $plan->active_sponsors_count }} supporters to end
                                                    @else
                                                        Waiting for system cleanup
                                                    @endif
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
                        <h4 class="text-muted">No Support Plans Yet</h4>
                        <p class="text-muted">Create your first support plan to start accepting sponsorships from your fans</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection