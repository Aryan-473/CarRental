<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function users(): View
    {
        $users = User::withTrashed()->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function roles(): View
    {
        return view('admin.roles');
    }

    public function settings(): View
    {
        return view('admin.settings');
    }

    public function reports(): View
    {
        return view('admin.reports');
    }

    public function cars(): View
    {
        $cars = Car::with(['vendor', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.cars', compact('cars'));
    }

    public function approveCar(Request $request, Car $car)
    {
        $car->update(['is_approved' => true]);
        return back()->with('success', 'Car approved successfully!');
    }

    public function rejectCar(Car $car)
    {
        $car->delete();
        return back()->with('success', 'Car rejected and removed.');
    }

    public function vendors(): View
    {
        $vendors = User::where('role', 'vendor')->paginate(15);
        return view('admin.vendors', compact('vendors'));
    }

    public function rentals(): View
    {
        $rentals = Rental::with(['user', 'car', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.rentals', compact('rentals'));
    }
}
