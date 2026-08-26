{{-- Original commented code removed for clarity --}}

<x-guest-layout>
    <div class="text-center mb-4">
        <h4 class="font-weight-bold">{{ __('Create Account') }}</h4>
        <h6 class="font-weight-light text-muted">{{ __('Signing up is easy. It only takes a few steps') }}</h6>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            <strong>{{ __('Please fix the following errors:') }}</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="pt-3" id="registerForm">
        @csrf

        <div class="form-group">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="form-control form-control-lg" type="text" name="name"
                :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your full name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="form-group">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="form-control form-control-lg" type="email" name="email"
                :value="old('email')" required autocomplete="username" placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="form-group">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="form-control form-control-lg" type="password" name="password" required
                autocomplete="new-password" placeholder="Create a password" />
            <div class="mt-1">
                <small class="text-muted">
                    <i class="mdi mdi-information-outline"></i>
                    Password must be at least 8 characters
                </small>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="form-group">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="form-control form-control-lg" type="password"
                name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Role Selection --}}
        <div class="form-group">
            <x-input-label for="role" :value="__('Account Type')" />
            <select id="role" name="role" class="form-control form-control-lg" required>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>
                    <i class="mdi mdi-account"></i> {{ __('Regular User') }}
                </option>
                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>
                    <i class="mdi mdi-briefcase"></i> {{ __('Manager') }}
                </option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                    <i class="mdi mdi-shield"></i> {{ __('Administrator') }}
                </option>
            </select>
            <small class="text-muted">
                <i class="mdi mdi-information-outline"></i>
                {{ __('Select the type of account you want to create') }}
            </small>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        {{-- Role Description --}}
        <div class="form-group">
            <div class="p-3 bg-light rounded" id="roleDescription">
                <div class="d-flex align-items-start">
                    <i class="mdi mdi-information-outline text-primary me-2" style="font-size: 20px;"></i>
                    <div>
                        <strong id="roleTitle">{{ __('Regular User') }}</strong>
                        <p class="mb-0 text-muted small" id="roleDesc">
                            {{ __('Access to personal dashboard and profile management.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <div class="form-check">
                <label class="form-check-label text-muted">
                    <input type="checkbox" class="form-check-input" name="terms" id="terms" required>
                    {{ __('I agree to all') }}
                    <a href="#" class="text-primary text-decoration-none">{{ __('Terms & Conditions') }}</a>
                    {{ __('and') }}
                    <a href="#" class="text-primary text-decoration-none">{{ __('Privacy Policy') }}</a>
                </label>
            </div>
        </div>

        <div class="mt-3 d-grid gap-2">
            <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" id="registerBtn">
                <i class="mdi mdi-account-plus me-2"></i>
                {{ __('SIGN UP') }}
            </button>
        </div>

        <div class="text-center mt-4 font-weight-light">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold">
                {{ __('Login Here') }}
                <i class="mdi mdi-arrow-right ms-1"></i>
            </a>
        </div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Role descriptions
            const roleDescriptions = {
                'user': {
                    title: '{{ __("Regular User") }}',
                    desc: '{{ __("Access to personal dashboard, profile management, and basic features.") }}'
                },
                'manager': {
                    title: '{{ __("Manager") }}',
                    desc: '{{ __("Access to team management, project oversight, and advanced reporting.") }}'
                },
                'admin': {
                    title: '{{ __("Administrator") }}',
                    desc: '{{ __("Full system access, user management, role assignments, and system configuration.") }}'
                }
            };

            // Role select change handler
            const roleSelect = document.getElementById('role');
            const roleTitle = document.getElementById('roleTitle');
            const roleDesc = document.getElementById('roleDesc');

            roleSelect.addEventListener('change', function() {
                const selectedRole = this.value;
                const roleInfo = roleDescriptions[selectedRole];
                
                if (roleInfo) {
                    roleTitle.textContent = roleInfo.title;
                    roleDesc.textContent = roleInfo.desc;
                    
                    // Add visual feedback
                    const parentDiv = this.closest('.form-group');
                    parentDiv.classList.add('highlight');
                    setTimeout(() => {
                        parentDiv.classList.remove('highlight');
                    }, 1000);
                }
            });

            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-dismissible');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Form submission handling
            const form = document.getElementById('registerForm');
            const registerBtn = document.getElementById('registerBtn');

            form.addEventListener('submit', function(e) {
                // Disable button to prevent multiple submissions
                registerBtn.disabled = true;
                registerBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    {{ __('Creating Account...') }}
                `;
            });
        });
    </script>
    @endpush

    @push('styles')
    <style>
        .highlight {
            animation: highlightFade 1s ease;
        }

        @keyframes highlightFade {
            0% { background-color: rgba(124, 77, 255, 0.1); }
            100% { background-color: transparent; }
        }

        #roleDescription {
            transition: all 0.3s ease;
            border-left: 3px solid #7c4dff;
        }

        #roleDescription:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        .form-control:focus {
            border-color: #7c4dff;
            box-shadow: 0 0 0 0.2rem rgba(124, 77, 255, 0.25);
        }

        .auth-form-btn {
            transition: all 0.3s ease;
        }

        .auth-form-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(124, 77, 255, 0.3);
        }

        .auth-link {
            transition: all 0.3s ease;
        }

        .auth-link:hover {
            color: #6c3cff !important;
            text-decoration: underline !important;
        }
    </style>
    @endpush
</x-guest-layout>