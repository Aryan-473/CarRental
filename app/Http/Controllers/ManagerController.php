<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ManagerController extends Controller
{
    public function dashboard(): View
    {
        // Add manager dashboard data
        return view('manager.dashboard');
    }

    public function projects(): View
    {
        return view('manager.projects');
    }

    public function team(): View
    {
        return view('manager.team');
    }

    public function reports(): View
    {
        return view('manager.reports');
    }
}
