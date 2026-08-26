<?php

namespace App\Http\Controllers;

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
            $data['regularUsers'] = User::where('role', 'user')->count();

            // Business statistics (replace with your actual models)
            $data['totalRevenue'] = 0;
            $data['newOrders'] = 0;
            $data['adminActions'] = 0;
            $data['recentUsers'] = User::latest()->take(5)->get();
        } elseif ($user->isManager()) {
            // Manager statistics
            $data['teamMembers'] = User::where('role', 'user')->count(); // Or get actual team members
            $data['totalProjects'] = 0;
            $data['activeProjects'] = 0;
            $data['totalTasks'] = 0;
            $data['completedTasks'] = 0;
            $data['pendingTasks'] = 0;
        } else {
            // Regular user statistics
            $data['myActivities'] = 0;
            $data['memberSince'] = $user->created_at->format('M d, Y');
            $data['totalLogins'] = 0; // If you track logins
        }

        return view('dashboard', $data);
    }
}
