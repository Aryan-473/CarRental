@extends('layouts.userapp')

@section('contents_title')
    <div class="page-header">
        <h3 class="page-title">{{ $car->brand }} {{ $car->model }} Details</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cars.index') }}">Cars</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $car->brand }} {{ $car->model }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents_body')
    <div class="row">
        <!-- Car Images -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    @if ($car->images && count(json_decode($car->images)) > 0)
                        <div id="carouselCar" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach (json_decode($car->images) as $key => $image)
                                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image) }}" class="d-block w-100" alt="Car image"
                                            style="height: 400px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCar"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCar"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Car Details & Booking -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</h3>

                    <div class="mb-3">
                        <span class="badge bg-{{ $car->is_available ? 'success' : 'danger' }}">
                            {{ $car->is_available ? 'Available' : 'Not Available' }}
                        </span>
                        <span class="badge bg-info">{{ ucfirst($car->category->name) }}</span>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">Transmission</small>
                            <p><strong>{{ ucfirst($car->transmission) }}</strong></p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Fuel Type</small>
                            <p><strong>{{ ucfirst($car->fuel_type) }}</strong></p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Seats</small>
                            <p><strong>{{ $car->seats }}</strong></p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Color</small>
                            <p><strong>{{ $car->color }}</strong></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Location</small>
                        <p><i class="mdi mdi-map-marker"></i> {{ $car->location }}</p>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Description</small>
                        <p>{{ $car->description }}</p>
                    </div>

                    @if ($car->features)
                        <div class="mb-3">
                            <small class="text-muted">Features</small>
                            <div>
                                @foreach (json_decode($car->features) as $feature)
                                    <span class="badge bg-light text-dark me-1">
                                        <i class="mdi mdi-check-circle text-success"></i> {{ $feature }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-primary">${{ number_format($car->price_per_day, 2) }}/day</h4>
                                <small class="text-muted">Security Deposit:
                                    ${{ number_format($car->security_deposit, 2) }}</small>
                            </div>

                            @if (Auth::check() && $car->is_available)
                                <a href="{{ route('rentals.create', $car) }}" class="btn btn-primary btn-lg">
                                    <i class="mdi mdi-calendar-plus"></i> Book Now
                                </a>
                            @elseif(!Auth::check())
                                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                    <i class="mdi mdi-login"></i> Login to Book
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Cars -->
    @if ($similarCars->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <h4>Similar Cars</h4>
                <div class="row">
                    @foreach ($similarCars as $similarCar)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6>{{ $similarCar->brand }} {{ $similarCar->model }}</h6>
                                    <p class="text-primary">${{ number_format($similarCar->price_per_day, 2) }}/day</p>
                                    <a href="{{ route('cars.show', $similarCar) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection
