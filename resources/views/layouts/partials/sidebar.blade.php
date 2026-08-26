<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        @auth
            <!-- Car Search & Listings -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('cars.index') }}">
                    <span class="menu-title">Browse Cars</span>
                    <i class="mdi mdi-car menu-icon"></i>
                </a>
            </li>

            <!-- Vendor Specific Menu -->
            @can('vendor')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#vendorMenu" aria-expanded="false"
                        aria-controls="vendorMenu">
                        <span class="menu-title">Vendor Dashboard</span>
                        <i class="menu-arrow"></i>
                        <i class="mdi mdi-store menu-icon"></i>
                    </a>
                    <div class="collapse" id="vendorMenu">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('vendor.cars.create') }}">
                                    <i class="mdi mdi-plus"></i> Add New Car
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('vendor.cars') }}">
                                    <i class="mdi mdi-format-list-bulleted"></i> My Cars
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('vendor.rentals') }}">
                                    <i class="mdi mdi-calendar-clock"></i> My Rentals
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @elsecan('admin')
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#adminMenu" aria-expanded="false"
                        aria-controls="adminMenu">
                        <span class="menu-title">Admin Panel</span>
                        <i class="menu-arrow"></i>
                        <i class="mdi mdi-shield menu-icon"></i>
                    </a>
                    <div class="collapse" id="adminMenu">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users') }}">
                                    <i class="mdi mdi-account-multiple"></i> Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.cars') }}">
                                    <i class="mdi mdi-car"></i> Manage Cars
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.vendors') }}">
                                    <i class="mdi mdi-store"></i> Vendors
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.rentals') }}">
                                    <i class="mdi mdi-calendar-check"></i> All Rentals
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            <!-- User Menu -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#userMenu" aria-expanded="false"
                    aria-controls="userMenu">
                    <span class="menu-title">My Bookings</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-calendar menu-icon"></i>
                </a>
                <div class="collapse" id="userMenu">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('rentals.index') }}">
                                <i class="mdi mdi-format-list-bulleted"></i> My Rentals
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.edit') }}">
                                <i class="mdi mdi-account"></i> Profile
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @else
            <!-- Guest Menu -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">
                    <span class="menu-title">Login</span>
                    <i class="mdi mdi-login menu-icon"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">
                    <span class="menu-title">Register</span>
                    <i class="mdi mdi-account-plus menu-icon"></i>
                </a>
            </li>
        @endauth
    </ul>
</nav>
