<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::with(['category', 'vendor'])
            ->where('is_approved', true);

        // Apply filters
        if ($request->filled('brand')) {
            $query->where('brand', 'LIKE', '%' . $request->brand . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('price_min')) {
            $query->where('price_per_day', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price_per_day', '<=', $request->price_max);
        }

        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }

        $cars = $query->paginate(12);
        $categories = CarCategory::all();

        return view('cars.index', compact('cars', 'categories'));
    }

    public function show(Car $car)
    {
        $car->load(['vendor', 'category', 'rentals' => function ($query) {
            $query->whereIn('status', ['pending', 'confirmed', 'active']);
        }]);

        $similarCars = Car::where('category_id', $car->category_id)
            ->where('id', '!=', $car->id)
            ->where('is_available', true)
            ->where('is_approved', true)
            ->limit(4)
            ->get();

        return view('cars.show', compact('car', 'similarCars'));
    }

    public function search(Request $request)
    {
        $query = Car::with(['category', 'vendor'])
            ->where('is_available', true)
            ->where('is_approved', true);

        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }

        if ($request->filled('brand')) {
            $query->where('brand', 'LIKE', '%' . $request->brand . '%');
        }

        if ($request->filled('price_min')) {
            $query->where('price_per_day', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price_per_day', '<=', $request->price_max);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $cars = $query->paginate(12);
        $categories = CarCategory::all();

        return view('cars.index', compact('cars', 'categories'));
    }

    public function create()
    {
        $categories = CarCategory::all();
        return view('vendor.cars.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'color' => 'required|string|max:50',
            'seats' => 'required|integer|min:1|max:15',
            'transmission' => 'required|in:automatic,manual',
            'fuel_type' => 'required|in:petrol,diesel,electric',
            'price_per_day' => 'required|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
            'description' => 'required|string|min:50',
            'category_id' => 'required|exists:car_categories,id',
            'license_plate' => 'required|unique:cars',
            'location' => 'required|string|max:255',
            'features' => 'array',
            'images' => 'array|max:5',
            'images.*' => 'image|max:2048',
        ]);

        $car = new Car($validated);
        $car->vendor_id = Auth::id();
        $car->is_approved = false;
        $car->is_available = true;

        // Handle features
        if ($request->has('features')) {
            $car->features = array_filter($request->features);
        }

        // Handle images
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('cars', 'public');
                $images[] = $path;
            }
            $car->images = $images;
        }

        $car->save();

        return redirect()->route('vendor.cars')
            ->with('success', 'Car listing created successfully! Awaiting admin approval.');
    }

    public function edit(Car $car)
    {
        // Check if user owns this car
        if ($car->vendor_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $categories = CarCategory::all();
        return view('vendor.cars.edit', compact('car', 'categories'));
    }

    public function update(Request $request, Car $car)
    {
        // Check if user owns this car
        if ($car->vendor_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'color' => 'required|string|max:50',
            'seats' => 'required|integer|min:1|max:15',
            'transmission' => 'required|in:automatic,manual',
            'fuel_type' => 'required|in:petrol,diesel,electric',
            'price_per_day' => 'required|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
            'description' => 'required|string|min:50',
            'category_id' => 'required|exists:car_categories,id',
            'location' => 'required|string|max:255',
            'features' => 'array',
            'images' => 'array|max:5',
            'images.*' => 'image|max:2048',
        ]);

        $car->fill($validated);

        // Handle features
        if ($request->has('features')) {
            $car->features = array_filter($request->features);
        }

        // Handle images
        if ($request->hasFile('images')) {
            // Delete old images
            if ($car->images) {
                foreach ($car->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('cars', 'public');
                $images[] = $path;
            }
            $car->images = $images;
        }

        $car->save();

        return redirect()->route('vendor.cars')
            ->with('success', 'Car listing updated successfully!');
    }

    public function destroy(Car $car)
    {
        // Check if user owns this car
        if ($car->vendor_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Delete images
        if ($car->images) {
            foreach ($car->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $car->delete();

        return redirect()->route('vendor.cars')
            ->with('success', 'Car listing deleted successfully!');
    }
}
