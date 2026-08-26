@extends('layouts.userapp')

@section('contents_js_style_above')
    <style>
        .card-img-absolute {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            opacity: 0.1;
        }

        .bg-gradient-danger {
            background: linear-gradient(to right, #da8cff, #9a55ff);
        }

        .bg-gradient-info {
            background: linear-gradient(to right, #84d9d2, #07cdae);
        }

        .bg-gradient-success {
            background: linear-gradient(to right, #ffbf96, #fe7096);
        }

        .bg-gradient-warning {
            background: linear-gradient(to right, #f6c445, #fe8c00);
        }

        .bg-gradient-primary {
            background: linear-gradient(to right, #7c4dff, #448aff);
        }

        .bg-gradient-dark {
            background: linear-gradient(to right, #2c3e50, #3498db);
        }

        .bg-gradient-vendor {
            background: linear-gradient(to right, #667eea, #764ba2);
        }

        .card-img-holder {
            position: relative;
            overflow: hidden;
        }

        .row.gap-reduced {
            margin-bottom: -10px;
        }

        .row.gap-reduced>[class*="col-"] {
            padding-bottom: 10px;
        }

        .role-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-weight: 500;
        }

        .role-badge-admin {
            background-color: #dc3545;
            color: white;
        }

        .role-badge-manager {
            background-color: #ffc107;
            color: #212529;
        }

        .role-badge-vendor {
            background-color: #764ba2;
            color: white;
        }

        .role-badge-user {
            background-color: #28a745;
            color: white;
        }

        .role-specific-card {
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .role-specific-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .role-specific-card.admin-card {
            border-left-color: #dc3545;
        }

        .role-specific-card.manager-card {
            border-left-color: #ffc107;
        }

        .role-specific-card.vendor-card {
            border-left-color: #764ba2;
        }

        .role-specific-card.user-card {
            border-left-color: #28a745;
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .vendor-stats {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .car-status-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
        }

        .quick-action-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .quick-action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('contents_title')
    <div class="page-header">
        <h3 class="page-title">
            Dashboard
            @if (auth()->user()->isAdmin())
                <span class="badge bg-danger ms-2">Admin Panel</span>
            @elseif(auth()->user()->isManager())
                <span class="badge bg-warning ms-2 text-dark">Manager Panel</span>
            @elseif(auth()->user()->isVendor())
                <span class="badge bg-vendor ms-2">Vendor Panel</span>
            @else
                <span class="badge bg-success ms-2">User Panel</span>
            @endif
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @if (auth()->user()->isAdmin())
                    <li class="breadcrumb-item active" aria-current="page">Admin Dashboard</li>
                @elseif(auth()->user()->isManager())
                    <li class="breadcrumb-item active" aria-current="page">Manager Dashboard</li>
                @elseif(auth()->user()->isVendor())
                    <li class="breadcrumb-item active" aria-current="page">Vendor Dashboard</li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">User Dashboard</li>
                @endif
            </ol>
        </nav>
    </div>
@endsection

@section('contents_body')

    {{-- User Role Banner --}}
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-12">
            <div class="alert alert-{{ auth()->user()->isAdmin() ? 'danger' : (auth()->user()->isManager() ? 'warning' : (auth()->user()->isVendor() ? 'dark' : 'info')) }} d-flex align-items-center justify-content-between"
                role="alert">
                <div>
                    <i class="mdi mdi-account-badge"></i>
                    <strong>Welcome back, {{ Auth::user()->name }}!</strong>
                    <span class="ms-2">
                        <span class="role-badge role-badge-{{ auth()->user()->role }}">
                            <i class="mdi mdi-shield"></i>
                            {{ ucfirst($roleLabel ?? auth()->user()->role_label) }}
                        </span>
                    </span>
                </div>
                <div>
                    <small>
                        <i class="mdi mdi-clock"></i>
                        Member since: {{ Auth::user()->created_at->format('M d, Y') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================
        ADMIN DASHBOARD
    ============================================ --}}
    @if (auth()->user()->isAdmin())

        {{-- Admin Stats Cards --}}
        <div class="row gap-reduced">
            <div class="col-xl-3 col-lg-3 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-danger card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Total Users
                            <i class="mdi mdi-account mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ number_format($totalUsers ?? 0) }}</h2>
                        <h6 class="card-text">
                            <i class="mdi mdi-account-check"></i>
                            Active: {{ number_format($activeUsers ?? 0) }}
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Total Cars
                            <i class="mdi mdi-car mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ number_format($totalCars ?? 0) }}</h2>
                        <h6 class="card-text">
                            <i class="mdi mdi-check-circle"></i>
                            Available: {{ number_format($availableCars ?? 0) }}
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Revenue
                            <i class="mdi mdi-currency-usd mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">${{ number_format($totalRevenue ?? 0) }}</h2>
                        <h6 class="card-text">
                            <i class="mdi mdi-trending-up"></i>
                            Total Earnings
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-warning card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Pending Cars
                            <i class="mdi mdi-clock mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ number_format($pendingCars ?? 0) }}</h2>
                        <h6 class="card-text">
                            <i class="mdi mdi-shield"></i>
                            Awaiting Approval
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        {{-- Admin Quick Actions --}}
        <div class="row" style="margin-top: 15px; margin-bottom: 15px;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="mdi mdi-rocket"></i>
                            Admin Quick Actions
                        </h4>
                        <p class="card-description">Manage your application</p>

                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="{{ route('admin.users') }}" class="text-decoration-none">
                                    <div class="role-specific-card admin-card p-3 bg-light rounded quick-action-card">
                                        <i class="mdi mdi-account-multiple mdi-24px text-danger"></i>
                                        <h6 class="mt-2">Manage Users</h6>
                                        <small class="text-muted">View all registered users</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="{{ route('admin.cars') }}" class="text-decoration-none">
                                    <div class="role-specific-card admin-card p-3 bg-light rounded quick-action-card">
                                        <i class="mdi mdi-car mdi-24px text-danger"></i>
                                        <h6 class="mt-2">Manage Cars</h6>
                                        <small class="text-muted">View and approve cars</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="{{ route('admin.vendors') }}" class="text-decoration-none">
                                    <div class="role-specific-card admin-card p-3 bg-light rounded quick-action-card">
                                        <i class="mdi mdi-store mdi-24px text-danger"></i>
                                        <h6 class="mt-2">Manage Vendors</h6>
                                        <small class="text-muted">View all vendors</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="{{ route('admin.reports') }}" class="text-decoration-none">
                                    <div class="role-specific-card admin-card p-3 bg-light rounded quick-action-card">
                                        <i class="mdi mdi-chart-bar mdi-24px text-danger"></i>
                                        <h6 class="mt-2">Reports</h6>
                                        <small class="text-muted">View system reports</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Users Table (Admin Only) --}}
        <div class="row" style="margin-top: 5px;">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="mdi mdi-account-multiple"></i>
                            Recent Users
                        </h4>
                        <p class="card-description">Latest registered users</p>

                        @if (isset($recentUsers) && $recentUsers->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Joined</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentUsers as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'manager' ? 'warning' : ($user->role == 'vendor' ? 'dark' : 'info')) }}">
                                                        {{ ucfirst($user->role) }}
                                                    </span>
                                                </td>
                                                <td>{{ $user->created_at->diffForHumans() }}</td>
                                                <td>
                                                    @if ($user->deleted_at)
                                                        <span class="badge bg-danger">Deleted</span>
                                                    @else
                                                        <span class="badge bg-success">Active</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">No users registered yet.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================
        MANAGER DASHBOARD
    ============================================ --}}
    @elseif(auth()->user()->isManager())
        {{-- Manager Stats Cards --}}
        <div class="row gap-reduced">
            <div class="col-xl-3 col-lg-3 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Team Members
                            <i class="mdi mdi-account-group mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ number_format($teamMembers ?? 0) }}</h2>
                        <h6 class="card-text">
                            <i class="mdi mdi-account-check"></i>
                            Active team members
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Projects
                            <i class="mdi mdi-briefcase mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ number_format($totalProjects ?? 0) }}</h2>
                        <h6 class="card-text">
                            <i class="mdi mdi-check"></i>
                            Active: {{ number_format($activeProjects ?? 0) }}
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-warning card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Tasks
                            <i class="mdi mdi-checkbox-marked mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ number_format($totalTasks ?? 0) }}</h2>
                        <h6 class="card-text">
                            <i class="mdi mdi-check"></i>
                            Completed: {{ number_format($completedTasks ?? 0) }}
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-primary card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Pending
                            <i class="mdi mdi-clock mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ number_format($pendingTasks ?? 0) }}</h2>
                        <h6 class="card-text">
                            <i class="mdi mdi-alert"></i>
                            Tasks pending review
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        {{-- Manager Quick Actions --}}
        <div class="row" style="margin-top: 15px; margin-bottom: 15px;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="mdi mdi-rocket"></i>
                            Manager Quick Actions
                        </h4>
                        <p class="card-description">Manage your team and projects</p>

                        <div class="row">
                            <div class="col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('manager.projects') }}" class="text-decoration-none">
                                    <div class="role-specific-card manager-card p-3 bg-light rounded quick-action-card">
                                        <i class="mdi mdi-briefcase mdi-24px text-warning"></i>
                                        <h6 class="mt-2">My Projects</h6>
                                        <small class="text-muted">View and manage projects</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('manager.team') }}" class="text-decoration-none">
                                    <div class="role-specific-card manager-card p-3 bg-light rounded quick-action-card">
                                        <i class="mdi mdi-account-group mdi-24px text-warning"></i>
                                        <h6 class="mt-2">Team Management</h6>
                                        <small class="text-muted">Manage your team</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('manager.reports') }}" class="text-decoration-none">
                                    <div class="role-specific-card manager-card p-3 bg-light rounded quick-action-card">
                                        <i class="mdi mdi-file-document mdi-24px text-warning"></i>
                                        <h6 class="mt-2">Reports</h6>
                                        <small class="text-muted">View team reports</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================
        VENDOR DASHBOARD
    ============================================ --}}
    @elseif(auth()->user()->isVendor())
        {{-- Vendor Stats Cards --}}
        <div class="row gap-reduced">
            <div class="col-xl-3 col-lg-3 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-vendor card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Total Cars
                            <i class="mdi mdi-car mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ number_format($totalCars ?? 0) }}</h2>
                        <h6 class="card-text">
                            <i class="mdi mdi-check-circle"></i>
                            Active: {{ number_format($activeCars ?? 0) }}
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-warning card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Pending Approvals
                            <i class="mdi mdi-clock mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ number_format($pendingApprovals ?? 0) }}</h2>
                        <h6 class="card-text">
                            <i class="mdi mdi-shield"></i>
                            Awaiting admin review
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Total Rentals
                            <i class="mdi mdi-calendar-check mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ number_format($totalRentals ?? 0) }}</h2>
                        <h6 class="card-text">
                            <i class="mdi mdi-play"></i>
                            Active: {{ number_format($activeRentals ?? 0) }}
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Total Earnings
                            <i class="mdi mdi-currency-usd mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">${{ number_format($totalEarnings ?? 0) }}</h2>
                        <h6 class="card-text">
                            <i class="mdi mdi-chart-line"></i>
                            All time earnings
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        {{-- Vendor Quick Actions --}}
        <div class="row" style="margin-top: 15px; margin-bottom: 15px;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="mdi mdi-rocket"></i>
                            Vendor Quick Actions
                        </h4>
                        <p class="card-description">Manage your fleet and rentals</p>

                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="{{ route('vendor.cars.create') }}" class="text-decoration-none">
                                    <div class="role-specific-card vendor-card p-3 bg-light rounded quick-action-card">
                                        <i class="mdi mdi-car-plus mdi-24px text-dark"></i>
                                        <h6 class="mt-2">Add New Car</h6>
                                        <small class="text-muted">List a new vehicle</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="{{ route('vendor.cars') }}" class="text-decoration-none">
                                    <div class="role-specific-card vendor-card p-3 bg-light rounded quick-action-card">
                                        <i class="mdi mdi-format-list-bulleted mdi-24px text-dark"></i>
                                        <h6 class="mt-2">My Cars</h6>
                                        <small class="text-muted">View all listings</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="{{ route('vendor.rentals') }}" class="text-decoration-none">
                                    <div class="role-specific-card vendor-card p-3 bg-light rounded quick-action-card">
                                        <i class="mdi mdi-calendar-clock mdi-24px text-dark"></i>
                                        <h6 class="mt-2">My Rentals</h6>
                                        <small class="text-muted">View all bookings</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="{{ route('profile.edit') }}" class="text-decoration-none">
                                    <div class="role-specific-card vendor-card p-3 bg-light rounded quick-action-card">
                                        <i class="mdi mdi-account-edit mdi-24px text-dark"></i>
                                        <h6 class="mt-2">Profile</h6>
                                        <small class="text-muted">Update vendor info</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Cars & Rentals --}}
        <div class="row" style="margin-top: 5px;">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="mdi mdi-car"></i>
                            Recent Cars
                        </h4>
                        <p class="card-description">Your latest vehicle listings</p>

                        @if (isset($recentCars) && $recentCars->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Car</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentCars as $car)
                                            <tr>
                                                <td>{{ $car->brand }} {{ $car->model }}</td>
                                                <td>${{ number_format($car->price_per_day, 2) }}/day</td>
                                                <td>
                                                    <span class="badge bg-{{ $car->status_badge }} car-status-badge">
                                                        {{ $car->status_text }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted">No cars added yet.</p>
                                <a href="{{ route('vendor.cars.create') }}" class="btn btn-sm btn-primary">
                                    Add Your First Car
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="mdi mdi-calendar-clock"></i>
                            Recent Rentals
                        </h4>
                        <p class="card-description">Latest bookings for your cars</p>

                        @if (isset($recentRentals) && $recentRentals->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Car</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentRentals as $rental)
                                            <tr>
                                                <td>{{ $rental->car->brand }} {{ $rental->car->model }}</td>
                                                <td>{{ $rental->user->name ?? 'N/A' }}</td>
                                                <td>${{ number_format($rental->total_amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $rental->status_badge }} car-status-badge">
                                                        {{ $rental->status_text }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted">No rentals yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================
        REGULAR USER DASHBOARD
    ============================================ --}}
    @else
        {{-- User Stats Cards --}}
        <div class="row gap-reduced">
            <div class="col-xl-4 col-lg-4 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-primary card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">My Profile
                            <i class="mdi mdi-account mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ Auth::user()->name }}</h2>
                        <h6 class="card-text">{{ Auth::user()->email }}</h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">My Rentals
                            <i class="mdi mdi-car mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ number_format($myRentals ?? 0) }}</h2>
                        <h6 class="card-text">
                            <i class="mdi mdi-calendar"></i>
                            {{ number_format($activeRentals ?? 0) }} active
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white stat-card">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Member Since
                            <i class="mdi mdi-clock mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">
                            {{ isset($memberSince) ? $memberSince : Auth::user()->created_at->format('M d, Y') }}</h2>
                        <h6 class="card-text">
                            <i class="mdi mdi-calendar"></i>
                            {{ Auth::user()->created_at->diffForHumans() }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        {{-- User Quick Actions --}}
        <div class="row" style="margin-top: 15px; margin-bottom: 15px;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="mdi mdi-rocket"></i>
                            Quick Actions
                        </h4>
                        <p class="card-description">Access your most used features</p>

                        <div class="row">
                            <div class="col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('cars.index') }}" class="text-decoration-none">
                                    <div class="role-specific-card user-card p-3 bg-light rounded quick-action-card">
                                        <i class="mdi mdi-car mdi-24px text-success"></i>
                                        <h6 class="mt-2">Browse Cars</h6>
                                        <small class="text-muted">Find available cars</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('rentals.index') }}" class="text-decoration-none">
                                    <div class="role-specific-card user-card p-3 bg-light rounded quick-action-card">
                                        <i class="mdi mdi-calendar mdi-24px text-success"></i>
                                        <h6 class="mt-2">My Rentals</h6>
                                        <small class="text-muted">View your bookings</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-6 mb-3">
                                <a href="{{ route('profile.edit') }}" class="text-decoration-none">
                                    <div class="role-specific-card user-card p-3 bg-light rounded quick-action-card">
                                        <i class="mdi mdi-account-edit mdi-24px text-success"></i>
                                        <h6 class="mt-2">My Profile</h6>
                                        <small class="text-muted">Update your profile</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Welcome Card Row --}}
    <div class="row" style="margin-top: 5px;">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        Welcome to {{ config('app.name') }}
                        <span
                            class="badge bg-{{ auth()->user()->isAdmin() ? 'danger' : (auth()->user()->isManager() ? 'warning' : (auth()->user()->isVendor() ? 'dark' : 'success')) }} ms-2">
                            {{ auth()->user()->role_label }}
                        </span>
                    </h4>
                    <p class="card-description">
                        {{ __("You're logged in as ") }} <strong>{{ Auth::user()->name }}</strong>
                    </p>
                    <div class="alert alert-{{ auth()->user()->isAdmin() ? 'danger' : (auth()->user()->isManager() ? 'warning' : (auth()->user()->isVendor() ? 'dark' : 'success')) }}"
                        role="alert">
                        <i class="mdi mdi-check-circle"></i>
                        @if (auth()->user()->isAdmin())
                            Welcome back, Administrator! You have full access to all features.
                        @elseif(auth()->user()->isManager())
                            Welcome back, Manager! You can manage your team and projects.
                        @elseif(auth()->user()->isVendor())
                            Welcome back, Vendor! You can manage your fleet and view rental earnings.
                        @else
                            Welcome back, {{ Auth::user()->name }}! Explore our car rental services.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('contents_jsbelow')
    <script>
        $(document).ready(function() {
            console.log('Dashboard loaded');
            console.log('User Role: {{ auth()->user()->role }}');
            console.log('User Name: {{ auth()->user()->name }}');

            // Add animation to cards on hover
            $('.quick-action-card').hover(
                function() {
                    $(this).addClass('shadow');
                },
                function() {
                    $(this).removeClass('shadow');
                }
            );

            // Add animation to stat cards
            $('.stat-card').hover(
                function() {
                    $(this).css('transform', 'scale(1.02)');
                },
                function() {
                    $(this).css('transform', 'scale(1)');
                }
            );
        });
    </script>
@endsection
