@extends('layouts.app')

@section('content')
    <div class="container py-4 main-content py-4"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin: auto !important;"
        @endif"
    >
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Daily Check-in</h5>
                    </div>
                    
                    <div class="card-body text-center">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <div class="mb-4">
                            <h4>Your Coins: {{ Auth::user()->coin_balance }}</h4>
                        </div>
                        
                        @if($hasCheckedInToday)
                            <div class="alert alert-info">
                                ✅ Already checked in today
                            </div>
                            <p class="text-muted">Come back tomorrow!</p>
                        @else
                            <form action="{{ route('daily.store') }}" method="POST">
                                @csrf
                                <button type="submit" style="margin: auto; display: flex; align-items: center; background-color: #FFbb28; color: white; height: 32px; padding: 8px 16px; font-size: 16px; border-radius: 8px; border: none;">
                                    Check In Today
                                </button>
                                <p class="mt-2 text-muted small">Get 1 coin</p>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection