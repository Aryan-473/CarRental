@extends('layouts.userapp')

@section('contents_title')
    <div class="page-header">
        <h3 class="page-title">Book {{ $car->brand }} {{ $car->model }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cars.index') }}">Cars</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cars.show', $car) }}">{{ $car->brand }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Book</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents_body')
    <div class="row">
        <!-- Car Summary -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Booking Summary</h5>
                    <div class="d-flex align-items-center mb-3">
                        @if ($car->images && count(json_decode($car->images)) > 0)
                            <img src="{{ asset('storage/' . json_decode($car->images)[0]) }}" alt="{{ $car->brand }}"
                                style="height: 100px; width: 150px; object-fit: cover; border-radius: 5px;">
                        @endif
                        <div class="ms-3">
                            <h6>{{ $car->brand }} {{ $car->model }}</h6>
                            <p class="text-muted">{{ $car->year }} • {{ ucfirst($car->transmission) }}</p>
                            <h6 class="text-primary">${{ number_format($car->price_per_day, 2) }}/day</h6>
                        </div>
                    </div>
                    <hr>
                    <div id="priceBreakdown">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Price per day</span>
                            <span>$<span id="pricePerDay">{{ number_format($car->price_per_day, 2) }}</span></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Number of days</span>
                            <span id="daysCount">0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Security Deposit</span>
                            <span>${{ number_format($car->security_deposit, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total Amount</strong>
                            <strong class="text-primary">$<span id="totalAmount">0.00</span></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Booking Details</h5>

                    @if (session('error'))
                        <div class="alert alert-danger">
                            <i class="mdi mdi-alert-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('rentals.store', $car) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pickup_date" class="form-label">Pickup Date & Time</label>
                                <input type="datetime-local" name="pickup_date" id="pickup_date"
                                    class="form-control @error('pickup_date') is-invalid @enderror"
                                    min="{{ now()->addHour()->format('Y-m-d\TH:i') }}" required>
                                @error('pickup_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="return_date" class="form-label">Return Date & Time</label>
                                <input type="datetime-local" name="return_date" id="return_date"
                                    class="form-control @error('return_date') is-invalid @enderror" required>
                                @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="pickup_location" class="form-label">Pickup Location</label>
                                <input type="text" name="pickup_location" id="pickup_location"
                                    class="form-control @error('pickup_location') is-invalid @enderror"
                                    value="{{ old('pickup_location', $car->location) }}" required>
                                @error('pickup_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="return_location" class="form-label">Return Location</label>
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

                        <div class="alert alert-info">
                            <i class="mdi mdi-information-outline"></i>
                            Please review the terms and conditions before confirming your booking.
                            By clicking "Confirm Booking", you agree to our cancellation policy.
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
        });
    </script>
@endsection
