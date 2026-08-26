@extends('layouts.userapp')

@section('contents_title')
    <div class="page-header">
        <h3 class="page-title">Available Cars</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cars</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents_body')
    <!-- Search & Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('cars.search') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="location" class="form-control" placeholder="Location" value="{{ request('location') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="brand" class="form-control" placeholder="Brand" value="{{ request('brand') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-control">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="price_min" class="form-control" placeholder="Min Price" value="{{ request('price_min') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="price_max" class="form-control" placeholder="Max Price" value="{{ request('price_max') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Car Listing Grid -->
    <div class="row">
        @forelse($cars as $car)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    @if($car->images && count(json_decode($car->images)) > 0)
                        <img src="{{ asset('storage/' . json_decode($car->images)[0]) }}" 
                             class="card-img-top" alt="{{ $car->brand }} {{ $car->model }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <img src="{{ asset('assets/images/car-placeholder.jpg') }}" 
                             class="card-img-top" alt="Car placeholder"
                             style="height: 200px; object-fit: cover;">
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $car->brand }} {{ $car->model }}</h5>
                        <p class="card-text">
                            <span class="badge bg-info">{{ $car->year }}</span>
                            <span class="badge bg-secondary">{{ ucfirst($car->transmission) }}</span>
                            <span class="badge bg-warning text-dark">{{ $car->seats }} seats</span>
                        </p>
                        <p class="card-text">
                            <strong>Location:</strong> {{ $car->location }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-primary">${{ number_format($car->price_per_day, 2) }}/day</span>
                            <a href="{{ route('cars.show', $car) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="mdi mdi-car-off"></i>
                    No cars available at the moment. Please check back later.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="col-12">
            {{ $cars->links() }}
        </div>
    </div>
@endsection