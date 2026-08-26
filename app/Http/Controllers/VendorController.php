<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function dashboard(): View
    {
        $vendorId = Auth::id();
        
        $data = [
            'totalCars' => Car::where('vendor_id', $vendorId)->count(),
            'activeCars' => Car::where('vendor_id', $vendorId)->where('is_available', true)->count(),
            'pendingApprovals' => Car::where('vendor_id', $vendorId)->where('is_approved', false)->count(),
            'totalRentals' => Rental::whereHas('car', function($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })->count(),
            'activeRentals' => Rental::whereHas('car', function($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })->whereIn('status', ['confirmed', 'active'])->count(),
            'recentCars' => Car::where('vendor_id', $vendorId)->latest()->limit(5)->get(),
            'recentRentals' => Rental::whereHas('car', function($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })->latest()->limit(5)->get(),
        ];

        return view('vendor.dashboard', $data);
    }

    public function cars(): View
    {
        $cars = Car::where('vendor_id', Auth::id())
                   ->with('category')
                   ->paginate(10);
        return view('vendor.cars.index', compact('cars'));
    }
}