@extends('layouts.userapp')

@section('contents_title')
    <div class="page-header">
        <h3 class="page-title">Rental Details</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rentals.index') }}">My Rentals</a></li>
                <li class="breadcrumb-item active" aria-current="page">#{{ $rental->id }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents_body')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Rental Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="card-title">Booking Information</h4>
                        <span class="badge bg-{{ $rental->status_badge }} p-2">
                            <i class="mdi mdi-circle"></i> {{ $rental->status_text }}
                        </span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted d-block">Car</label>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $rental->car->featured_image }}" alt="{{ $rental->car->full_name }}"
                                        style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;">
                                    <div class="ms-3">
                                        <h5>{{ $rental->car->full_name }}</h5>
                                        <p class="text-muted mb-0">{{ $rental->car->location }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted d-block">Vendor</label>
                                <p><strong>{{ $rental->car->vendor->name ?? 'N/A' }}</strong></p>
                                <small class="text-muted">{{ $rental->car->vendor->email ?? '' }}</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted d-block">Pickup Date & Time</label>
                                <p><i class="mdi mdi-calendar-check"></i> {{ $rental->pickup_date->format('M d, Y H:i') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted d-block">Return Date & Time</label>
                                <p><i class="mdi mdi-calendar-check"></i> {{ $rental->return_date->format('M d, Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted d-block">Pickup Location</label>
                                <p><i class="mdi mdi-map-marker"></i> {{ $rental->pickup_location }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted d-block">Return Location</label>
                                <p><i class="mdi mdi-map-marker"></i> {{ $rental->return_location }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($rental->special_requests)
                        <div class="mb-3">
                            <label class="text-muted d-block">Special Requests</label>
                            <p>{{ $rental->special_requests }}</p>
                        </div>
                    @endif

                    @php
                        $extras = is_array($rental->extras)
                            ? $rental->extras
                            : (is_string($rental->extras)
                                ? json_decode($rental->extras, true)
                                : []);
                    @endphp
                    @if (is_array($extras) && count($extras) > 0)
                        <div class="mb-3">
                            <label class="text-muted d-block">Extras</label>
                            <div>
                                @foreach ($extras as $extra)
                                    <span class="badge bg-light text-dark me-1">
                                        <i class="mdi mdi-check-circle text-success"></i> {{ $extra }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment & Actions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Payment Summary</h4>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Price per day</span>
                            <span>${{ number_format($rental->car->price_per_day, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Number of days</span>
                            <span>{{ $rental->total_days }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Subtotal</span>
                            <span>${{ number_format($rental->total_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Security Deposit</span>
                            <span>${{ number_format($rental->security_deposit, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total</strong>
                            <strong
                                class="text-primary">${{ number_format($rental->total_amount + $rental->security_deposit, 2) }}</strong>
                        </div>
                    </div>

                    @if ($rental->payment)
                        <div class="mb-3">
                            <label class="text-muted d-block">Payment Status</label>
                            <span class="badge bg-{{ $rental->payment->status_badge }}">
                                {{ ucfirst($rental->payment->status) }}
                            </span>
                            @if ($rental->payment->transaction_id)
                                <p class="mt-2">
                                    <small class="text-muted">Transaction ID:</small>
                                    <br>
                                    <strong>{{ $rental->payment->transaction_id }}</strong>
                                </p>
                            @endif
                            @if ($rental->payment->paid_at)
                                <p class="mt-1">
                                    <small class="text-muted">Paid at:</small>
                                    <br>
                                    <strong>{{ $rental->payment->paid_at->format('M d, Y H:i') }}</strong>
                                </p>
                            @endif
                        </div>
                    @endif

                    @if ($rental->canBeCancelled())
                        <form action="{{ route('rentals.cancel', $rental) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Are you sure you want to cancel this booking?')">
                                <i class="mdi mdi-close"></i> Cancel Booking
                            </button>
                        </form>
                    @endif

                    @if ($rental->status === 'pending' && auth()->user()->isAdmin())
                        <form action="{{ route('rentals.confirm', $rental) }}" method="POST" class="mt-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100">
                                <i class="mdi mdi-check"></i> Confirm Booking
                            </button>
                        </form>
                    @endif

                    @if ($rental->status === 'active' && auth()->user()->isAdmin())
                        <form action="{{ route('rentals.complete', $rental) }}" method="POST" class="mt-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-info w-100">
                                <i class="mdi mdi-check-circle"></i> Complete Booking
                            </button>
                        </form>
                    @endif

                    @if ($rental->payment && $rental->payment->status === 'completed' && auth()->user()->isAdmin())
                        <form action="{{ route('payments.refund', $rental->payment) }}" method="POST" class="mt-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning w-100"
                                onclick="return confirm('Are you sure you want to refund this payment?')">
                                <i class="mdi mdi-cash-refund"></i> Refund Payment
                            </button>
                        </form>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('rentals.index') }}" class="btn btn-secondary w-100">
                            <i class="mdi mdi-arrow-left"></i> Back to Rentals
                        </a>
                    </div>
                </div>
            </div>

            <!-- Car Details Quick View -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Car Details</h5>
                    <ul class="list-unstyled">
                        <li><strong>Brand:</strong> {{ $rental->car->brand }}</li>
                        <li><strong>Model:</strong> {{ $rental->car->model }}</li>
                        <li><strong>Year:</strong> {{ $rental->car->year }}</li>
                        <li><strong>Transmission:</strong> {{ ucfirst($rental->car->transmission) }}</li>
                        <li><strong>Fuel Type:</strong> {{ ucfirst($rental->car->fuel_type) }}</li>
                        <li><strong>Seats:</strong> {{ $rental->car->seats }}</li>
                    </ul>
                    <a href="{{ route('cars.show', $rental->car) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="mdi mdi-eye"></i> View Car Details
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
