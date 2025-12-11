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
                        <h4 class="mb-0">Create Support Plan</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('plans.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="plan_name" class="form-label">Plan Name <span class="required-asterisk">*</span></label>
                                <input type="text" class="form-control @error('plan_name') is-invalid @enderror" 
                                    id="plan_name" name="plan_name" 
                                    value="{{ old('plan_name') }}" required>
                                @error('plan_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="monthly_amount" class="form-label">Monthly Amount (Coins) <span class="required-asterisk">*</span></label>
                                <input type="number" class="form-control @error('monthly_amount') is-invalid @enderror" 
                                    id="monthly_amount" name="monthly_amount" 
                                    value="{{ old('monthly_amount') }}" 
                                    placeholder="100" 
                                    min="100" required>
                                @error('monthly_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Cannot be changed after publication</div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Plan Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" name="description" rows="4" 
                                    placeholder="Describe what supporters will get...">{{ old('description') }}</textarea>
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
                                <div class="form-text">Recommended size: 400x300px, Max: 2MB</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Create Plan
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