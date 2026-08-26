{{--  <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>  --}}


<x-guest-layout>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <h4>Forgot Password</h4>
    <h6 class="font-weight-light">Enter your email address and we'll send you a password reset link.</h6>

    <form method="POST" action="{{ route('password.email') }}" class="pt-3">
        @csrf

        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-control form-control-lg" type="email" name="email"
                :value="old('email')" required autofocus placeholder="Email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-3 d-grid gap-2">
            <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>

        <div class="text-center mt-4 font-weight-light">
            <a href="{{ route('login') }}" class="text-primary">{{ __('Back to Login') }}</a>
        </div>
    </form>
</x-guest-layout>
