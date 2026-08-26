<?php

namespace App\Http\Controllers;

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

    // Additional admin methods...
}
