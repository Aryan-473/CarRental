@extends('layouts.userapp')

@section('contents_title')
    <div class="page-header">
        <h3 class="page-title">My Rentals</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Rentals</li>
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
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Your Bookings</h4>
                    <p class="card-description">View and manage all your car rental bookings</p>

                    @if ($rentals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Car</th>
                                        <th>Pickup Date</th>
                                        <th>Return Date</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rentals as $rental)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $rental->car->featured_image }}"
                                                        alt="{{ $rental->car->full_name }}"
                                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                    <div class="ms-2">
                                                        <strong>{{ $rental->car->brand }} {{ $rental->car->model }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $rental->car->year }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $rental->pickup_date->format('M d, Y H:i') }}</td>
                                            <td>{{ $rental->return_date->format('M d, Y H:i') }}</td>
                                            <td>
                                                <strong>${{ number_format($rental->total_amount, 2) }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $rental->total_days }} days</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $rental->status_badge }}">
                                                    {{ $rental->status_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('rentals.show', $rental) }}"
                                                        class="btn btn-sm btn-outline-info">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                    @if ($rental->canBeCancelled())
                                                        <form action="{{ route('rentals.cancel', $rental) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                                <i class="mdi mdi-close"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $rentals->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-calendar-blank mdi-48px text-muted"></i>
                            <h4 class="mt-3">No Rentals Found</h4>
                            <p class="text-muted">You haven't made any car rentals yet.</p>
                            <a href="{{ route('cars.index') }}" class="btn btn-primary mt-2">
                                <i class="mdi mdi-car"></i> Browse Cars
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
