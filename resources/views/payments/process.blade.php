@extends('layouts.userapp')

@section('contents_title')
    <div class="page-header">
        <h3 class="page-title">Complete Payment</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rentals.index') }}">My Rentals</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payment</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents_body')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle"></i>
            <strong>Payment Failed!</strong>
            <p class="mb-0">{{ session('error') }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="mdi mdi-information-outline"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Payment Summary -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Payment Summary</h5>

                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $payment->rental->car->featured_image }}" alt="{{ $payment->rental->car->full_name }}"
                            style="height: 80px; width: 120px; object-fit: cover; border-radius: 5px;">
                        <div class="ms-3">
                            <h6>{{ $payment->rental->car->full_name }}</h6>
                            <p class="text-muted mb-0">{{ $payment->rental->car->location }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Pickup Date</span>
                            <span>{{ $payment->rental->pickup_date->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Return Date</span>
                            <span>{{ $payment->rental->return_date->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Days</span>
                            <span>{{ $payment->rental->total_days }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Rental Amount</span>
                            <span>${{ number_format($payment->rental->total_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Security Deposit</span>
                            <span>${{ number_format($payment->rental->security_deposit, 2) }}</span>
                        </div>
                        @if ($payment->tax_amount > 0)
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Tax</span>
                                <span>${{ number_format($payment->tax_amount, 2) }}</span>
                            </div>
                        @endif
                        @if ($payment->discount_amount > 0)
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Discount</span>
                                <span>-${{ number_format($payment->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total Due</strong>
                            <strong class="text-primary h5">${{ number_format($payment->total_amount, 2) }}</strong>
                        </div>
                    </div>

                    @if ($payment->status === 'failed')
                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert-triangle"></i>
                            <strong>Previous payment attempt failed.</strong>
                            <p class="mb-0">Please try again with valid payment details.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Payment Details</h5>
                    <p class="card-description">Enter your payment information to complete the booking</p>

                    <form action="{{ route('payments.confirm', $payment) }}" method="POST" id="paymentForm">
                        @csrf

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method <span
                                    class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method"
                                class="form-control @error('payment_method') is-invalid @enderror" required>
                                <option value="">Select Payment Method</option>
                                <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>
                                    Credit Card</option>
                                <option value="debit_card" {{ old('payment_method') == 'debit_card' ? 'selected' : '' }}>
                                    Debit Card</option>
                                <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal
                                </option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="cardDetails">
                            <div class="mb-3">
                                <label for="card_number" class="form-label">Card Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="card_number" id="card_number"
                                    class="form-control @error('card_number') is-invalid @enderror"
                                    placeholder="1234 5678 9012 3456" value="{{ old('card_number') }}" maxlength="19"
                                    required>
                                <small class="text-muted">Enter a valid card number (13-19 digits)</small>
                                @error('card_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="card_expiry" class="form-label">Expiry Date <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="card_expiry" id="card_expiry"
                                        class="form-control @error('card_expiry') is-invalid @enderror"
                                        placeholder="MM/YY" value="{{ old('card_expiry') }}" maxlength="5" required>
                                    <small class="text-muted">Format: MM/YY</small>
                                    @error('card_expiry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="card_cvv" class="form-label">CVV <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="card_cvv" id="card_cvv"
                                        class="form-control @error('card_cvv') is-invalid @enderror" placeholder="123"
                                        value="{{ old('card_cvv') }}" maxlength="4" required>
                                    <small class="text-muted">3-4 digit security code</small>
                                    @error('card_cvv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Test Card Info -->
                            <div class="alert alert-secondary mt-2">
                                <small>
                                    <strong>Test Card Details:</strong><br>
                                    Card: 4242 4242 4242 4242<br>
                                    Expiry: 12/26<br>
                                    CVV: 123
                                </small>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="mdi mdi-information-outline"></i>
                            <small>
                                Your payment is secure and encrypted. We do not store your card details.
                            </small>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100" id="payButton">
                            <i class="mdi mdi-check-circle"></i> Pay ${{ number_format($payment->total_amount, 2) }}
                        </button>
                    </form>

                    @if ($payment->status === 'failed')
                        <div class="mt-3">
                            <a href="{{ route('rentals.show', $payment->rental) }}" class="btn btn-secondary w-100">
                                <i class="mdi mdi-arrow-left"></i> Back to Booking
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('contents_jsbelow')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentMethod = document.getElementById('payment_method');
            const cardDetails = document.getElementById('cardDetails');

            function toggleCardDetails() {
                const method = paymentMethod.value;
                if (method === 'credit_card' || method === 'debit_card') {
                    cardDetails.style.display = 'block';
                    document.getElementById('card_number').required = true;
                    document.getElementById('card_expiry').required = true;
                    document.getElementById('card_cvv').required = true;
                } else {
                    cardDetails.style.display = 'none';
                    document.getElementById('card_number').required = false;
                    document.getElementById('card_expiry').required = false;
                    document.getElementById('card_cvv').required = false;
                }
            }

            paymentMethod.addEventListener('change', toggleCardDetails);
            toggleCardDetails();

            // Format card number with spaces
            document.getElementById('card_number').addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 16) {
                    value = value.substring(0, 16);
                }
                value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
                this.value = value;
            });

            // Format expiry date
            document.getElementById('card_expiry').addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 4) {
                    value = value.substring(0, 4);
                }
                if (value.length >= 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2);
                }
                this.value = value;
            });

            // Only allow numbers for CVV
            document.getElementById('card_cvv').addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 4) {
                    value = value.substring(0, 4);
                }
                this.value = value;
            });

            // Prevent double submission
            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                const button = document.getElementById('payButton');
                button.disabled = true;
                button.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';

                // Re-enable after 10 seconds if something goes wrong
                setTimeout(function() {
                    button.disabled = false;
                    button.innerHTML =
                        '<i class="mdi mdi-check-circle"></i> Pay ${{ number_format($payment->total_amount, 2) }}';
                }, 10000);
            });
        });
    </script>
@endsection
