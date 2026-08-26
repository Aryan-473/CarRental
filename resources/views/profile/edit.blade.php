<x-app-layout>
    <x-slot name="header">
        {{ __('Profile') }}
    </x-slot>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Profile Information</h4>
                    <p class="card-description">Update your account's profile information and email address.</p>

                    <!-- Display User Role -->
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong>Role:</strong> {{ Auth::user()->role_label }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Update Password</h4>
                    <p class="card-description">Ensure your account is using a long, random password to stay secure.</p>
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-danger">Delete Account</h4>
                    <p class="card-description">Once your account is deleted, all of its resources and data will be
                        permanently deleted.</p>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
