<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::where('is_available', true)
                   ->where('is_approved', true)
                   ->with('category', 'vendor')
                   ->paginate(12);
        $categories = CarCategory::all();
        return view('cars.index', compact('cars', 'categories'));
    }

    public function show(Car $car)
    {
        $car->load('vendor', 'category', 'rentals');
        $similarCars = Car::where('category_id', $car->category_id)
                          ->where('id', '!=', $car->id)
                          ->where('is_available', true)
                          ->limit(4)
                          ->get();
        return view('cars.show', compact('car', 'similarCars'));
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
            'color' => 'required|string',
            'seats' => 'required|integer|min:1|max:15',
            'transmission' => 'required|in:automatic,manual',
            'fuel_type' => 'required|in:petrol,diesel,electric',
            'price_per_day' => 'required|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
            'description' => 'required|string|min:50',
            'category_id' => 'required|exists:car_categories,id',
            'license_plate' => 'required|unique:cars',
            'location' => 'required|string',
            'features' => 'array',
            'images' => 'array|max:5',
            'images.*' => 'image|max:2048',
        ]);

        $car = new Car($validated);
        $car->vendor_id = Auth::id();
        $car->is_approved = false; // Requires admin approval
        
        // Handle images
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('cars', 'public');
                $images[] = $path;
            }
            $car->images = json_encode($images);
        }

        $car->save();

        return redirect()->route('vendor.cars')->with('success', 'Car listing created. Awaiting admin approval.');
    }

    public function search(Request $request)
    {
        $query = Car::where('is_available', true)->where('is_approved', true);
        
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }
        
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
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
        
        return view('cars.search', compact('cars', 'categories'));
    }
}