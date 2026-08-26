<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        // Common data for all users
        $data['user'] = $user;
        $data['userRole'] = $user->role;
        $data['roleLabel'] = $user->role_label;

        // Role-specific data
        if ($user->isAdmin()) {
            // Admin statistics
            $data['totalUsers'] = User::count();
            $data['activeUsers'] = User::whereNull('deleted_at')->count();
            $data['deletedUsers'] = User::onlyTrashed()->count();
            $data['adminUsers'] = User::where('role', 'admin')->count();
            $data['managerUsers'] = User::where('role', 'manager')->count();
            $data['vendorUsers'] = User::where('role', 'vendor')->count();
            $data['regularUsers'] = User::where('role', 'user')->count();
            $data['totalCars'] = Car::count();
            $data['availableCars'] = Car::where('is_available', true)->where('is_approved', true)->count();
            $data['pendingCars'] = Car::where('is_approved', false)->count();
            $data['totalRevenue'] = Rental::where('status', 'completed')->sum('total_amount');
            $data['recentUsers'] = User::latest()->take(5)->get();
        } elseif ($user->isManager()) {
            // Manager statistics
            $data['teamMembers'] = User::where('role', 'user')->count();
            $data['totalProjects'] = 0;
            $data['activeProjects'] = 0;
            $data['totalTasks'] = 0;
            $data['completedTasks'] = 0;
            $data['pendingTasks'] = 0;
        } elseif ($user->isVendor()) {
            // Vendor statistics
            $vendorId = $user->id;

            $data['totalCars'] = Car::where('vendor_id', $vendorId)->count();
            $data['activeCars'] = Car::where('vendor_id', $vendorId)
                ->where('is_available', true)
                ->where('is_approved', true)
                ->count();
            $data['pendingApprovals'] = Car::where('vendor_id', $vendorId)
                ->where('is_approved', false)
                ->count();
            $data['totalRentals'] = Rental::whereHas('car', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })->count();
            $data['activeRentals'] = Rental::whereHas('car', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })->whereIn('status', ['confirmed', 'active'])->count();
            $data['totalEarnings'] = Rental::whereHas('car', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })->where('status', 'completed')->sum('total_amount');
            $data['recentCars'] = Car::where('vendor_id', $vendorId)
                ->latest()
                ->limit(5)
                ->get();
            $data['recentRentals'] = Rental::whereHas('car', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })->latest()
                ->limit(5)
                ->get();
        } else {
            // Regular user statistics
            $data['myRentals'] = Rental::where('user_id', $user->id)->count();
            $data['activeRentals'] = Rental::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->count();
            $data['memberSince'] = $user->created_at->format('M d, Y');
            $data['totalLogins'] = 0;
        }

        return view('dashboard', $data);
    }
}
