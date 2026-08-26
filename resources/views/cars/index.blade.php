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
                        <div class="col-md-2">
                            <input type="text" name="location" class="form-control" placeholder="Location"
                                value="{{ request('location') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="brand" class="form-control" placeholder="Brand"
                                value="{{ request('brand') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-control">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="price_min" class="form-control" placeholder="Min Price"
                                value="{{ request('price_min') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="price_max" class="form-control" placeholder="Max Price"
                                value="{{ request('price_max') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('cars.index') }}" class="btn btn-secondary w-100">
                                <i class="mdi mdi-refresh"></i>
                            </a>
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
                    <img src="{{ $car->featured_image }}" class="card-img-top" alt="{{ $car->full_name }}"
                        style="height: 200px; object-fit: cover;">

                    <div class="card-body">
                        <h5 class="card-title">{{ $car->brand }} {{ $car->model }}</h5>
                        <p class="card-text">
                            <span class="badge bg-info">{{ $car->year }}</span>
                            <span class="badge bg-secondary">{{ ucfirst($car->transmission) }}</span>
                            <span class="badge bg-warning text-dark">{{ $car->seats }} seats</span>
                            @if (!$car->is_approved)
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </p>
                        <p class="card-text">
                            <i class="mdi mdi-map-marker"></i> {{ $car->location }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-primary">${{ number_format($car->price_per_day, 2) }}/day</span>
                            <a href="{{ route('cars.show', $car) }}" class="btn btn-outline-primary btn-sm">View
                                Details</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="mdi mdi-car-off mdi-48px"></i>
                    <h4 class="mt-3">No cars available</h4>
                    <p class="mb-0">Please check back later or adjust your search filters.</p>
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
