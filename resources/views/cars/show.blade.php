@extends('layouts.userapp')

@section('contents_title')
    <div class="page-header">
        <h3 class="page-title">{{ $car->full_name }} Details</h3>
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
                    @php
                        $images = $car->images_array;
                    @endphp
                    @if (count($images) > 0)
                        <div id="carouselCar" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($images as $key => $image)
                                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image) }}" class="d-block w-100"
                                            alt="{{ $car->full_name }}" style="height: 400px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                            @if (count($images) > 1)
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
                            @endif
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-car mdi-96px text-muted"></i>
                            <p class="text-muted">No images available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Car Details & Booking -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">{{ $car->full_name }}</h3>

                    <div class="mb-3">
                        <span class="badge bg-{{ $car->status_badge }}">
                            {{ $car->status_text }}
                        </span>
                        <span class="badge bg-info">{{ ucfirst($car->category->name ?? 'Uncategorized') }}</span>
                        @if (!$car->is_approved)
                            <span class="badge bg-warning text-dark">Pending Approval</span>
                        @endif
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

                    @php
                        $features = $car->features_array;
                    @endphp
                    @if (count($features) > 0)
                        <div class="mb-3">
                            <small class="text-muted">Features</small>
                            <div>
                                @foreach ($features as $feature)
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

                            @if (Auth::check())
                                @if ($car->is_available && $car->is_approved)
                                    <a href="{{ route('rentals.create', $car) }}" class="btn btn-primary btn-lg">
                                        <i class="mdi mdi-calendar-plus"></i> Book Now
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-lg" disabled>
                                        <i class="mdi mdi-car-off"></i> Not Available
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
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
    @if (isset($similarCars) && $similarCars->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <h4>Similar Cars</h4>
                <div class="row">
                    @foreach ($similarCars as $similarCar)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <div class="card h-100">
                                <img src="{{ $similarCar->featured_image }}" class="card-img-top"
                                    alt="{{ $similarCar->full_name }}" style="height: 150px; object-fit: cover;">
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

@section('contents_jsbelow')
    <script>
        $(document).ready(function() {
            // Initialize carousel if needed
            $('.carousel').carousel({
                interval: 3000,
                wrap: true
            });
        });
    </script>
@endsection
