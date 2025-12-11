@extends('layouts.app')

@section('content')
    <div class="container main-content py-4"
        @if (!Auth::check() || Auth::user()->role !== 'creator')
            style="width: 100% !important; margin-left: 0 !important;"
        @endif
    >
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Edit Support Plan</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('plans.update', $plan) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="plan_name" class="form-label">Plan Name <span class="required-asterisk">*</span></label>
                                <input type="text" class="form-control @error('plan_name') is-invalid @enderror" 
                                    id="plan_name" name="plan_name" 
                                    value="{{ old('plan_name', $plan->plan_name) }}" required>
                                @error('plan_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Monthly Amount <span class="required-asterisk">*</span></label>
                                <div class="form-control bg-light">
                                    {{ number_format(old('monthly_amount', $plan->monthly_amount)) }} coins
                                </div>
                                <div class="form-text">Cannot be changed after publication</div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Plan Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" name="description" rows="4">{{ old('description', $plan->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="cover" class="form-label">Cover Image</label>
                                <input type="file" class="form-control @error('cover') is-invalid @enderror" 
                                    id="cover" name="cover" accept="image/*">
                                @error('cover')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($plan->cover)
                                    <div class="mt-2">
                                        <p class="mb-1">Current Cover:</p>
                                        <img src="{{ Storage::url($plan->cover) }}" alt="Current cover" style="max-height: 150px;" class="rounded border">
                                    </div>
                                @endif
                                <div class="form-text">Recommended size: 400x300px, Max: 2MB</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Update Plan
                                </button>
                                <a href="{{ route('plans.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection