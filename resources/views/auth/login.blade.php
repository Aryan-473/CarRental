{{-- Original commented code removed for clarity --}}

<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            <strong>{{ __('Login Failed!') }}</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="text-center mb-4">
        <h4 class="font-weight-bold">{{ __('Welcome Back!') }}</h4>
        <h6 class="font-weight-light text-muted">{{ __('Sign in to continue to your dashboard.') }}</h6>
    </div>

    <form method="POST" action="{{ route('login') }}" class="pt-3">
        @csrf

        <div class="form-group">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="form-control form-control-lg" type="email" name="email"
                :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="form-group">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="form-control form-control-lg" type="password" name="password" required
                autocomplete="current-password" placeholder="Enter your password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="my-2 d-flex justify-content-between align-items-center">
            <div class="form-check">
                <label class="form-check-label text-muted">
                    <input type="checkbox" class="form-check-input" name="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    {{ __('Remember me') }}
                </label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link text-primary text-decoration-none">
                    <i class="mdi mdi-lock-reset me-1"></i>
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="mt-3 d-grid gap-2">
            <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                <i class="mdi mdi-login me-2"></i>
                {{ __('SIGN IN') }}
            </button>
        </div>

        {{-- Role Information Display --}}
        <div class="mt-4 p-3 bg-light rounded">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted d-block">{{ __('Demo Credentials') }}</small>
                    <div class="mt-1">
                        <span class="badge bg-danger me-1">Admin</span>
                        <small class="text-muted">admin@example.com / password</small>
                    </div>
                    <div class="mt-1">
                        <span class="badge bg-warning text-dark me-1">Manager</span>
                        <small class="text-muted">manager@example.com / password</small>
                    </div>
                    <div class="mt-1">
                        <span class="badge bg-info me-1">User</span>
                        <small class="text-muted">user@example.com / password</small>
                    </div>
                </div>
                <i class="mdi mdi-information-outline text-muted" style="font-size: 24px;"></i>
            </div>
        </div>

        <div class="text-center mt-4 font-weight-light">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-bold">
                {{ __('Create One Now') }}
                <i class="mdi mdi-arrow-right ms-1"></i>
            </a>
        </div>
    </form>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-dismiss alerts after 5 seconds
                setTimeout(function() {
                    const alerts = document.querySelectorAll('.alert-dismissible');
                    alerts.forEach(function(alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    });
                }, 5000);
            });
        </script>
    @endpush
</x-guest-layout>
