<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function profile(): View
    {
        return view('user.profile');
    }

    public function notifications(): View
    {
        return view('user.notifications');
    }

    public function activities(): View
    {
        return view('user.activities');
    }

    public function settings(): View
    {
        return view('user.settings');
    }

    public function help(): View
    {
        return view('user.help');
    }

    // Additional user methods...
}
