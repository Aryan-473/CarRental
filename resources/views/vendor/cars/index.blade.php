@extends('layouts.userapp')

@section('contents_title')
    <div class="page-header">
        <h3 class="page-title">My Cars</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Vendor</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Cars</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents_body')
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('vendor.cars.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Add New Car
            </a>
        </div>
    </div>

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
        @forelse($cars as $car)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <img src="{{ $car->featured_image }}" class="card-img-top" alt="{{ $car->full_name }}"
                        style="height: 200px; object-fit: cover;">

                    <div class="card-body">
                        <h5 class="card-title">{{ $car->brand }} {{ $car->model }}</h5>
                        <p class="card-text">
                            <span class="badge bg-info">{{ $car->year }}</span>
                            <span class="badge bg-{{ $car->status_badge }}">
                                {{ $car->status_text }}
                            </span>
                            @if (!$car->is_approved)
                                <span class="badge bg-warning text-dark">Pending Approval</span>
                            @endif
                        </p>
                        <p class="card-text">
                            <strong>Price:</strong> ${{ number_format($car->price_per_day, 2) }}/day<br>
                            <strong>Location:</strong> {{ $car->location }}
                        </p>
                        @php
                            $features = $car->features_array;
                        @endphp
                        @if (count($features) > 0)
                            <p class="card-text">
                                <strong>Features:</strong>
                                @foreach (array_slice($features, 0, 3) as $feature)
                                    <span class="badge bg-light text-dark">{{ $feature }}</span>
                                @endforeach
                                @if (count($features) > 3)
                                    <span class="badge bg-light text-dark">+{{ count($features) - 3 }} more</span>
                                @endif
                            </p>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('cars.show', $car) }}" class="btn btn-outline-info btn-sm">
                                <i class="mdi mdi-eye"></i> View
                            </a>
                            <a href="{{ route('vendor.cars.edit', $car) }}" class="btn btn-outline-primary btn-sm">
                                <i class="mdi mdi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('vendor.cars.destroy', $car) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this car?')">
                                    <i class="mdi mdi-delete"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="mdi mdi-car-off mdi-48px"></i>
                    <h4 class="mt-3">No cars listed</h4>
                    <p class="mb-0">Start by adding your first car to rent.</p>
                    <a href="{{ route('vendor.cars.create') }}" class="btn btn-primary mt-3">
                        <i class="mdi mdi-plus"></i> Add New Car
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="row">
        <div class="col-12">
            {{ $cars->links() }}
        </div>
    </div>
@endsection
