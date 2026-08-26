@extends('layouts.userapp')

@section('contents_title')
    <div class="page-header">
        <h3 class="page-title">My Rentals</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Vendor</a></li>
                <li class="breadcrumb-item active" aria-current="page">Rentals</li>
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">All Bookings</h4>
                    <p class="card-description">View all rental bookings for your cars</p>

                    @if ($rentals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Car</th>
                                        <th>Customer</th>
                                        <th>Pickup Date</th>
                                        <th>Return Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rentals as $rental)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $rental->car->brand }} {{ $rental->car->model }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $rental->car->year }}</small>
                                            </td>
                                            <td>
                                                {{ $rental->user->name ?? 'N/A' }}
                                                <br>
                                                <small class="text-muted">{{ $rental->user->email ?? '' }}</small>
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
                                                <a href="{{ route('rentals.show', $rental) }}"
                                                    class="btn btn-sm btn-outline-info">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
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
                            <p class="text-muted">No one has rented your cars yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
