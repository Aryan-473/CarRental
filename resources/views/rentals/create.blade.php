@extends('layouts.userapp')

@section('contents_title')
    <div class="page-header">
        <h3 class="page-title">Book {{ $car->full_name }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cars.index') }}">Cars</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cars.show', $car) }}">{{ $car->full_name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Book</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents_body')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Car Summary -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Booking Summary</h5>

                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $car->featured_image }}" alt="{{ $car->full_name }}"
                            style="height: 100px; width: 150px; object-fit: cover; border-radius: 5px;">
                        <div class="ms-3">
                            <h6>{{ $car->full_name }}</h6>
                            <p class="text-muted mb-0">{{ $car->year }} • {{ ucfirst($car->transmission) }}</p>
                            <h6 class="text-primary mt-1">${{ number_format($car->price_per_day, 2) }}/day</h6>
                        </div>
                    </div>

                    <hr>

                    <div id="priceBreakdown">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Price per day</span>
                            <span>$<span id="pricePerDay">{{ number_format($car->price_per_day, 2) }}</span></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Number of days</span>
                            <span id="daysCount">0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Security Deposit</span>
                            <span>${{ number_format($car->security_deposit, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total Amount</strong>
                            <strong class="text-primary">$<span id="totalAmount">0.00</span></strong>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="mdi mdi-information-outline"></i>
                        <small>
                            A security deposit of ${{ number_format($car->security_deposit, 2) }} will be charged
                            and refunded after the rental period.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Booking Details</h5>

                    <form action="{{ route('rentals.store', $car) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pickup_date" class="form-label">Pickup Date & Time <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" name="pickup_date" id="pickup_date"
                                    class="form-control @error('pickup_date') is-invalid @enderror"
                                    min="{{ now()->addHour()->format('Y-m-d\TH:i') }}" value="{{ old('pickup_date') }}"
                                    required>
                                @error('pickup_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="return_date" class="form-label">Return Date & Time <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" name="return_date" id="return_date"
                                    class="form-control @error('return_date') is-invalid @enderror"
                                    value="{{ old('return_date') }}" required>
                                @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="pickup_location" class="form-label">Pickup Location <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="pickup_location" id="pickup_location"
                                    class="form-control @error('pickup_location') is-invalid @enderror"
                                    value="{{ old('pickup_location', $car->location) }}" required>
                                @error('pickup_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="return_location" class="form-label">Return Location <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="return_location" id="return_location"
                                    class="form-control @error('return_location') is-invalid @enderror"
                                    value="{{ old('return_location', $car->location) }}" required>
                                @error('return_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="special_requests" class="form-label">Special Requests</label>
                                <textarea name="special_requests" id="special_requests"
                                    class="form-control @error('special_requests') is-invalid @enderror" rows="3"
                                    placeholder="Any special requests or requirements">{{ old('special_requests') }}</textarea>
                                @error('special_requests')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert-triangle"></i>
                            <strong>Cancellation Policy:</strong>
                            <ul class="mb-0 mt-1">
                                <li>Free cancellation up to 24 hours before pickup</li>
                                <li>50% charge for cancellations within 24 hours</li>
                                <li>No refund for no-shows</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="mdi mdi-check-circle"></i> Confirm Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('contents_jsbelow')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pickupDate = document.getElementById('pickup_date');
            const returnDate = document.getElementById('return_date');
            const pricePerDay = parseFloat(document.getElementById('pricePerDay').textContent);
            const daysCount = document.getElementById('daysCount');
            const totalAmount = document.getElementById('totalAmount');

            function calculateTotal() {
                if (pickupDate.value && returnDate.value) {
                    const pickup = new Date(pickupDate.value);
                    const return_ = new Date(returnDate.value);

                    if (return_ > pickup) {
                        const days = Math.ceil((return_ - pickup) / (1000 * 60 * 60 * 24));
                        daysCount.textContent = days;
                        totalAmount.textContent = (days * pricePerDay).toFixed(2);
                    } else {
                        daysCount.textContent = 0;
                        totalAmount.textContent = '0.00';
                    }
                }
            }

            pickupDate.addEventListener('change', calculateTotal);
            returnDate.addEventListener('change', calculateTotal);

            // Set minimum return date
            pickupDate.addEventListener('change', function() {
                const pickupDateTime = new Date(this.value);
                const minReturnDateTime = new Date(pickupDateTime.getTime() + 24 * 60 * 60 * 1000);
                returnDate.min = minReturnDateTime.toISOString().slice(0, 16);
            });

            // Initialize with current values if any
            if (pickupDate.value && returnDate.value) {
                calculateTotal();
            }
        });
    </script>
@endsection
