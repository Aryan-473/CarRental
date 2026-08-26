@extends('layouts.userapp')

@section('contents_title')
    <div class="page-header">
        <h3 class="page-title">Add New Car</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('vendor.cars') }}">My Cars</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add New Car</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents_body')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('vendor.cars.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Basic Information</h5>

                                <div class="mb-3">
                                    <label for="brand" class="form-label">Brand <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="brand" id="brand"
                                        class="form-control @error('brand') is-invalid @enderror"
                                        value="{{ old('brand') }}" required>
                                    @error('brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="model" class="form-label">Model <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="model" id="model"
                                        class="form-control @error('model') is-invalid @enderror"
                                        value="{{ old('model') }}" required>
                                    @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="year" class="form-label">Year <span class="text-danger">*</span></label>
                                    <input type="number" name="year" id="year"
                                        class="form-control @error('year') is-invalid @enderror"
                                        value="{{ old('year', date('Y')) }}" min="1900" max="{{ date('Y') }}"
                                        required>
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="color" class="form-label">Color <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="color" id="color"
                                        class="form-control @error('color') is-invalid @enderror"
                                        value="{{ old('color') }}" required>
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category <span
                                            class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id"
                                        class="form-control @error('category_id') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="license_plate" class="form-label">License Plate <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="license_plate" id="license_plate"
                                        class="form-control @error('license_plate') is-invalid @enderror"
                                        value="{{ old('license_plate') }}" required>
                                    @error('license_plate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Specifications -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Specifications</h5>

                                <div class="mb-3">
                                    <label for="seats" class="form-label">Number of Seats <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="seats" id="seats"
                                        class="form-control @error('seats') is-invalid @enderror"
                                        value="{{ old('seats', 4) }}" min="1" max="15" required>
                                    @error('seats')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="transmission" class="form-label">Transmission <span
                                            class="text-danger">*</span></label>
                                    <select name="transmission" id="transmission"
                                        class="form-control @error('transmission') is-invalid @enderror" required>
                                        <option value="">Select Transmission</option>
                                        <option value="automatic"
                                            {{ old('transmission') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                                        <option value="manual" {{ old('transmission') == 'manual' ? 'selected' : '' }}>
                                            Manual</option>
                                    </select>
                                    @error('transmission')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="fuel_type" class="form-label">Fuel Type <span
                                            class="text-danger">*</span></label>
                                    <select name="fuel_type" id="fuel_type"
                                        class="form-control @error('fuel_type') is-invalid @enderror" required>
                                        <option value="">Select Fuel Type</option>
                                        <option value="petrol" {{ old('fuel_type') == 'petrol' ? 'selected' : '' }}>Petrol
                                        </option>
                                        <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel
                                        </option>
                                        <option value="electric" {{ old('fuel_type') == 'electric' ? 'selected' : '' }}>
                                            Electric</option>
                                    </select>
                                    @error('fuel_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="price_per_day" class="form-label">Price per Day ($) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="price_per_day" id="price_per_day"
                                        class="form-control @error('price_per_day') is-invalid @enderror"
                                        value="{{ old('price_per_day') }}" min="0" required>
                                    @error('price_per_day')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="security_deposit" class="form-label">Security Deposit ($) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="security_deposit" id="security_deposit"
                                        class="form-control @error('security_deposit') is-invalid @enderror"
                                        value="{{ old('security_deposit', 0) }}" min="0" required>
                                    @error('security_deposit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Location & Description -->
                            <div class="col-md-12">
                                <h5 class="mb-3 mt-3">Location & Description</h5>

                                <div class="mb-3">
                                    <label for="location" class="form-label">Location <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="location" id="location"
                                        class="form-control @error('location') is-invalid @enderror"
                                        value="{{ old('location') }}" placeholder="e.g., New York, NY" required>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description <span
                                            class="text-danger">*</span></label>
                                    <textarea name="description" id="description" rows="4"
                                        class="form-control @error('description') is-invalid @enderror" placeholder="Describe your car in detail..."
                                        required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Features & Images -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Features</h5>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="features[]" value="Air Conditioning"
                                            class="form-check-input" id="feature_ac"
                                            {{ old('features') && in_array('Air Conditioning', old('features')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feature_ac">Air Conditioning</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="features[]" value="GPS Navigation"
                                            class="form-check-input" id="feature_gps"
                                            {{ old('features') && in_array('GPS Navigation', old('features')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feature_gps">GPS Navigation</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="features[]" value="Bluetooth"
                                            class="form-check-input" id="feature_bluetooth"
                                            {{ old('features') && in_array('Bluetooth', old('features')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feature_bluetooth">Bluetooth</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="features[]" value="Child Seat"
                                            class="form-check-input" id="feature_child"
                                            {{ old('features') && in_array('Child Seat', old('features')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feature_child">Child Seat</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="features[]" value="Sunroof"
                                            class="form-check-input" id="feature_sunroof"
                                            {{ old('features') && in_array('Sunroof', old('features')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feature_sunroof">Sunroof</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="features[]" value="Leather Seats"
                                            class="form-check-input" id="feature_leather"
                                            {{ old('features') && in_array('Leather Seats', old('features')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feature_leather">Leather Seats</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="features[]" value="Backup Camera"
                                            class="form-check-input" id="feature_camera"
                                            {{ old('features') && in_array('Backup Camera', old('features')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feature_camera">Backup Camera</label>
                                    </div>
                                    @error('features')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="mb-3">Images</h5>
                                <div class="mb-3">
                                    <label for="images" class="form-label">Upload Images (Max 5)</label>
                                    <input type="file" name="images[]" id="images"
                                        class="form-control @error('images.*') is-invalid @enderror" accept="image/*"
                                        multiple>
                                    <small class="text-muted">You can upload up to 5 images (JPG, PNG, GIF - Max 2MB
                                        each)</small>
                                    @error('images.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="imagePreview" class="mt-2"></div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-12">
                                <hr>
                                <div class="alert alert-info">
                                    <i class="mdi mdi-information-outline"></i>
                                    After submitting, your car listing will be reviewed by an administrator before it
                                    becomes visible to customers.
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-check-circle"></i> Submit for Approval
                                </button>
                                <a href="{{ route('vendor.cars') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('contents_jsbelow')
    <script>
        document.getElementById('images').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            if (this.files) {
                const files = Array.from(this.files);
                if (files.length > 5) {
                    alert('You can upload a maximum of 5 images.');
                    this.value = '';
                    return;
                }

                files.forEach(function(file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail me-2';
                        img.style.width = '100px';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
@endsection
